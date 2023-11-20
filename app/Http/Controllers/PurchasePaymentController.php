<?php

namespace App\Http\Controllers;

use App\Interface\CodeGenerationServiceInterface;
use App\Models\Payment;
use App\Models\PaymentMethod;
use App\Models\Purchase;
use App\Utils\AccountLedgerUtil;
use App\Utils\PaymentDescriptionReferenceUtil;
use App\Utils\PaymentDescriptionUtil;
use App\Utils\PaymentUtil;
use App\Utils\PurchaseUtil;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PurchasePaymentController extends Controller
{
    public function __construct(
        private PurchaseUtil $purchaseUtil,
        private PaymentUtil $paymentUtil,
        private PaymentDescriptionUtil $paymentDescriptionUtil,
        private PaymentDescriptionReferenceUtil $paymentDescriptionReferenceUtil,
        private AccountLedgerUtil $accountLedgerUtil,
    ) {
    }

    public function create($purchaseId)
    {
        $purchase = Purchase::with('supplier:id,name,phone,address')->where('id', $purchaseId)->first();

        $accounts = DB::table('accounts')
            ->leftJoin('account_groups', 'accounts.account_group_id', 'account_groups.id')
            ->leftJoin('banks', 'accounts.bank_id', 'banks.id')
            ->whereIn('account_groups.sub_sub_group_number', [1, 2, 11])
            ->select(
                'accounts.id',
                'accounts.name',
                'accounts.account_number',
                'accounts.balance',
                'banks.name as bank'
            )->get();

        $methods = PaymentMethod::with(['methodAccount'])->select('id', 'name')->get();

        return view('procurement.ajax_view.purchase_payment_modal', compact('purchase', 'accounts', 'methods'));
    }

    public function store(Request $request, $purchaseId, CodeGenerationServiceInterface $codeGenerationService)
    {
        $this->validate($request, [
            'paying_amount' => 'required',
            'date' => 'required|date',
            'payment_method_id' => 'required',
            'account_id' => 'required',
        ], [
            'payment_method_id.required' => 'Payment Type is required.',
            'payment_method_id.required' => 'Credit A/c is required.',
        ]);

        if ($request->paying_amount == '' && $request->paying_amount <= 0) {

            return response()->json(['errorMsg' => 'Paying Amount field must be empty or 0']);
        }

        try {

            DB::beginTransaction();

            $purchase = Purchase::with('supplier')->where('id', $purchaseId)->first();

            $settings = DB::table('general_settings')->select(['id', 'prefix', 'purchase'])->first();
            $paymentVoucherPrefix = json_decode($settings->prefix, true)['purchase_payment'];

            $addPayment = $this->paymentUtil->addPayment(date: $request->date, remarks: $request->remarks, paymentType: 2, voucherGenerator: $codeGenerationService, voucherPrefix: $paymentVoucherPrefix, debitTotal: $request->paying_amount, creditTotal: $request->paying_amount, purchaseRefId: $purchase->id);

            // Add Payment Description Debit Entry
            $addPaymentDebitDescription = $this->paymentDescriptionUtil->addPaymentDescription(paymentId: $addPayment->id, accountId: $purchase->supplier_account_id, paymentMethodId: null, amountType: 'dr', amount: $request->paying_amount, chequeNo: null);

            $this->paymentDescriptionReferenceUtil->addPaymentDescriptionReferences(paymentDescriptionId: $addPaymentDebitDescription->id, refIdColNames: ['purchase_id'], refIds: [$purchase->id], amounts: [$request->paying_amount]);

            //Add Debit Ledger Entry
            $this->accountLedgerUtil->addAccountLedger(voucher_type_id: 9, date: $request->date, account_id: $purchase->supplier_account_id, trans_id: $addPaymentDebitDescription->id, amount: $request->paying_amount, amount_type: 'debit', cash_bank_account_id: $request->account_id);

            // Add Payment Description Credit Entry
            $addPaymentCreditDescription = $this->paymentDescriptionUtil->addPaymentDescription(paymentId: $addPayment->id, accountId: $request->account_id, paymentMethodId: $request->payment_method_id, amountType: 'cr', amount: $request->paying_amount, transactionNo: $request->transaction_no, chequeNo: $request->cheque_no, chequeSerialNo: $request->cheque_serial_no, chequeIssueDate: $request->issue_date);

            //Add Credit Ledger Entry
            $this->accountLedgerUtil->addAccountLedger(voucher_type_id: 9, date: $request->date, account_id: $request->account_id, trans_id: $addPaymentCreditDescription->id, amount: $request->paying_amount, amount_type: 'credit');

            $adjustedPurchase = $this->purchaseUtil->adjustPurchaseInvoiceAmounts($purchase);

            $payment = Payment::with([
                'descriptions',
                'user:id,prefix,name,last_name',
                'descriptions.paymentMethod:id,name',
                'descriptions.account:id,name,phone,account_number,account_group_id',
                'descriptions.account.group:id,name',
                'descriptions.user:id,prefix,name,last_name,phone',
                'descriptions.paymentMethod:id,name',
                'descriptions.references:id,payment_description_id,sale_id,purchase_id,stock_adjustment_id,amount',
                'descriptions.references:sale:id,invoice_id,order_id,status,order_status',
                'descriptions.references:purchase:id,invoice_id,purchase_status',
            ])->where('id', $addPayment->id)->first();

            DB::commit();
        } catch (Exception $e) {

            DB::rollBack();
        }

        if ($request->action == 'save_and_print') {

            return view('finance.vouchers.save_and_print_template.print_payment_voucher', compact('payment'));
        } else {

            return response()->json(['successMsg' => 'Payment is added successfully']);
        }
    }
}
