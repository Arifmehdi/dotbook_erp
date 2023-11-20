<?php

namespace App\Http\Controllers;

use App\Interface\CodeGenerationServiceInterface;
use App\Models\Payment;
use App\Utils\AccountLedgerUtil;
use App\Utils\AccountUtil;
use App\Utils\Converter;
use App\Utils\DayBookUtil;
use App\Utils\InvoiceVoucherRefIdUtil;
use App\Utils\PaymentDescriptionUtil;
use App\Utils\PaymentUtil;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PaymentController extends Controller
{
    public function __construct(
        private AccountUtil $accountUtil,
        private Converter $converter,
        private PaymentUtil $paymentUtil,
        private PaymentDescriptionUtil $paymentDescriptionUtil,
        private AccountLedgerUtil $accountLedgerUtil,
        private InvoiceVoucherRefIdUtil $invoiceVoucherRefIdUtil,
        private DayBookUtil $dayBookUtil,
    ) {
    }

    public function index(Request $request)
    {
        if (! auth()->user()->can('payments_index')) {

            abort(403, 'Access Forbidden.');
        }

        if ($request->ajax()) {

            return $this->paymentUtil->list(request: $request, paymentType: 2, converter: $this->converter);
        }

        $users = '';
        if (! auth()->user()->can('view_own_sale')) {

            $users = DB::table('users')->select('id', 'prefix', 'name', 'last_name', 'phone')->get();
        }

        $paymentMethods = DB::table('payment_methods')->select('id', 'name')->get();

        return view('finance.vouchers.payments.index', compact('users', 'paymentMethods'));
    }

    public function show($id)
    {
        if (! auth()->user()->can('payments_index')) {

            abort(403, 'Access Forbidden.');
        }

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
        ])->where('id', $id)->first();

        return view('finance.vouchers.payments.ajax_view.show', compact('payment'));
    }

    public function create($mode)
    {
        if (! auth()->user()->can('payments_add')) {

            abort(403, 'Access Forbidden.');
        }

        $users = '';
        if (! auth()->user()->can('view_own_sale')) {

            $users = DB::table('users')->select('id', 'prefix', 'name', 'last_name', 'phone')->get();
        }

        $paymentMethods = DB::table('payment_methods')->select('id', 'name')->get();

        if ($mode == 1) {

            return view('finance.vouchers.payments.create_payment_single_entry', compact('users', 'paymentMethods'));
        } else {

            return view('finance.vouchers.payments.create_payment_double_entry', compact('users', 'paymentMethods'));
        }
    }

    public function store(Request $request, CodeGenerationServiceInterface $voucherGenerator)
    {
        if (! auth()->user()->can('payments_add')) {

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
            $paymentVoucherPrefix = json_decode($gs->prefix, true)['purchase_payment'];
            $__paymentVoucherPrefix = $paymentVoucherPrefix != null ? $paymentVoucherPrefix : 'PV';

            $addPayment = $this->paymentUtil->addPayment(date: $request->date, remarks: $request->remarks, paymentType: 2, voucherGenerator: $voucherGenerator, voucherPrefix: $__paymentVoucherPrefix, debitTotal: $request->debit_total, creditTotal: $request->credit_total, isTransactionDetails: $request->is_transaction_details, mode: $request->mode);

            $cashBankAccountId = $this->paymentDescriptionUtil->getCashBankAccountId($request);

            $index = 0;
            $debitAccountId = '';
            $debitAmount = '';
            foreach ($request->account_ids as $account_id) {

                $amountType = $request->amount_types[$index] == 'Dr' || $request->amount_types[$index] == 'dr' ? 'dr' : 'cr';
                $ledgerAmountType = $request->amount_types[$index] == 'Dr' || $request->amount_types[$index] == 'dr' ? 'debit' : 'credit';
                $amount = $request->amount_types[$index] == 'Dr' || $request->amount_types[$index] == 'dr' ? $request->debit_amounts[$index] : $request->credit_amounts[$index];

                if ($debitAccountId == '' && ($request->amount_types[$index] == 'Dr' || $request->amount_types[$index] == 'dr')) {

                    $debitAccountId = $account_id;
                    $debitAmount = $request->debit_amounts[$index];
                }

                // Add Payment Description Entry
                $addPaymentDescription = $this->paymentDescriptionUtil->addPaymentDescription(paymentId: $addPayment->id, accountId: $account_id, paymentMethodId: $request->payment_method_ids[$index], amountType: $amountType, amount: $amount, userId: $request->user_ids[$index], transactionNo: $request->transaction_nos[$index], chequeNo: $request->cheque_nos[$index], chequeSerialNo: $request->cheque_serial_nos[$index], chequeIssueDate: $request->cheque_issue_dates[$index]);

                $cashBankAcId = $addPaymentDescription->is_cash_bank_ac == 0 ? $cashBankAccountId : null;

                //Add Credit Ledger Entry
                $this->accountLedgerUtil->addAccountLedger(voucher_type_id: 9, date: $request->date, account_id: $account_id, trans_id: $addPaymentDescription->id, amount: $amount, amount_type: $ledgerAmountType, user_id: $request->user_ids[$index], cash_bank_account_id: $cashBankAcId);

                $index++;
            }

            // Add Day Book entry for Payment Voucher
            $this->dayBookUtil->addDayBook(voucherTypeId: 10, date: $request->date, accountId: $debitAccountId, transId: $addPayment->id, amount: $debitAmount, amountType: 'debit');

            DB::commit();
        } catch (Exception $e) {

            DB::rollBack();
        }

        return response()->json('Payment created successfully');
    }

    public function edit($id)
    {
        if (! auth()->user()->can('payments_edit')) {

            abort(403, 'Access Forbidden.');
        }

        $accountUtil = $this->accountUtil;
        $payment = Payment::with([
            'singleModeCreditDescription',
            'singleModeCreditDescription.account:id,name,account_number',
            'singleModeCreditDescription.paymentMethod:id,name',
            'singleModeDebitDescriptions',
            'singleModeDebitDescriptions.user:id,prefix,name,last_name',
            'singleModeDebitDescriptions.account:id,name,account_number',
            'singleModeDebitDescriptions.paymentMethod:id,name',
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

        if ($payment->mode == 1) {

            return view('finance.vouchers.payments.edit_payment_single_entry', compact('payment', 'accountUtil', 'users', 'paymentMethods'));
        } else {

            return view('finance.vouchers.payments.edit_payment_double_entry', compact('payment', 'accountUtil', 'users', 'paymentMethods'));
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
            $debitAccountId = '';
            $debitAmount = '';
            foreach ($request->account_ids as $account_id) {

                $amountType = $request->amount_types[$index] == 'Dr' || $request->amount_types[$index] == 'dr' ? 'dr' : 'cr';
                $ledgerAmountType = $request->amount_types[$index] == 'Dr' || $request->amount_types[$index] == 'dr' ? 'debit' : 'credit';
                $amount = $request->amount_types[$index] == 'Dr' || $request->amount_types[$index] == 'dr' ? $request->debit_amounts[$index] : $request->credit_amounts[$index];

                if ($debitAccountId == '' && ($request->amount_types[$index] == 'Dr' || $request->amount_types[$index] == 'dr')) {

                    $debitAccountId = $account_id;
                    $debitAmount = $request->debit_amounts[$index];
                }

                // Update Payment Description Entry
                $updatePaymentDescription = $this->paymentDescriptionUtil->updatePaymentDescription(paymentId: $updatePayment->id, paymentDescriptionId: $request->payment_description_ids[$index], accountId: $account_id, paymentMethodId: $request->payment_method_ids[$index], amountType: $amountType, amount: $amount, userId: $request->user_ids[$index], transactionNo: $request->transaction_nos[$index], chequeNo: $request->cheque_nos[$index], chequeSerialNo: $request->cheque_serial_nos[$index], chequeIssueDate: $request->cheque_issue_dates[$index]);

                $cashBankAcId = $updatePaymentDescription->is_cash_bank_ac == 0 ? $cashBankAccountId : null;

                //Update Ledger Entry
                $this->accountLedgerUtil->updateAccountLedger(voucher_type_id: 9, date: $request->date, account_id: $account_id, trans_id: $updatePaymentDescription->id, amount: $amount, amount_type: $ledgerAmountType, new_user_id: $request->user_ids[$index], cash_bank_account_id: $cashBankAcId);

                $index++;
            }

            // Delete unused Payment Descriptions
            $this->paymentDescriptionUtil->deleteUnusedPaymentDescriptions($updatePayment->id);

            // Update Day Book entry for Payment Voucher
            $this->dayBookUtil->updateDayBook(voucherTypeId: 9, date: $request->date, accountId: $debitAccountId, transId: $updatePayment->id, amount: $debitAmount, amountType: 'debit');

            DB::commit();
        } catch (Exception $e) {

            DB::rollBack();
        }

        return response()->json('Payment updated successfully');
    }

    public function delete(Request $request, $id)
    {
        if (! auth()->user()->can('payments_delete')) {

            abort(403, 'Access Forbidden.');
        }

        try {

            DB::beginTransaction();

            $this->paymentUtil->deletePayment($id);

            DB::commit();
        } catch (Exception $e) {

            DB::rollBack();
        }

        return response()->json('Payment deleted successfully');
    }

    public function userWiseCustomerClosingBalance(Request $request, $account_id)
    {
        return $this->accountUtil->accountClosingBalance($account_id, $request->user_id);
    }
}
