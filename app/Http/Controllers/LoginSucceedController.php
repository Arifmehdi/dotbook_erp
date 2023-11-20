<?php

namespace App\Http\Controllers;

use App\Mail\LoginSucceedMail;
use Illuminate\Support\Facades\Mail;

class LoginSucceedController extends Controller
{
    public function sendEmail()
    {
        $myData = [
            'ip' => '127.01.02',
            'browser' => 'Firefox',
            'state' => 'Dhaka',
        ];

        Mail::to('john@gmail.com')->send(new LoginSucceedMail($myData));
    }
}
