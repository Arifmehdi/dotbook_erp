<?php

return [
    'print_on_sale' => (bool) env('PRINT_SD_SALE', true),
    'print_on_purchase' => (bool) env('PRINT_SD_PURCHASE', true),
    'print_on_others' => (bool) env('PRINT_SD_OTHERS', true),
    'print_on_payment' => (bool) env('PRINT_SD_PAYMENT', true),
    'scale_api' => env('SCALE_API', 'http://localhost:8888'),
];
