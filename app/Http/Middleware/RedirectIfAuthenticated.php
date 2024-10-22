<?php

namespace App\Http\Middleware;

use Core\Http\Middleware\Middleware;

class RedirectIfAuthenticated implements Middleware
{
    public function handle($request, $next)
    {
        if (!auth()->check()) {
            return redirect('/login');
        }

        return $next($request);
    }
}
