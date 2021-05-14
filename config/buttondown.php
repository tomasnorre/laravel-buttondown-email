<?php

return [
    'api' => [
        'key' => env('BUTTONDOWN_KEY'),
        'url' => env('BUTTONDOWN_URL', 'https://api.buttondown.email/v1'),
        'timeout' => env('BUTTONDOWN_TIMEOUT', 10),
        'retry' => [
            'times' => env('BUTTONDOWN_RETRY_TIMES', null),
            'milliseconds' => env('BUTTONDOWN_RETRY_MILLISECONDS', null),
        ],
    ]
];
