<?php

namespace App\Http\Controllers;

use App\Interface\CodeGenerationServiceInterface;
use App\Models\Expanse;
use App\Utils\AccountLedgerUtil;
use App\Utils\AccountUtil;
use App\Utils\DayBookUtil;
use App\Utils\ExpenseDescriptionUtil;
use App\Utils\ExpenseUtil;
use App\Utils\UserActivityLogUtil;
use App\Utils\VoucherEntryCostCentreUtil;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ExpenseController extends Controller
{
    public function __construct(
        private ExpenseUtil $expenseUtil,
        private ExpenseDescriptionUtil $expenseDescriptionUtil,
        private VoucherEntryCostCentreUtil $voucherEntryCostCentreUtil,
        private AccountUtil $accountUtil,
        private AccountLedgerUtil $accountLedgerUtil,
        private UserActivityLogUtil $userActivityLogUtil,
        private DayBookUtil $dayBookUtil,
    ) {}

    public function index(Request $request)
    {
        if (!auth()->user()->can('view_expense')) {

            abort(403, 'Access Forbidden.');
        }

        if ($request->ajax()) {

            return $this->expenseUtil->expenseListTable($request);
        }

        $users = '';
        if (!auth()->user()->can('view_own_sale')) {

            $users = DB::table('users')->select('id', 'prefix', 'name', 'last_name', 'phone')->get();
        }

        return view('finance.vouchers.expenses.index', compact('users'));
    }

    public function show($id)
    {
        if (!auth()->user()->can('view_expense')) {

            abort(403, 'Access Forbidden.');
        }

        $expense = Expanse::with(
            [
                'purchase:id,invoice_id',
                'expenseDescriptions',
                'expenseDescriptions.paymentMethod',
                'expenseDescriptions.account:id,name,account_number',
                'expenseDescriptions.voucherEntryCostCentres',
                'expenseDescriptions.voucherEntryCostCentres.costCentre:id,name',
                'createdBy:id,prefix,name,last_name,phone',
            ]
        )->where('id', $id)->first();

        return view('finance.vouchers.expenses.ajax_view.show', compact('expense'));
    }

    public function create($mode)
    {
        if (!auth()->user()->can('add_expense')) {

            abort(403, 'Access Forbidden.');
        }

        $paymentMethods = DB::table('payment_methods')->select('id', 'name')->get();

        if ($mode == 1) {

            return view('finance.vouchers.expenses.create_expense_single_entry', compact('paymentMethods'));
        } else {

            return view('finance.vouchers.expenses.create_expense_double_entry', compact('paymentMethods'));
        }
    }

    public function store(Request $request, CodeGenerationServiceInterface $voucherGenerator)
    {
        if (!auth()->user()->can('add_expense')) {

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

            $settings = DB::table('general_settings')->select(['id', 'prefix'])->first();
            $expenseVoucherPrefix = json_decode($settings->prefix, true)['expenses'];

            $addExpense = $this->expenseUtil->addExpense(date: $request->date, remarks: $request->remarks, mode: 1, debitTotal: $request->debit_total, creditTotal: $request->credit_total, isTransactionDetails: $request->is_transaction_details, maintainCostCentre: $request->maintain_cost_centre, voucherGenerator: $voucherGenerator, expenseVoucherPrefix: $expenseVoucherPrefix);

            $cashBankAccountId = $this->expenseDescriptionUtil->getCashBankAccountId($request);

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

                // Add Expense Description
                $addExpenseDescription = $this->expenseDescriptionUtil->addExpenseDescription(expenseId: $addExpense->id, accountId: $account_id, paymentMethodId: $request->payment_method_ids[$index], amountType: $amountType, amount: $amount, transactionNo: $request->transaction_nos[$index], chequeNo: $request->cheque_nos[$index], chequeSerialNo: $request->cheque_serial_nos[$index], chequeIssueDate: $request->cheque_issue_dates[$index]);

                if ($request->maintain_cost_centre == 1) {

                    $expenseRowIndexNo = $request->indexes[$index];
                    if (isset($request->cost_centre_ids[$expenseRowIndexNo])) {

                        $this->voucherEntryCostCentreUtil->addVoucherEntryCostCentres($addExpenseDescription->id, 'expense', $request, $expenseRowIndexNo);
                    }
                }

                $cashBankAcId = $addExpenseDescription->is_cash_bank_ac == 0 ? $cashBankAccountId : null;

                //Add Account Ledger Entry
                $this->accountLedgerUtil->addAccountLedger(voucher_type_id: 5, date: $request->date, account_id: $account_id, trans_id: $addExpenseDescription->id, amount: $amount, amount_type: $ledgerAmountType, cash_bank_account_id: $cashBankAcId);

                $index++;
            }

            $this->userActivityLogUtil->addLog(action: 1, subject_type: 15, data_obj: $addExpense);

            // Add Day Book entry for Expense Voucher
            $this->dayBookUtil->addDayBook(voucherTypeId: 7, date: $request->date, accountId: $debitAccountId, transId: $addExpense->id, amount: $debitAmount, amountType: 'debit');

            DB::commit();
        } catch (Exception $e) {

            DB::rollBack();
        }

        return response()->json('Expense created successfully!');
    }

    public function edit($id)
    {
        if (!auth()->user()->can('edit_expense')) {

            abort(403, 'Access Forbidden.');
        }

        $paymentMethods = DB::table('payment_methods')->select('id', 'name')->get();

        $accountUtil = $this->accountUtil;

        $expense = Expanse::with(
            [
                'singleModeCreditDescription',
                'singleModeCreditDescription.account:id,name,account_number,account_group_id',
                'singleModeCreditDescription.account.group:id,main_group_number',
                'singleModeCreditDescription.paymentMethod:id,name',
                'singleModeDebitDescriptions',
                'singleModeDebitDescriptions.account:id,name,account_number,account_group_id',
                'singleModeDebitDescriptions.account.group:id,main_group_number',
                'singleModeDebitDescriptions.paymentMethod:id,name',
                'singleModeDebitDescriptions.voucherEntryCostCentres',
                'singleModeDebitDescriptions.voucherEntryCostCentres.costCentre:id,name',
                'expenseDescriptions',
                'expenseDescriptions.account:id,name,account_number,account_group_id',
                'expenseDescriptions.account.group:id,main_group_number',
                'expenseDescriptions.paymentMethod:id,name',
            ]
        )->where('id', $id)->first();

        $myArray = [];
        $index = 1;
        foreach ($expense->singleModeDebitDescriptions as $description) {

            if (count($description->voucherEntryCostCentres) > 0) {

                foreach ($description->voucherEntryCostCentres as $voucherEntryCostCentre) {

                    if (isset($myArray[$index])) {

                        array_push($myArray[$index], [
                            'cost_centre_id' => $voucherEntryCostCentre->cost_centre_id,
                            'cost_centre_name' => $voucherEntryCostCentre?->costCentre?->name,
                            'cost_centre_amount' => $voucherEntryCostCentre->amount,
                        ]);
                    } else {

                        $myArray[$index][] = [
                            'cost_centre_id' => $voucherEntryCostCentre->cost_centre_id,
                            'cost_centre_name' => $voucherEntryCostCentre?->costCentre?->name,
                            'cost_centre_amount' => $voucherEntryCostCentre->amount,
                        ];
                    }
                }
            }

            $index++;
        }

        $costCentreArr = $myArray;
        $totalEntries = $index;

        if ($expense->mode == 1) {

            return view('finance.vouchers.expenses.edit_expense_single_entry', compact('expense', 'accountUtil', 'paymentMethods', 'costCentreArr', 'totalEntries'));
        } else {

            return view('finance.vouchers.expenses.edit_expense_multiple_mode', compact('expense', 'accountUtil', 'paymentMethods', 'costCentreArr', 'totalEntries'));
        }
    }

    public function update(Request $request, $id)
    {
        if (!auth()->user()->can('edit_expense')) {

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

            $updateExpense = $this->expenseUtil->updateExpense(id: $id, date: $request->date, remarks: $request->remarks, debitTotal: $request->debit_total, creditTotal: $request->credit_total, isTransactionDetails: $request->is_transaction_details, maintainCostCentre: $request->maintain_cost_centre);

            $cashBankAccountId = $this->expenseDescriptionUtil->getCashBankAccountId($request);

            // Prepare unused deletable Expense Descriptions
            $this->expenseDescriptionUtil->prepareUnusedDeletableExpenseDescriptions($updateExpense->expenseDescriptions);

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

                // Update Journal Entry
                $updateExpenseDescription = $this->expenseDescriptionUtil->updateExpenseDescription(expenseId: $updateExpense->id, expenseDescriptionId: $request->expense_description_ids[$index], accountId: $account_id, paymentMethodId: $request->payment_method_ids[$index], amountType: $amountType, amount: $amount, transactionNo: $request->transaction_nos[$index], chequeNo: $request->cheque_nos[$index], chequeSerialNo: $request->cheque_serial_nos[$index], chequeIssueDate: $request->cheque_issue_dates[$index]);

                $this->voucherEntryCostCentreUtil->updateVoucherEntryCostCentres($updateExpenseDescription->id, 'expense', $request, $index);

                $cashBankAcId = $updateExpenseDescription->is_cash_bank_ac == 0 ? $cashBankAccountId : null;

                //Update Ledger Entry
                $this->accountLedgerUtil->updateAccountLedger(voucher_type_id: 5, date: $request->date, account_id: $account_id, trans_id: $updateExpenseDescription->id, amount: $amount, amount_type: $ledgerAmountType, cash_bank_account_id: $cashBankAcId);

                $index++;
            }

            // Delete unused Expense Description
            $this->expenseDescriptionUtil->deleteUnusedExpenseDescriptions($updateExpense->id);

            $this->userActivityLogUtil->addLog(action: 2, subject_type: 15, data_obj: $updateExpense);

            // Update Day Book entry for Expense Voucher
            $this->dayBookUtil->updateDayBook(voucherTypeId: 7, date: $request->date, accountId: $debitAccountId, transId: $updateExpense->id, amount: $debitAmount, amountType: 'debit');

            DB::commit();
        } catch (Exception $e) {

            DB::rollBack();
        }

        return response()->json('Expense updated successfully');
    }

    public function delete(Request $request, $expanseId)
    {
        if (!auth()->user()->can('delete_expense')) {

            abort(403, 'Access Forbidden.');
        }

        try {

            DB::beginTransaction();
            $deleteExpense = Expanse::with('purchase')->where('id', $expanseId)->first();

            if ($deleteExpense->purchase) {

                return response()->json('Expense can not be deleted. This Expense is associated with purchase.');
            }

            $this->userActivityLogUtil->addLog(action: 3, subject_type: 15, data_obj: $deleteExpense);

            $this->expenseUtil->expenseDelete($deleteExpense);

            DB::commit();
        } catch (Exception $e) {

            DB::rollBack();
        }

        return response()->json('Successfully expanse is deleted');
    }
}
