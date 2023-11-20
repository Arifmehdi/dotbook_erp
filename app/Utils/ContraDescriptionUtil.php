<?php

namespace App\Utils;

use App\Models\ContraDescription;

class ContraDescriptionUtil
{
    public function addContraDescription(
        $contraId,
        $accountId,
        $paymentMethodId,
        $amountType,
        $amount,
        $transactionNo = null,
        $chequeNo = null,
        $chequeSerialNo = null,
        $chequeIssueDate = null,
    ) {
        $addContraDescription = new ContraDescription();
        $addContraDescription->contra_id = $contraId;
        $addContraDescription->account_id = $accountId;
        $addContraDescription->payment_method_id = $paymentMethodId;
        $addContraDescription->amount_type = $amountType;
        $addContraDescription->amount = $amount;
        $addContraDescription->transaction_no = $transactionNo;
        $addContraDescription->cheque_no = $chequeNo;
        $addContraDescription->cheque_serial_no = $chequeSerialNo;
        $addContraDescription->cheque_issue_date = $chequeIssueDate;
        $addContraDescription->cheque_issue_date = $chequeIssueDate;
        $addContraDescription->save();

        return $addContraDescription;
    }

    public function updateContraDescription($contraId, $contraDescriptionId, $accountId, $paymentMethodId, $amountType, $amount, $transactionNo, $chequeNo, $chequeSerialNo, $chequeIssueDate)
    {
        $contraDescription = ContraDescription::where('id', $contraDescriptionId)->where('contra_id', $contraId)->first();
        $addOrUpdateContraDescription = '';

        if ($contraDescription) {

            $addOrUpdateContraDescription = $contraDescription;
        } else {

            $addOrUpdateContraDescription = new ContraDescription();
        }

        $addOrUpdateContraDescription->contra_id = $contraId;
        $addOrUpdateContraDescription->account_id = $accountId;
        $addOrUpdateContraDescription->payment_method_id = $paymentMethodId;
        $addOrUpdateContraDescription->amount_type = $amountType;
        $addOrUpdateContraDescription->amount = $amount;
        $addOrUpdateContraDescription->transaction_no = $transactionNo;
        $addOrUpdateContraDescription->cheque_no = $chequeNo;
        $addOrUpdateContraDescription->cheque_serial_no = $chequeSerialNo;
        $addOrUpdateContraDescription->cheque_issue_date = $chequeIssueDate;
        $addOrUpdateContraDescription->cheque_issue_date = $chequeIssueDate;
        $addOrUpdateContraDescription->is_delete_in_update = 0;
        $addOrUpdateContraDescription->save();

        return $addOrUpdateContraDescription;
    }

    public function prepareUnusedDeletableContraDescriptions($descriptions)
    {
        foreach ($descriptions as $description) {

            $description->is_delete_in_update = 1;
            $description->save();
        }
    }

    public function deleteUnusedContraDescriptions($contraId)
    {
        $deletableDescriptions = ContraDescription::where('contra_id', $contraId)->where('is_delete_in_update', 1)->get();

        foreach ($deletableDescriptions as $deletableDescription) {

            $deletableDescription->delete();
        }
    }
}
