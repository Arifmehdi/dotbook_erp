<?php

namespace App\Utils;

use App\Models\JournalEntry;
use Illuminate\Support\Facades\DB;

class JournalEntryUtil
{
    public function addJournalEntry(
        $journalId,
        $accountId,
        $paymentMethodId,
        $amountType,
        $amount,
        $userId,
        $transactionNo,
        $chequeNo,
        $chequeSerialNo,
        $chequeIssueDate,
        $remarkableNote
    ) {

        $accountGroup = DB::table('accounts')->where('accounts.id', $accountId)
            ->leftJoin('account_groups', 'accounts.account_group_id', 'account_groups.id')
            ->select('account_groups.sub_sub_group_number')->first();

        $addJournalEntry = new JournalEntry();
        $addJournalEntry->journal_id = $journalId;
        $addJournalEntry->account_id = $accountId;
        $addJournalEntry->payment_method_id = $paymentMethodId;
        $addJournalEntry->amount_type = $amountType;
        $addJournalEntry->amount = $amount;
        $addJournalEntry->transaction_no = $transactionNo;
        $addJournalEntry->cheque_no = $chequeNo;
        $addJournalEntry->cheque_serial_no = $chequeSerialNo;
        $addJournalEntry->cheque_issue_date = $chequeIssueDate;
        $addJournalEntry->remarkable_note = $remarkableNote;
        $addJournalEntry->user_id = $userId;
        $addJournalEntry->save();

        if ($accountGroup->sub_sub_group_number == 1 || $accountGroup->sub_sub_group_number == 2 || $accountGroup->sub_sub_group_number == 11) {

            $addJournalEntry->is_cash_bank_ac = 1;
        } else {

            $addJournalEntry->is_cash_bank_ac = 0;
        }

        return $addJournalEntry;
    }

    public function updateJournalEntry(
        $journalId,
        $journalEntryId,
        $accountId,
        $paymentMethodId,
        $amountType,
        $amount,
        $userId,
        $transactionNo,
        $chequeNo,
        $chequeSerialNo,
        $chequeIssueDate,
        $remarkableNote
    ) {

        $journalEntry = JournalEntry::where('id', $journalEntryId)->where('journal_id', $journalId)->first();
        $addOrUpdateJournalEntry = '';

        if ($journalEntry) {

            $addOrUpdateJournalEntry = $journalEntry;
        } else {

            $addOrUpdateJournalEntry = new JournalEntry();
        }

        $accountGroup = DB::table('accounts')->where('accounts.id', $accountId)
            ->leftJoin('account_groups', 'accounts.account_group_id', 'account_groups.id')
            ->select('account_groups.sub_sub_group_number')->first();

        $addOrUpdateJournalEntry->journal_id = $journalId;
        $addOrUpdateJournalEntry->account_id = $accountId;
        $addOrUpdateJournalEntry->user_id = $userId;
        $addOrUpdateJournalEntry->payment_method_id = $paymentMethodId;
        $addOrUpdateJournalEntry->amount_type = $amountType;
        $addOrUpdateJournalEntry->amount = $amount;
        $addOrUpdateJournalEntry->transaction_no = $transactionNo;
        $addOrUpdateJournalEntry->cheque_no = $chequeNo;
        $addOrUpdateJournalEntry->cheque_serial_no = $chequeSerialNo;
        $addOrUpdateJournalEntry->cheque_issue_date = $chequeIssueDate;
        $addOrUpdateJournalEntry->remarkable_note = $remarkableNote;
        $addOrUpdateJournalEntry->is_delete_in_update = 0;
        $addOrUpdateJournalEntry->save();

        if ($accountGroup->sub_sub_group_number == 1 || $accountGroup->sub_sub_group_number == 2 || $accountGroup->sub_sub_group_number == 11) {

            $addOrUpdateJournalEntry->is_cash_bank_ac = 1;
        } else {

            $addOrUpdateJournalEntry->is_cash_bank_ac = 0;
        }

        return $addOrUpdateJournalEntry;
    }

    public function prepareUnusedDeletableJournalEntries($entries)
    {
        foreach ($entries as $entry) {

            $entry->is_delete_in_update = 1;
            $entry->save();
        }
    }

    public function deleteUnusedJournalEntries($journalId)
    {
        $deletableJournalEntries = JournalEntry::where('journal_id', $journalId)->where('is_delete_in_update', 1)->get();

        foreach ($deletableJournalEntries as $deletableJournalEntry) {

            $deletableJournalEntry->delete();
        }
    }

    public function getCashBankAccountId($request)
    {
        $cashBankAccountId = null;
        foreach ($request->account_ids as $accountId) {

            $account = DB::table('accounts')->where('accounts.id', $accountId)
                ->leftJoin('account_groups', 'accounts.account_group_id', 'account_groups.id')
                ->select('accounts.id', 'account_groups.sub_sub_group_number')->first();

            if ($account->sub_sub_group_number == 1 || $account->sub_sub_group_number == 2 || $account->sub_sub_group_number == 11) {

                if (! isset($cashBankAccountId)) {

                    $cashBankAccountId = $account->id;
                }
            }

            if ($cashBankAccountId != null) {

                break;
            }
        }

        return $cashBankAccountId;
    }
}
