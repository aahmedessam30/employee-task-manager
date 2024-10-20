<?php

namespace Core\Routing;

use Exception;
use Core\Exceptions\ExceptionHandler;

class Router
{
    protected array $routes = [];
    protected array $currentGroup = [];

    /**
     * @throws Exception
     */
    public function __construct()
    {
        Route::setRouter($this);

        $this->loadRoutes();
    }

    /**
     * @throws Exception
     */
    protected function loadRoutes($dir = null): void
    {
        $routes = scandir(base_path($dir ? 'routes/' . $dir : 'routes'));

        foreach ($routes as $route) {
            if ($route === '.' || $route === '..') {
                continue;
            }

            if (!str_contains($route, '.php')) {
                continue;
            }

            if (!is_file(base_path("routes/$route")) && !is_dir(base_path("routes/$route"))) {
                throw new Exception('Route file not found.');
            }

            $globalMiddlewares = config('middleware.global' ?? []) ?? [];
            $webMiddlewares    = array_merge($globalMiddlewares, config('middleware.web' ?? []) ?? []);
            $apiMiddlewares    = array_merge($globalMiddlewares, config('middleware.api' ?? []) ?? []);

            if (is_file(base_path("routes/$route"))) {
                if ($route === 'api.php') {
                    $this->group([
                        'prefix'     => 'api',
                        'namespace'  => 'Api',
                        'as'         => 'api.',
                        'middleware' => $apiMiddlewares,
                    ], function ($router) use ($route) {
                        require_once base_path('routes/' . $route);
                    });

                    continue;
                }

                $this->group(['middleware' => $webMiddlewares], function ($router) use ($route) {
                    require_once base_path("routes/$route");
                });
            }

            if (is_dir(base_path("routes/$route"))) {
                $this->loadRoutes($route);
            }
        }
    }

    public function addRoute($methods, $uri, $action)
    {
        $route = $this->createRoute($methods, $uri, $action);
        $this->routes[] = $route;
        return $route;
    }

    protected function createRoute($methods, $uri, $action): Route
    {
        $route = new Route($methods, $uri, $action);

        if (!empty($this->currentGroup)) {
            $this->mergeGroupAttributesIntoRoute($route);
        }

        return $route;
    }

    protected function mergeGroupAttributesIntoRoute($route): void
    {
        if (isset($this->currentGroup['middleware'])) {
            $route->middleware($this->currentGroup['middleware']);
        }

        if (isset($this->currentGroup['prefix'])) {
            $route->setUri(trim($this->currentGroup['prefix'], '/') . '/' . trim($route->uri, '/'));
        }

        if (isset($this->currentGroup['namespace'])) {
            $route->namespace($this->currentGroup['namespace']);
        }

        if (isset($this->currentGroup['name'])) {
            $route->name($this->currentGroup['name'] . '.');
        }

        if (isset($this->currentGroup['as'])) {
            $route->as(rtrim($this->currentGroup['as'], '.') . '.' . $route->name);
        }
    }

    public function group(array $attributes, \Closure $callback): void
    {
        $previousGroup = $this->currentGroup;
        $this->currentGroup = $this->mergeGroups($previousGroup, $attributes);

        $callback($this);

        $this->currentGroup = $previousGroup;
    }

    protected function mergeGroups($previous, $new): array
    {
        $merged = array_merge_recursive($previous, $new);

        if (isset($merged['middleware'])) {
            $merged['middleware'] = array_unique($merged['middleware']);
        }

        if (isset($previous['prefix']) && isset($new['prefix'])) {
            $merged['prefix'] = trim($previous['prefix'], '/') . '/' . trim($new['prefix'], '/');
        }

        return $merged;
    }

    public function dispatch($request): void
    {
        try {
            foreach ($this->routes as $route) {
                if ($route->matches($request)) {
                    $route->run($request);
                    return;
                }
            }
            throw new \Exception("Route not found", 404);
        } catch (Exception $e) {
            ExceptionHandler::handle($e);
        }
    }

    public function getRoutes()
    {
        return $this->routes;
    }
}
