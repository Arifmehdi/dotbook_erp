<?php

namespace App\Http\Controllers;

use App\Interface\CodeGenerationServiceInterface;
use App\Models\Journal;
use App\Utils\AccountLedgerUtil;
use App\Utils\AccountUtil;
use App\Utils\Converter;
use App\Utils\DayBookUtil;
use App\Utils\InvoiceVoucherRefIdUtil;
use App\Utils\JournalEntryUtil;
use App\Utils\JournalUtil;
use App\Utils\UserActivityLogUtil;
use App\Utils\VoucherEntryCostCentreUtil;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class JournalController extends Controller
{
    protected $invoiceVoucherRefIdUtil;

    protected $userActivityLogUtil;

    protected $journalUtil;

    protected $journalEntryUtil;

    protected $accountUtil;

    protected $accountLedgerUtil;

    protected $converter;

    protected $voucherEntryCostCentreUtil;

    protected $dayBookUtil;

    public function __construct(
        InvoiceVoucherRefIdUtil $invoiceVoucherRefIdUtil,
        UserActivityLogUtil $userActivityLogUtil,
        JournalUtil $journalUtil,
        JournalEntryUtil $journalEntryUtil,
        AccountUtil $accountUtil,
        AccountLedgerUtil $accountLedgerUtil,
        Converter $converter,
        VoucherEntryCostCentreUtil $voucherEntryCostCentreUtil,
        DayBookUtil $dayBookUtil,
    ) {
        $this->invoiceVoucherRefIdUtil = $invoiceVoucherRefIdUtil;
        $this->userActivityLogUtil = $userActivityLogUtil;
        $this->journalUtil = $journalUtil;
        $this->journalEntryUtil = $journalEntryUtil;
        $this->accountUtil = $accountUtil;
        $this->accountLedgerUtil = $accountLedgerUtil;
        $this->converter = $converter;
        $this->voucherEntryCostCentreUtil = $voucherEntryCostCentreUtil;
        $this->dayBookUtil = $dayBookUtil;
    }

    public function index(Request $request)
    {
        if (! auth()->user()->can('journals_index')) {

            abort(403, 'Access Forbidden.');
        }

        if ($request->ajax()) {

            return $this->journalUtil->list($request, $this->converter);
        }

        $users = '';
        if (! auth()->user()->can('view_own_sale')) {

            $users = DB::table('users')->select('id', 'prefix', 'name', 'last_name', 'phone')->get();
        }

        return view('finance.vouchers.journals.index', compact('users'));
    }

    public function show($id)
    {
        if (! auth()->user()->can('journals_index')) {

            abort(403, 'Access Forbidden.');
        }

        $journal = Journal::with(
            [
                'entries',
                'entries.paymentMethod',
                'entries.assignedUser:id,prefix,name,last_name,phone',
                'entries.account:id,name,account_number',
                'entries.voucherEntryCostCentres',
                'entries.voucherEntryCostCentres.costCentre:id,name',
                'createdBy:id,prefix,name,last_name,phone',
            ]
        )->where('id', $id)->first();

        return view('finance.vouchers.journals.ajax_view.show', compact('journal'));
    }

    public function create()
    {
        if (! auth()->user()->can('journals_add')) {

            abort(403, 'Access Forbidden.');
        }

        $users = '';
        if (! auth()->user()->can('view_own_sale')) {

            $users = DB::table('users')->select('id', 'prefix', 'name', 'last_name', 'phone')->get();
        }

        $paymentMethods = DB::table('payment_methods')->select('id', 'name')->get();

        return view('finance.vouchers.journals.create', compact('users', 'paymentMethods'));
    }

    public function store(Request $request, CodeGenerationServiceInterface $voucherGenerator)
    {
        if (! auth()->user()->can('journals_add')) {

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

            $addJournal = $this->journalUtil->addJournal(date: $request->date, remarks: $request->remarks, debitTotal: $request->debit_total, creditTotal: $request->credit_total, isTransactionDetails: $request->is_transaction_details, maintainCostCentre: $request->maintain_cost_centre, voucherGenerator: $voucherGenerator);

            // Add Day Book entry for Journal
            $this->dayBookUtil->addDayBook(voucherTypeId: 12, date: $request->date, accountId: $request->account_ids[0], transId: $addJournal->id, amount: $request->debit_amounts[0], amountType: 'debit');

            $cashBankAccountId = $this->journalEntryUtil->getCashBankAccountId($request);

            $index = 0;
            foreach ($request->account_ids as $account_id) {

                $amountType = $request->amount_types[$index] == 'Dr' || $request->amount_types[$index] == 'dr' ? 'dr' : 'cr';
                $ledgerAmountType = $request->amount_types[$index] == 'Dr' || $request->amount_types[$index] == 'dr' ? 'debit' : 'credit';
                $amount = $request->amount_types[$index] == 'Dr' || $request->amount_types[$index] == 'dr' ? $request->debit_amounts[$index] : $request->credit_amounts[$index];

                // Add Journal Entry
                $addJournalEntry = $this->journalEntryUtil->addJournalEntry(journalId: $addJournal->id, accountId: $account_id, paymentMethodId: $request->payment_method_ids[$index], amountType: $amountType, amount: $amount, userId: $request->user_ids[$index], transactionNo: $request->transaction_nos[$index], chequeNo: $request->cheque_nos[$index], chequeSerialNo: $request->cheque_serial_nos[$index], chequeIssueDate: $request->cheque_issue_dates[$index], remarkableNote: $request->remarkable_notes[$index]);

                if ($request->maintain_cost_centre == 1) {

                    $journalRowIndexNo = $request->indexes[$index];
                    if (isset($request->cost_centre_ids[$journalRowIndexNo])) {

                        $this->voucherEntryCostCentreUtil->addVoucherEntryCostCentres($addJournalEntry->id, 'journal', $request, $journalRowIndexNo);
                    }
                }

                $cashBankAcId = $addJournalEntry->is_cash_bank_ac == 0 ? $cashBankAccountId : null;

                //Add Account Ledger Entry
                $this->accountLedgerUtil->addAccountLedger(voucher_type_id: 13, date: $request->date, account_id: $account_id, trans_id: $addJournalEntry->id, amount: $amount, amount_type: $ledgerAmountType, user_id: $request->user_ids[$index], cash_bank_account_id: $cashBankAcId);

                $index++;
            }

            DB::commit();
        } catch (Exception $e) {

            DB::rollBack();
        }

        return response()->json('Journal created successfully');
    }

    public function edit($id)
    {
        if (! auth()->user()->can('journals_edit')) {

            abort(403, 'Access Forbidden.');
        }

        $users = '';
        if (! auth()->user()->can('view_own_sale')) {

            $users = DB::table('users')->select('id', 'prefix', 'name', 'last_name', 'phone')->get();
        }

        $paymentMethods = DB::table('payment_methods')->select('id', 'name')->get();

        $accountUtil = $this->accountUtil;
        $journal = Journal::with(
            [
                'entries',
                'entries.assignedUser:id,prefix,name,last_name,phone',
                'entries.account:id,name,account_number,account_group_id',
                'entries.account.group:id,main_group_number',
                'entries.voucherEntryCostCentres',
                'entries.voucherEntryCostCentres.costCentre:id,name',
            ]
        )->where('id', $id)->first();

        $myArray = [];
        $index = 0;
        foreach ($journal->entries as $entry) {

            if (count($entry->voucherEntryCostCentres) > 0) {

                foreach ($entry->voucherEntryCostCentres as $voucherEntryCostCentre) {

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

        return view('finance.vouchers.journals.edit', compact('journal', 'accountUtil', 'users', 'paymentMethods', 'costCentreArr', 'totalEntries'));
    }

    public function update(Request $request, $id)
    {
        if (! auth()->user()->can('journals_edit')) {

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

            $updateJournal = $this->journalUtil->updateJournal(id: $id, date: $request->date, remarks: $request->remarks, debitTotal: $request->debit_total, creditTotal: $request->credit_total, isTransactionDetails: $request->is_transaction_details, maintainCostCentre: $request->maintain_cost_centre);

            // Update Day Book entry for Journal
            $this->dayBookUtil->updateDayBook(voucherTypeId: 12, date: $request->date, accountId: $request->account_ids[0], transId: $updateJournal->id, amount: $request->debit_amounts[0], amountType: 'debit');

            $cashBankAccountId = $this->journalEntryUtil->getCashBankAccountId($request);

            // Prepare unused deletable JournalEntries
            $this->journalEntryUtil->prepareUnusedDeletableJournalEntries($updateJournal->entries);

            $index = 0;
            foreach ($request->account_ids as $account_id) {

                $amountType = $request->amount_types[$index] == 'Dr' || $request->amount_types[$index] == 'dr' ? 'dr' : 'cr';
                $ledgerAmountType = $request->amount_types[$index] == 'Dr' || $request->amount_types[$index] == 'dr' ? 'debit' : 'credit';
                $amount = $request->amount_types[$index] == 'Dr' || $request->amount_types[$index] == 'dr' ? $request->debit_amounts[$index] : $request->credit_amounts[$index];

                // Update Journal Entry
                $updateJournalEntry = $this->journalEntryUtil->updateJournalEntry(journalId: $updateJournal->id, journalEntryId: $request->journal_entry_ids[$index], accountId: $account_id, paymentMethodId: $request->payment_method_ids[$index], amountType: $amountType, amount: $amount, userId: $request->user_ids[$index], transactionNo: $request->transaction_nos[$index], chequeNo: $request->cheque_nos[$index], chequeSerialNo: $request->cheque_serial_nos[$index], chequeIssueDate: $request->cheque_issue_dates[$index], remarkableNote: $request->remarkable_notes[$index]);

                $this->voucherEntryCostCentreUtil->updateVoucherEntryCostCentres($updateJournalEntry->id, 'journal', $request, $index);

                $cashBankAcId = $updateJournalEntry->is_cash_bank_ac == 0 ? $cashBankAccountId : null;

                //Update Ledger Entry
                $this->accountLedgerUtil->updateAccountLedger(voucher_type_id: 13, date: $request->date, account_id: $account_id, trans_id: $updateJournalEntry->id, amount: $amount, amount_type: $ledgerAmountType, new_user_id: $request->user_ids[$index], cash_bank_account_id: $cashBankAcId);

                $index++;
            }

            // Delete unused JournalEntries
            $this->journalEntryUtil->deleteUnusedJournalEntries($updateJournal->id);

            DB::commit();
        } catch (Exception $e) {

            DB::rollBack();
        }

        return response()->json('Journal updated successfully');
    }

    public function delete($id)
    {
        if (! auth()->user()->can('journals_delete')) {

            abort(403, 'Access Forbidden.');
        }

        try {

            DB::beginTransaction();

            $this->journalUtil->deleteJournal($id);

            DB::commit();
        } catch (Exception $e) {

            DB::rollBack();
        }

        DB::statement('ALTER TABLE journals AUTO_INCREMENT = 1');

        return response()->json('Journal is deleted successfully');
    }

    public function userWiseCustomerClosingBalance(Request $request, $account_id)
    {
        return $this->accountUtil->accountClosingBalance($account_id, $request->user_id);
    }

    public function accountClosingBalance(Request $request, $account_id)
    {
        return $this->accountUtil->accountClosingBalance($account_id, $request->user_id, $request->from_date, $request->to_date);
    }

    public function costCentrePrepare(Request $request)
    {
        if ($request->cost_allocation_amount != $request->total_cost_centre_amount) {

            return response()->json(['errorMsg' => 'Allocation amount must not be greater then original amount']);
        }
        $myArray = [];

        if (isset($request->cost_centre_ids[$request->cost_allocation_account_index])) {

            $index = 0;
            foreach ($request->cost_centre_ids[$request->cost_allocation_account_index] as $key => $cost_centre_id) {

                if (isset($myArray[$request->cost_allocation_account_index])) {

                    array_push($myArray[$request->cost_allocation_account_index], [
                        'cost_centre_id' => $cost_centre_id,
                        'cost_centre_name' => $request->default_cost_centre_names[$request->cost_allocation_account_index][$index],
                        'cost_centre_amount' => $request->cost_centre_amounts[$request->cost_allocation_account_index][$index],
                    ]);
                } else {

                    $myArray[$request->cost_allocation_account_index][] = [
                        'cost_centre_id' => $cost_centre_id,
                        'cost_centre_name' => $request->default_cost_centre_names[$request->cost_allocation_account_index][$index],
                        'cost_centre_amount' => $request->cost_centre_amounts[$request->cost_allocation_account_index][$index],
                    ];
                }

                $index++;
            }

            return json_encode($myArray);
        } else {

            return 'Data not found';
        }
    }
}
