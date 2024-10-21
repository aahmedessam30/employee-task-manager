<?php

namespace Core\Http\Middleware;

use Core\Http\Request;
use Core\Http\Response;

class MiddlewareStack
{
    protected array $middlewares = [];

    public function push($middleware)
    {
        if (is_string($middleware)) {
            if (config('middleware.alias.' . $middleware)) {
                $middleware = config('middleware.alias.' . $middleware);
            }

            $middleware = new $middleware();
        }

        $this->middlewares[] = $middleware;
    }

    public function handle(Request $request, \Closure $destination)
    {
        $pipeline = array_reduce(
            array_reverse($this->middlewares),
            $this->carry(),
            $destination
        );

        return $pipeline($request);
    }

    protected function carry()
    {
        return function ($stack, $pipe) {
            return function ($request) use ($stack, $pipe) {
                $response = $pipe->handle($request, $stack);
                return $response instanceof Response ? $response : new Response($response);
            };
        };
    }
}
