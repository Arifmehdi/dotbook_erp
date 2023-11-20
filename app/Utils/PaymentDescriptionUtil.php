<?php

namespace App\Utils;

use App\Models\PaymentDescription;
use Illuminate\Support\Facades\DB;

class PaymentDescriptionUtil
{
    public function addPaymentDescription(
        $paymentId,
        $accountId,
        $paymentMethodId,
        $amountType,
        $amount,
        $userId = null,
        $transactionNo = null,
        $chequeNo = null,
        $chequeSerialNo = null,
        $chequeIssueDate = null,
    ) {
        $accountGroup = DB::table('accounts')->where('accounts.id', $accountId)
            ->leftJoin('account_groups', 'accounts.account_group_id', 'account_groups.id')
            ->select('account_groups.sub_sub_group_number')->first();

        $addPaymentDescription = new PaymentDescription();
        $addPaymentDescription->payment_id = $paymentId;
        $addPaymentDescription->account_id = $accountId;
        $addPaymentDescription->payment_method_id = $paymentMethodId;
        $addPaymentDescription->amount_type = $amountType;
        $addPaymentDescription->amount = $amount;
        $addPaymentDescription->transaction_no = $transactionNo;
        $addPaymentDescription->cheque_no = $chequeNo;
        $addPaymentDescription->cheque_serial_no = $chequeSerialNo;
        $addPaymentDescription->cheque_issue_date = $chequeIssueDate;
        $addPaymentDescription->user_id = $userId;
        $addPaymentDescription->save();

        if ($accountGroup->sub_sub_group_number == 1 || $accountGroup->sub_sub_group_number == 2 || $accountGroup->sub_sub_group_number == 11) {

            $addPaymentDescription->is_cash_bank_ac = 1;
        } else {

            $addPaymentDescription->is_cash_bank_ac = 0;
        }

        return $addPaymentDescription;
    }

    public function updatePaymentDescription(
        $paymentId,
        $paymentDescriptionId,
        $accountId,
        $paymentMethodId,
        $amountType,
        $amount,
        $userId,
        $transactionNo,
        $chequeNo,
        $chequeSerialNo,
        $chequeIssueDate
    ) {
        $paymentDescription = PaymentDescription::where('id', $paymentDescriptionId)->where('payment_id', $paymentId)->first();
        $addOrUpdatePaymentDescription = '';

        if ($paymentDescription) {

            $addOrUpdatePaymentDescription = $paymentDescription;
        } else {

            $addOrUpdatePaymentDescription = new PaymentDescription();
        }

        $accountGroup = DB::table('accounts')->where('accounts.id', $accountId)
            ->leftJoin('account_groups', 'accounts.account_group_id', 'account_groups.id')
            ->select('account_groups.sub_sub_group_number')->first();

        $addOrUpdatePaymentDescription->payment_id = $paymentId;
        $addOrUpdatePaymentDescription->account_id = $accountId;
        $addOrUpdatePaymentDescription->payment_method_id = $paymentMethodId;
        $addOrUpdatePaymentDescription->amount_type = $amountType;
        $addOrUpdatePaymentDescription->amount = $amount;
        $addOrUpdatePaymentDescription->transaction_no = $transactionNo;
        $addOrUpdatePaymentDescription->cheque_no = $chequeNo;
        $addOrUpdatePaymentDescription->cheque_serial_no = $chequeSerialNo;
        $addOrUpdatePaymentDescription->cheque_issue_date = $chequeIssueDate;
        $addOrUpdatePaymentDescription->cheque_issue_date = $chequeIssueDate;
        $addOrUpdatePaymentDescription->user_id = $userId;
        $addOrUpdatePaymentDescription->is_delete_in_update = 0;
        $addOrUpdatePaymentDescription->save();

        if ($accountGroup->sub_sub_group_number == 1 || $accountGroup->sub_sub_group_number == 2 || $accountGroup->sub_sub_group_number == 11) {

            $addOrUpdatePaymentDescription->is_cash_bank_ac = 1;
        } else {

            $addOrUpdatePaymentDescription->is_cash_bank_ac = 0;
        }

        return $addOrUpdatePaymentDescription;
    }

    public function prepareUnusedDeletablePaymentDescriptions($descriptions)
    {
        foreach ($descriptions as $description) {

            $description->is_delete_in_update = 1;
            $description->save();
        }
    }

    public function deleteUnusedPaymentDescriptions($paymentId)
    {
        $deletableDescriptions = PaymentDescription::where('payment_id', $paymentId)->where('is_delete_in_update', 1)->get();

        foreach ($deletableDescriptions as $deletableDescription) {

            $deletableDescription->delete();
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
