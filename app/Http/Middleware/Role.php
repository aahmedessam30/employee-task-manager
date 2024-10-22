<?php

namespace App\Http\Middleware;

use Core\Http\Middleware\Middleware;

class Role implements Middleware
{
    public function handle($request, $next)
    {
        if (auth()->check() && in_array(auth()->user()->role, ['admin', 'employee'])) {
            return $next($request);
        }

        return redirect('/');
    }
}
