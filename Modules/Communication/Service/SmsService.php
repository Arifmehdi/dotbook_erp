<?php

namespace Modules\Communication\Service;

use App\Models\GeneralSetting;
use Illuminate\Support\Facades\Http;
use Modules\Communication\Interface\SmsServiceInterface;

/**
 * SpeedDigit Software Solution
 * SMS Service (Dynamic)
 * Completely customizable from GUI data
 * API 1: http://188.138.41.146:7788/sendtext?apikey=9052ffc3af735e96&secretkey=fc6d355e&callerID=8809612770480&toUser=01781077277&messageContent=MyMessageGoesHere
 * API 2: http://www.sms.bangohost.com/smsapi?api_key=(APIKEY)&type=text&contacts=(NUMBER)&senderid=(Approved Sender ID)&msg=(Message Content)
 * Example: http://www.sms.bangohost.com/smsapi?api_key=R6001497636b5b900483f9.42267793&type=text&contacts=8801781077277+8801911194724&senderid=SpeedDigit&msg=স্পিডডিজিট
 */
class SmsService implements SmsServiceInterface
{
    public function send(string $message, string $toUser)
    {
        $setting = GeneralSetting::sms();
        $url = trim($setting['final_url']);
        $isGET = strtolower(trim($setting['type'])) == 'get' ?? false;
        $requestBody = $setting['config'];
        $requestBody['contacts'] = $toUser;
        $requestBody['msg'] = $message;

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
        $numbers = \implode('+', $numbers);
        $order1 = $requestBodyFinal['api_key'] = $requestBody['api_key'];
        $order2 = $requestBodyFinal['type'] = $requestBody['type'];
        $order3 = $requestBodyFinal['contacts'] = $numbers;
        array_map(function ($key, $value) use (&$x) {
            $x .= "$key=$value&";
        }, array_keys($requestBodyFinal), $requestBodyFinal);

        $x .= 'senderid='.$requestBody['senderid'].'&';
        $x .= 'msg='.$message;
        $finalUrl = $url.'?'.$x;

        // dd($finalUrl);
        if ($isGET) {
            $response = Http::get($finalUrl);
        } else {
            $response = Http::post($finalUrl);
        }
    }
}
