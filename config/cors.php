<?php

return [

    'paths' => ['api/*', 'sanctum/csrf-cookie'],

    'allowed_methods' => ['*'],

    'allowed_origins' => array_filter(array_map('trim', explode(',', env(
        'FRONTEND_URLS',
        env('FRONTEND_URL', 'http://8.222.195.9:5173')
    )))),

    'allowed_origins_patterns' => [],

    'allowed_headers' => ['*'],

    'exposed_headers' => ['Authorization'],

    'max_age' => 0,

    // If you use Sanctum cookies/session, keep true.
    'supports_credentials' => true,
];
