<?php

return [
    'url' => env('SMS_URL', 'http://188.138.41.146:7788/sendtext?apikey=SMS_API_KEY&secretkey=SMS_SECRET_KEY&callerID=SMS_CALLER_ID&toUser=TO_USER&messageContent=MESSAGE_CONTENT'),
    'apiKey' => env('SMS_API_KEY', '9052ffc3af735e96'),
    'secretKey' => env('SMS_SECRET_KEY', 'fc6d355e'),
    'callerId' => env('SMS_CALLER_ID', '8809612770480'),
];
