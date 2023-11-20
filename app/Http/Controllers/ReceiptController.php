<?php

namespace App\Http\Controllers;

use App\Interface\CodeGenerationServiceInterface;
use App\Models\Payment;
use App\Utils\AccountLedgerUtil;
use App\Utils\AccountUtil;
use App\Utils\Converter;
use App\Utils\DayBookUtil;
use App\Utils\PaymentDescriptionUtil;
use App\Utils\PaymentUtil;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReceiptController extends Controller
{
    public function __construct(
        private AccountUtil $accountUtil,
        private Converter $converter,
        private PaymentUtil $paymentUtil,
        private PaymentDescriptionUtil $paymentDescriptionUtil,
        private AccountLedgerUtil $accountLedgerUtil,
        private DayBookUtil $dayBookUtil,
    ) {
    }

    public function index(Request $request)
    {
        if (! auth()->user()->can('receipts_index')) {

            abort(403, 'Access Forbidden.');
        }

        if ($request->ajax()) {

            return $this->paymentUtil->list(request: $request, paymentType: 1, converter: $this->converter);
        }

        return view('finance.vouchers.receipts.index');
    }

    public function show($id)
    {
        if (! auth()->user()->can('receipts_index')) {

            abort(403, 'Access Forbidden.');
        }

        $receipt = Payment::with([
            'descriptions',
            'user:id,prefix,name,last_name',
            'descriptions.paymentMethod:id,name',
            'descriptions.account:id,name,phone,account_number,account_group_id',
            'descriptions.account.group:id,name',
            'descriptions.user:id,prefix,name,last_name,phone',
            'descriptions.paymentMethod:id,name',
            'saleReference:id,invoice_id,order_id',
            'stockAdjustmentReference:id,voucher_no',
            'descriptions.references:id,payment_description_id,sale_id,purchase_id,stock_adjustment_id,amount',
            'descriptions.references:sale:id,invoice_id,order_id,status,order_status',
            'descriptions.references:purchase:id,invoice_id,purchase_status',
        ])->where('id', $id)->first();

        return view('finance.vouchers.receipts.ajax_view.show', compact('receipt'));
    }

    public function create($mode)
    {
        if (! auth()->user()->can('receipts_add')) {

            abort(403, 'Access Forbidden.');
        }

        $users = '';
        if (! auth()->user()->can('view_own_sale')) {

            $users = DB::table('users')->select('id', 'prefix', 'name', 'last_name', 'phone')->get();
        }

        $paymentMethods = DB::table('payment_methods')->select('id', 'name')->get();

        if ($mode == 1) {

            return view('finance.vouchers.receipts.create_receipt_single_entry', compact('users', 'paymentMethods'));
        } else {

            return view('finance.vouchers.receipts.create_receipt_double_entry', compact('users', 'paymentMethods'));
        }
    }

    public function store(Request $request, CodeGenerationServiceInterface $voucherGenerator)
    {
        if (! auth()->user()->can('receipts_add')) {

            abort(403, 'Access Forbidden.');
        }

        $this->validate($request, [
            'date' => 'required|date',
        ], ['date.date' => 'Date Format is invalid.']);

        if ($request->debit_total == 0 || $request->credit_total == 0) {

            return response()->json(['errorMsg' => 'Total Debit or Total Credit must not be 0']);
        } elseif ($request->debit_total != $request->credit_total) {

            return response()->json(['errorMsg' => 'Total Debit and Total Credit must be equal.']);
        }

        try {

            DB::beginTransaction();

            $gs = DB::table('general_settings')->select(['prefix'])->first();
            $receiptVoucherPrefix = json_decode($gs->prefix, true)['sale_payment'];
            $__receiptVoucherPrefix = $receiptVoucherPrefix != null ? $receiptVoucherPrefix : 'RV';

            $addPayment = $this->paymentUtil->addPayment(date: $request->date, remarks: $request->remarks, paymentType: 1, voucherGenerator: $voucherGenerator, voucherPrefix: $__receiptVoucherPrefix, debitTotal: $request->debit_total, creditTotal: $request->credit_total, isTransactionDetails: $request->is_transaction_details, mode: $request->mode);

            $cashBankAccountId = $this->paymentDescriptionUtil->getCashBankAccountId($request);

            $index = 0;
            $creditAccountId = '';
            $creditAmount = '';
            foreach ($request->account_ids as $account_id) {

                $amountType = $request->amount_types[$index] == 'Dr' || $request->amount_types[$index] == 'dr' ? 'dr' : 'cr';
                $ledgerAmountType = $request->amount_types[$index] == 'Dr' || $request->amount_types[$index] == 'dr' ? 'debit' : 'credit';
                $amount = $request->amount_types[$index] == 'Dr' || $request->amount_types[$index] == 'dr' ? $request->debit_amounts[$index] : $request->credit_amounts[$index];

                if ($creditAccountId == '' && ($request->amount_types[$index] == 'Cr' || $request->amount_types[$index] == 'cr')) {

                    $creditAccountId = $account_id;
                    $creditAmount = $request->credit_amounts[$index];
                }

                // Add Payment Description Entry
                $addPaymentDescription = $this->paymentDescriptionUtil->addPaymentDescription(paymentId: $addPayment->id, accountId: $account_id, paymentMethodId: $request->payment_method_ids[$index], amountType: $amountType, amount: $amount, userId: $request->user_ids[$index], transactionNo: $request->transaction_nos[$index], chequeNo: $request->cheque_nos[$index], chequeSerialNo: $request->cheque_serial_nos[$index], chequeIssueDate: $request->cheque_issue_dates[$index]);

                $cashBankAcId = $addPaymentDescription->is_cash_bank_ac == 0 ? $cashBankAccountId : null;

                //Add Credit Ledger Entry
                $this->accountLedgerUtil->addAccountLedger(voucher_type_id: 8, date: $request->date, account_id: $account_id, trans_id: $addPaymentDescription->id, amount: $amount, amount_type: $ledgerAmountType, user_id: $request->user_ids[$index], cash_bank_account_id: $cashBankAcId);

                $index++;
            }

            // Add Day Book entry for Receipt Voucher
            $this->dayBookUtil->addDayBook(voucherTypeId: 9, date: $request->date, accountId: $creditAccountId, transId: $addPayment->id, amount: $creditAmount, amountType: 'credit');

            DB::commit();
        } catch (Exception $e) {

            DB::rollBack();
        }

        return response()->json('Receipt created successfully');
    }

    public function edit($id)
    {
        if (! auth()->user()->can('receipts_edit')) {

            abort(403, 'Access Forbidden.');
        }

        $accountUtil = $this->accountUtil;
        $receipt = Payment::with([
            'singleModeDebitDescription',
            'singleModeDebitDescription.account:id,name,account_number',
            'singleModeDebitDescription.paymentMethod:id,name',
            'singleModeCreditDescriptions',
            'singleModeCreditDescriptions.user:id,prefix,name,last_name',
            'singleModeCreditDescriptions.account:id,name,account_number',
            'singleModeCreditDescriptions.paymentMethod:id,name',
            'descriptions',
            'descriptions.user:id,prefix,name,last_name',
            'descriptions.account:id,name,account_number',
            'descriptions.paymentMethod:id,name',
        ])->where('id', $id)->first();

        $users = '';
        if (! auth()->user()->can('view_own_sale')) {

            $users = DB::table('users')->select('id', 'prefix', 'name', 'last_name', 'phone')->get();
        }

        $paymentMethods = DB::table('payment_methods')->select('id', 'name')->get();

        if ($receipt->mode == 1) {

            return view('finance.vouchers.receipts.edit_receipt_single_entry', compact('receipt', 'accountUtil', 'users', 'paymentMethods'));
        } else {

            return view('finance.vouchers.receipts.edit_receipt_double_entry', compact('receipt', 'accountUtil', 'users', 'paymentMethods'));
        }
    }

    public function update(Request $request, $id)
    {
        if (! auth()->user()->can('receipts_edit')) {

            abort(403, 'Access Forbidden.');
        }

        $this->validate($request, [
            'date' => 'required|date',
        ], ['date.date' => 'Date Format is invalid.']);

        if ($request->debit_total == 0 || $request->credit_total == 0) {

            return response()->json(['errorMsg' => 'Total Debit or Total Credit must not be 0']);
        } elseif ($request->debit_total != $request->credit_total) {

            return response()->json(['errorMsg' => 'Total Debit and Total Credit must be equal.']);
        }

        try {

            DB::beginTransaction();

            $updatePayment = $this->paymentUtil->updatePayment(id: $id, date: $request->date, remarks: $request->remarks, debitTotal: $request->debit_total, creditTotal: $request->credit_total, isTransactionDetails: $request->is_transaction_details);

            $cashBankAccountId = $this->paymentDescriptionUtil->getCashBankAccountId($request);

            // Prepare unused deletable Payment Descriptions
            $this->paymentDescriptionUtil->prepareUnusedDeletablePaymentDescriptions($updatePayment->descriptions);

            $index = 0;
            $creditAccountId = '';
            $creditAmount = '';
            foreach ($request->account_ids as $account_id) {

                $amountType = $request->amount_types[$index] == 'Dr' || $request->amount_types[$index] == 'dr' ? 'dr' : 'cr';
                $ledgerAmountType = $request->amount_types[$index] == 'Dr' || $request->amount_types[$index] == 'dr' ? 'debit' : 'credit';
                $amount = $request->amount_types[$index] == 'Dr' || $request->amount_types[$index] == 'dr' ? $request->debit_amounts[$index] : $request->credit_amounts[$index];

                if ($creditAccountId == '' && ($request->amount_types[$index] == 'Cr' || $request->amount_types[$index] == 'cr')) {

                    $creditAccountId = $account_id;
                    $creditAmount = $request->credit_amounts[$index];
                }

                // Update Payment Description Entry
                $updatePaymentDescription = $this->paymentDescriptionUtil->updatePaymentDescription(paymentId: $updatePayment->id, paymentDescriptionId: $request->payment_description_ids[$index], accountId: $account_id, paymentMethodId: $request->payment_method_ids[$index], amountType: $amountType, amount: $amount, userId: $request->user_ids[$index], transactionNo: $request->transaction_nos[$index], chequeNo: $request->cheque_nos[$index], chequeSerialNo: $request->cheque_serial_nos[$index], chequeIssueDate: $request->cheque_issue_dates[$index]);

                $cashBankAcId = $updatePaymentDescription->is_cash_bank_ac == 0 ? $cashBankAccountId : null;

                //Update Ledger Entry
                $this->accountLedgerUtil->updateAccountLedger(voucher_type_id: 8, date: $request->date, account_id: $account_id, trans_id: $updatePaymentDescription->id, amount: $amount, amount_type: $ledgerAmountType, new_user_id: $request->user_ids[$index], cash_bank_account_id: $cashBankAcId);

                $index++;
            }

            // Delete unused Payment Descriptions
            $this->paymentDescriptionUtil->deleteUnusedPaymentDescriptions($updatePayment->id);

            // Update Day Book entry for Receipt Voucher
            $this->dayBookUtil->updateDayBook(voucherTypeId: 9, date: $request->date, accountId: $creditAccountId, transId: $updatePayment->id, amount: $creditAmount, amountType: 'credit');

            DB::commit();
        } catch (Exception $e) {

            DB::rollBack();
        }

        return response()->json('Receipt updated successfully');
    }

    public function delete(Request $receipt, $id)
    {
        if (! auth()->user()->can('receipts_delete')) {

            abort(403, 'Access Forbidden.');
        }

        try {

            DB::beginTransaction();

            $this->paymentUtil->deletePayment($id);
            DB::commit();
        } catch (Exception $e) {

            DB::rollBack();
        }

        return response()->json('Receipt deleted successfully');
    }

    public function userWiseCustomerClosingBalance(Request $request, $account_id)
    {
        return $this->accountUtil->accountClosingBalance($account_id, $request->user_id);
    }
}
