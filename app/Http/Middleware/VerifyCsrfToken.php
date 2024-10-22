<?php

namespace App\Http\Middleware;

use Core\Http\Middleware\Middleware;

class VerifyCsrfToken implements Middleware
{
    /**
     * @throws \Exception
     */
    public function handle($request, $next)
    {
        if (in_array($request->method(), ['HEAD', 'GET', 'OPTIONS'])) {
            return $next($request);
        }

        $csrfToken = $request->input('_token') ?? $request->header('X-CSRF-TOKEN');

        if (!$csrfToken || !is_string($csrfToken)) {
            throw new \Exception('CSRF token not found', 403);
        }

        if (!hash_equals(trim($csrfToken, ' '), trim(session()->csrfToken(), ' '))) {
            throw new \Exception('CSRF token mismatch', 403);
        }

        return $next($request);
    }
}
