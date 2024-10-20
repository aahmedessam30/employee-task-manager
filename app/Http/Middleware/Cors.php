<?php

namespace App\Http\Middleware;

use Core\Http\Middleware\Middleware;

class Cors implements Middleware
{
    public function handle($request, $next)
    {
        $response = $next($request);

        $response->setHeaders([
            'Access-Control-Allow-Origin'  => '*',
            'Access-Control-Allow-Methods' => 'GET, POST, PUT, DELETE, OPTIONS',
            'Access-Control-Allow-Headers' => 'Content-Type, Authorization',
        ]);

        return $response;
    }
}
