<?php

return [
    'global' => [
        \App\Http\Middleware\TrustHosts::class,
//        \App\Http\Middleware\Cors::class,
    ],
    'api'    => [],
    'web'    => [
        \App\Http\Middleware\StartSession::class,
        \App\Http\Middleware\VerifyCsrfToken::class,
    ],
];
