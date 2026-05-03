<?php

return [
    'stateful' => explode(',', env('SANCTUM_STATEFUL_DOMAINS', 'localhost,127.0.0.1,127.0.0.1:3000,127.0.0.1:3001')),
    'middleware' => [
        'verify_csrf_token' => false,  // For API routes
    ],
    'guard' => 'web',
    'expiration' => null,
    'token_prefix' => env('SANCTUM_TOKEN_PREFIX', ''),
];