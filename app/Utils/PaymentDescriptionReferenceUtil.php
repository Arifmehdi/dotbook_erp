<?php

namespace App\Utils;

use App\Models\PaymentDescriptionReference;

class PaymentDescriptionReferenceUtil
{
    public function addPaymentDescriptionReferences(int $paymentDescriptionId, array $refIdColNames, array $refIds, array $amounts)
    {
        $index = 0;
        foreach ($refIds as $refId) {

            $addPaymentDescriptionRef = new PaymentDescriptionReference();
            $addPaymentDescriptionRef->payment_description_id = $paymentDescriptionId;
            $addPaymentDescriptionRef->{$refIdColNames[$index]} = $refId;
            $addPaymentDescriptionRef->amount = $amounts[$index];
            $addPaymentDescriptionRef->save();

            $index++;
        }
    }
}
