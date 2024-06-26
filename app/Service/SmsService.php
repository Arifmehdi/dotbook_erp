<?php

namespace App\Service;

use App\Interface\SmsServiceInterface;
use App\Models\GeneralSetting;
use Illuminate\Support\Facades\Http;

/**
 * SpeedDigit Software Solution
 * SMS Service (Dynamic)
 * Completely customizable from GUI data
 * Current API: http://188.138.41.146:7788/sendtext?apikey=9052ffc3af735e96&secretkey=fc6d355e&callerID=8809612770480&toUser=01781077277&messageContent=MyMessageGoesHere
 */
class SmsService implements SmsServiceInterface
{
    public function send(string $message, string $toUser)
    {
        $setting = GeneralSetting::sms();
        $url = trim($setting['final_url']);
        $isGET = strtolower(trim($setting['type'])) == 'get' ?? false;

        $requestBody = $setting['config'];
        $requestBody['toUser'] = $toUser;
        $requestBody['messageContent'] = $message;

        if ($isGET) {
            $response = Http::get($url, $requestBody);
        } else {
            $response = Http::post($url, $requestBody);
        }

        return $response;
    }

    public function sendMultiple(string $message, array $numbers)
    {
        $setting = GeneralSetting::sms();
        $url = trim($setting['final_url']);
        $isGET = strtolower(trim($setting['type'])) == 'get' ?? false;

        $requestBody = $setting['config'];

        foreach ($numbers as $toUser) {
            $requestBody['toUser'] = $toUser;
            $requestBody['messageContent'] = $message;
            if ($isGET) {
                $response = Http::get($url, $requestBody);
            } else {
                $response = Http::post($url, $requestBody);
            }
        }
    }
}
