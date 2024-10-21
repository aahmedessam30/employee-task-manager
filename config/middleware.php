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
    'alias'  => [
        'auth'  => \App\Http\Middleware\Authenticate::class,
        'guest' => \App\Http\Middleware\RedirectIfAuthenticated::class,
        'role'  => \App\Http\Middleware\Role::class,
    ],
];
