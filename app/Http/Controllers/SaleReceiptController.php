<?php

namespace App\Http\Controllers;

use App\Interface\CodeGenerationServiceInterface;
use App\Models\Payment;
use App\Models\PaymentMethod;
use App\Models\Sale;
use App\Utils\AccountLedgerUtil;
use App\Utils\DayBookUtil;
use App\Utils\PaymentDescriptionReferenceUtil;
use App\Utils\PaymentDescriptionUtil;
use App\Utils\PaymentUtil;
use App\Utils\SaleUtil;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SaleReceiptController extends Controller
{
    public function __construct(
        private SaleUtil $saleUtil,
        private PaymentUtil $paymentUtil,
        private PaymentDescriptionUtil $paymentDescriptionUtil,
        private PaymentDescriptionReferenceUtil $paymentDescriptionReferenceUtil,
        private AccountLedgerUtil $accountLedgerUtil,
        private DayBookUtil $dayBookUtil,
    ) {
    }

    public function create($saleId)
    {
        $sale = Sale::with(
            [
                'customer:id,name,phone,address',
                'sr:id,prefix,name,last_name',
            ]
        )->where('id', $saleId)->first();

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

        return view('sales_app.ajax_view.sale_receipt_modal', compact('sale', 'accounts', 'methods'));
    }

    public function store(Request $request, $saleId, CodeGenerationServiceInterface $codeGenerationService)
    {
        $this->validate($request, [
            'received_amount' => 'required',
            'date' => 'required|date',
            'payment_method_id' => 'required',
            'account_id' => 'required',
        ], [
            'payment_method_id.required' => 'Receipt Type is required.',
            'payment_method_id.required' => 'Debit A/c is required.',
        ]);

        if ($request->received_amount == '' || $request->received_amount <= 0) {

            return response()->json(['errorMsg' => 'Received Amount field must be empty or 0']);
        }

        try {

            DB::beginTransaction();

            $sale = Sale::with('customer')->where('id', $saleId)->first();

            $settings = DB::table('general_settings')->select(['id', 'prefix', 'sale'])->first();
            $receiptVoucherPrefix = json_decode($settings->prefix, true)['sale_payment'];

            $addPayment = $this->paymentUtil->addPayment(date: $request->date, remarks: $request->remarks, paymentType: 1, voucherGenerator: $codeGenerationService, voucherPrefix: $receiptVoucherPrefix, debitTotal: $request->received_amount, creditTotal: $request->received_amount, saleRefId: $sale->id);

            //========================Debit===========================

            // Add Receipt Description debit Entry
            $addPaymentDebitDescription = $this->paymentDescriptionUtil->addPaymentDescription(paymentId: $addPayment->id, accountId: $request->account_id, paymentMethodId: $request->payment_method_id, amountType: 'dr', amount: $request->received_amount, transactionNo: $request->transaction_no, chequeNo: $request->cheque_no, chequeSerialNo: $request->cheque_serial_no, chequeIssueDate: $request->issue_date);

            //Add Debit Ledger Entry
            $this->accountLedgerUtil->addAccountLedger(voucher_type_id: 8, date: $request->date, account_id: $request->account_id, trans_id: $addPaymentDebitDescription->id, amount: $request->received_amount, amount_type: 'debit');

            //==========================Credit=========================

            // Add Receipt Description Credit Entry
            $addPaymentCreditDescription = $this->paymentDescriptionUtil->addPaymentDescription(paymentId: $addPayment->id, accountId: $sale->customer_account_id, paymentMethodId: null, amountType: 'cr', amount: $request->received_amount, userId: $sale->sr_user_id);

            $this->paymentDescriptionReferenceUtil->addPaymentDescriptionReferences(paymentDescriptionId: $addPaymentCreditDescription->id, refIdColNames: ['sale_id'], refIds: [$sale->id], amounts: [$request->received_amount]);

            //Add Credit Ledger Entry
            $this->accountLedgerUtil->addAccountLedger(voucher_type_id: 8, date: $request->date, account_id: $sale->customer_account_id, trans_id: $addPaymentCreditDescription->id, amount: $request->received_amount, amount_type: 'credit', user_id: $sale->sr_user_id, cash_bank_account_id: $request->account_id);

            //===========================Receipt Description End========================

            $adjustedSale = $this->saleUtil->adjustSaleInvoiceAmounts($sale);

            // Add Day Book entry for Expense Voucher
            $this->dayBookUtil->addDayBook(voucherTypeId: 9, date: $request->date, accountId: $sale->customer_account_id, transId: $addPayment->id, amount: $request->received_amount, amountType: 'credit');

            $receipt = Payment::with([
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

            return view('finance.vouchers.save_and_print_template.print_receipt_voucher', compact('receipt'));
        } else {

            return response()->json(['successMsg' => 'Receipt is added successfully']);
        }
    }
}
