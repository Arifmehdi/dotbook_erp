<?php

namespace App\Utils;

use App\Interface\SmsServiceInterface;

class SmsUtil
{
    public function __construct(
        private SmsServiceInterface $smsService
    ) {
    }

    public function sendSaleSms($sale)
    {
        $phone = $sale->customer->phone;
        if (isset($phone)) {
            $message = 'প্রিয় '.$sale->customer->name.', আপনার ক্রয়ের পরিমান: '.$sale->total_payable_amount;
            $message .= ', মোট পরিশোধ: '.$sale->paid.', মোট বাকি: '.$sale->paid.', ক্রয় ইনভয়েসঃ '.$sale->invoice_id;
            $message .= ', তারিখঃ '.$sale->date;
            $this->smsService->send($message, $phone);
        }
    }
}
