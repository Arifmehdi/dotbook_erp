<?php

return [
    'format' => 'A4',
    'display_mode' => 'fullpage',
    'font_path' => base_path('resources/fonts/'),
    'margin_top' => 5,
    'margin_right' => 10,
    'margin_left' => 10,
    // 'margin_bottom' => 30,
    'font_data' => [
        'nikosh' => [
            'R' => 'Nikosh.ttf', // regular font
            'B' => 'Nikosh.ttf', // optional: bold font
            'I' => 'Nikosh.ttf', // optional: italic font
            'BI' => 'Nikosh.ttf', // optional: bold-italic font
            'useOTL' => 0xFF,
            'useKashida' => 75,
        ],
        'solaimanlipi' => [
            'R' => 'SolaimanLipi.ttf', // regular font
            'B' => 'SolaimanLipi_Bold.ttf', // optional: bold font
            'I' => 'SolaimanLipi.ttf', // optional: italic font
            'BI' => 'SolaimanLipi.ttf', // optional: bold-italic font
            'useOTL' => 0xFF,
            'useKashida' => 75,
        ],
    ],
    'tempDir' => public_path('tmp'),
];
