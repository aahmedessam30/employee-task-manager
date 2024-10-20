<?php

namespace Core\Http\Middleware;

use Core\Http\Request;
use Core\Http\Response;

abstract class TrustHosts
{
    /**
     * Get the host patterns that should be trusted.
     *
     * @return array
     */
    abstract public function hosts();

    public function handle(Request $request, $next)
    {
        Request::setTrustedHosts(array_filter($this->hosts()));
        return $next($request);
    }

    protected function allSubdomainsOfApplicationUrl()
    {
        if ($host = parse_url(config('app.url'), PHP_URL_HOST)) {
            return '^(.+\.)?'.preg_quote($host).'$';
        }
    }
}
