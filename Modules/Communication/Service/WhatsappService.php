<?php

namespace Modules\Communication\Service;

use Modules\Communication\Interface\WhatsappServiceInterface;
use Twilio\Rest\Client;

/**
 * SpeedDigit Software Solution
 * Whatsapp Service (Dynamic)
 */
class WhatsappService implements WhatsappServiceInterface
{
    public function checkCountryCodeWithNumber($number)
    {
        if (strlen("${number}") == 11) {
            $number = '88'.$number;

            return $number;
        } else {
            $first_number = substr($number, 0, 3);
            if ($first_number == '880') {
                return $number;
            } elseif ($first_number == '+88') {
                return $trim_number = ltrim($number, '+');
            }
        }
    }

    public function send(string $message, string $toUser)
    {
        $toUser = $this->checkCountryCodeWithNumber($toUser);
        $sid = env('TWILIO_AUTH_SID');
        $token = env('TWILIO_AUTH_TOKEN');
        $wa_from = env('TWILIO_WHATSAPP_FROM');
        $twilio = new Client($sid, $token);
        // $recipient = "+8801911194724";
        $body = 'Hello, welcome to speeddigit.com';
        $message = $twilio->messages->create("whatsapp:$toUser", ['from' => "whatsapp:$wa_from", 'body' => $body]);
    }

    public function sendMultiple(string $message, array $numbers)
    {
        foreach ($numbers as $toUser) {
            $toUser = $this->checkCountryCodeWithNumber($toUser);
            $sid = env('TWILIO_AUTH_SID');
            $token = env('TWILIO_AUTH_TOKEN');
            $wa_from = env('TWILIO_WHATSAPP_FROM');
            $twilio = new Client($sid, $token);
            // $recipient = "+8801911194724";
            $body = 'Hello, welcome to speeddigit.com';
            $message = $twilio->messages->create("whatsapp:$toUser", ['from' => "whatsapp:$wa_from", 'body' => $body]);
        }
    }
}
