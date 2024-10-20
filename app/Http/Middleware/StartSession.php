<?php

namespace App\Http\Middleware;

use Core\Http\Middleware\Middleware;
use Core\Session\Session;

class StartSession implements Middleware
{
    public function handle($request, $next)
    {
        Session::start();

        return $next($request);
    }
}
