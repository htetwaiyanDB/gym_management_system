<?php

return [
    // Apply CORS only to API routes
    'paths' => ['api/*'],

    'allowed_methods' => ['*'],

    // ✅ EXACT frontend origin (port matters)
    'allowed_origins' => [
        'http://8.222.195.9:5173',
        'http://localhost:5173',
        'http://127.0.0.1:5173',
        'https://unityfitnessmyanmar.online',
        'https://www.unityfitnessmyanmar.online',

    ],

    'allowed_origins_patterns' => [],

    // ✅ MUST allow Authorization for Bearer token
    'allowed_headers' => [
        'Content-Type',
        'Authorization',
        'X-Requested-With',
        'Accept',
        'Origin',
    ],

    'exposed_headers' => [],

    'max_age' => 0,

    // ❌ Bearer token → no cookies
    'supports_credentials' => false,
];
