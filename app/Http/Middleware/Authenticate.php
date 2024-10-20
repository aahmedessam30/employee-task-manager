<?php

namespace App\Http\Middleware;

use Core\Http\Middleware\Middleware;

class Authenticate implements Middleware
{
    public function handle($request, $next)
    {
        if (!$request->session()->has('user')) {
            redirect('/login');
        }

        return $next($request);
    }
}
