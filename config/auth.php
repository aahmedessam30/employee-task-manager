<?php

return [
    'guards'    => [
        'web' => [
            'driver'   => 'session',
            'provider' => 'users',
        ],
        'api' => [
            'driver'   => 'token',
            'provider' => 'users',
        ],
    ],
    'providers' => [
        'users' => [
            'model' => App\Models\User::class,
        ],
    ],
];
