<?php

namespace App\Utils;

use App\Models\ExpenseDescription;
use Illuminate\Support\Facades\DB;

class ExpenseDescriptionUtil
{
    public function addExpenseDescription($expenseId, $accountId, $paymentMethodId, $amountType, $amount, $transactionNo, $chequeNo, $chequeSerialNo, $chequeIssueDate)
    {
        $accountGroup = DB::table('accounts')->where('accounts.id', $accountId)
            ->leftJoin('account_groups', 'accounts.account_group_id', 'account_groups.id')
            ->select('account_groups.sub_sub_group_number')->first();

        $addExpenseDescription = new ExpenseDescription();
        $addExpenseDescription->expense_id = $expenseId;
        $addExpenseDescription->account_id = $accountId;
        $addExpenseDescription->payment_method_id = $paymentMethodId;
        $addExpenseDescription->transaction_no = $transactionNo;
        $addExpenseDescription->cheque_no = $chequeNo;
        $addExpenseDescription->cheque_serial_no = $chequeSerialNo;
        $addExpenseDescription->cheque_issue_date = $chequeIssueDate;
        $addExpenseDescription->amount_type = $amountType;
        $addExpenseDescription->amount = $amount;
        $addExpenseDescription->save();

        if ($accountGroup->sub_sub_group_number == 1 || $accountGroup->sub_sub_group_number == 2 || $accountGroup->sub_sub_group_number == 11) {

            $addExpenseDescription->is_cash_bank_ac = 1;
        } else {

            $addExpenseDescription->is_cash_bank_ac = 0;
        }

        return $addExpenseDescription;
    }

    public function updateExpenseDescription(
        $expenseId,
        $expenseDescriptionId,
        $accountId,
        $paymentMethodId,
        $amountType,
        $amount,
        $transactionNo,
        $chequeNo,
        $chequeSerialNo,
        $chequeIssueDate,
    ) {

        $expenseDescription = ExpenseDescription::where('id', $expenseDescriptionId)->where('expense_id', $expenseId)->first();
        $addOrUpdateExpenseDescription = '';

        if ($expenseDescription) {

            $addOrUpdateExpenseDescription = $expenseDescription;
        } else {

            $addOrUpdateExpenseDescription = new ExpenseDescription();
        }

        $accountGroup = DB::table('accounts')->where('accounts.id', $accountId)
            ->leftJoin('account_groups', 'accounts.account_group_id', 'account_groups.id')
            ->select('account_groups.sub_sub_group_number')->first();

        $addOrUpdateExpenseDescription->expense_id = $expenseId;
        $addOrUpdateExpenseDescription->account_id = $accountId;
        $addOrUpdateExpenseDescription->payment_method_id = $paymentMethodId;
        $addOrUpdateExpenseDescription->amount_type = $amountType;
        $addOrUpdateExpenseDescription->amount = $amount;
        $addOrUpdateExpenseDescription->transaction_no = $transactionNo;
        $addOrUpdateExpenseDescription->cheque_no = $chequeNo;
        $addOrUpdateExpenseDescription->cheque_serial_no = $chequeSerialNo;
        $addOrUpdateExpenseDescription->cheque_issue_date = $chequeIssueDate;
        $addOrUpdateExpenseDescription->is_delete_in_update = 0;
        $addOrUpdateExpenseDescription->save();

        if ($accountGroup->sub_sub_group_number == 1 || $accountGroup->sub_sub_group_number == 2 || $accountGroup->sub_sub_group_number == 11) {

            $addOrUpdateExpenseDescription->is_cash_bank_ac = 1;
        } else {

            $addOrUpdateExpenseDescription->is_cash_bank_ac = 0;
        }

        return $addOrUpdateExpenseDescription;
    }

    public function prepareUnusedDeletableExpenseDescriptions($descriptions)
    {
        foreach ($descriptions as $description) {

            $description->is_delete_in_update = 1;
            $description->save();
        }
    }

    public function deleteUnusedExpenseDescriptions($expenseId)
    {
        $deleteAbleExDescriptions = ExpenseDescription::where('expense_id', $expenseId)
            ->where('is_delete_in_update', 1)->get();

        foreach ($deleteAbleExDescriptions as $exDescription) {

            $exDescription->delete();
        }
    }

    public function getCashBankAccountId($request)
    {
        $cashBankAccountId = '';
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
