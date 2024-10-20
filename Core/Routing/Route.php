<?php

namespace Core\Routing;

use Core\Http\FormRequest;
use Core\Http\Middleware\MiddlewareStack;
use Core\Http\Request;

class Route
{
    protected static $router;

    public array $methods;
    public $uri;
    public $action;
    public $name;
    public $as;
    public $namespace;
    public array $middleware = [];
    public array $where = [];

    public function __construct($methods, $uri, $action)
    {
        $this->methods = (array)$methods;
        $this->uri     = $uri;
        $this->action  = $action;
    }

    public static function __callStatic($method, $arguments)
    {
        return static::$router->addRoute(strtoupper($method), ...$arguments);
    }

    public static function get($uri, $action)
    {
        return static::$router->addRoute(['GET', 'HEAD'], $uri, $action);
    }

    public static function post($uri, $action)
    {
        return static::$router->addRoute('POST', $uri, $action);
    }

    public static function put($uri, $action)
    {
        return static::$router->addRoute('PUT', $uri, $action);
    }

    public static function patch($uri, $action)
    {
        return static::$router->addRoute('PATCH', $uri, $action);
    }

    public static function delete($uri, $action)
    {
        return static::$router->addRoute('DELETE', $uri, $action);
    }

    public static function options($uri, $action)
    {
        return static::$router->addRoute('OPTIONS', $uri, $action);
    }

    public static function any($uri, $action)
    {
        return static::$router->addRoute(['GET', 'HEAD', 'POST', 'PUT', 'PATCH', 'DELETE', 'OPTIONS'], $uri, $action);
    }

    public static function match(array $methods, $uri, $action)
    {
        return static::$router->addRoute($methods, $uri, $action);
    }

    public static function group(array $attributes, \Closure $callback)
    {
        static::$router->group($attributes, $callback);
    }

    public static function setRouter(Router $router)
    {
        static::$router = $router;
    }

    public function name($name)
    {
        $this->name = $name;

        if ($this->as) {
            $this->name = $this->as . $this->name;
        }

        return $this;
    }

    public function middleware($middleware)
    {
        $this->middleware = array_merge($this->middleware, (array)$middleware);
        return $this;
    }

    public function namespace($namespace)
    {
        $this->namespace = "App\\Http\\Controllers\\$namespace";
        return $this;
    }

    public function prefix($prefix)
    {
        $this->uri = trim($prefix, '/') . '/' . trim($this->uri, '/');
        return $this;
    }

    public function as($as)
    {
        $this->as = $as;
        return $this;
    }

    public function where($name, $expression = null)
    {
        if (is_array($name)) {
            foreach ($name as $key => $value) {
                $this->where[$key] = $value;
            }
        } else {
            $this->where[$name] = $expression;
        }
        return $this;
    }

    public function setUri($uri)
    {
        $this->uri = $uri;
        return $this;
    }

    protected function getCompiledPattern(): string
    {
        $pattern = '/' . trim($this->uri, '/');
        $pattern = preg_replace('/\/{([a-zA-Z0-9_]+)\?}/', '(?:/(?P<$1>[^/]+))?', $pattern);
        $pattern = preg_replace('/{([a-zA-Z0-9_]+)}/', '(?P<$1>[^/]+)', $pattern);

        foreach ($this->where as $name => $expression) {
            $pattern = str_replace("(?P<$name>[^/]+)", "(?P<$name>$expression)", $pattern);
        }

        return "#^$pattern/?$#i";
    }

    public function matches($request)
    {
        if (!in_array($request->method(), $this->methods)) {
            return false;
        }

        $pattern = $this->getCompiledPattern();
        $path    = '/' . trim($request->getPathInfo(), '/');

        if (preg_match($pattern, $path, $matches)) {
            $parameters = $this->extractParameters($matches);
            $request->setRouteParameters($parameters);
            return true;
        }

        return false;
    }

    protected function extractParameters($matches): array
    {
        return array_filter($matches, fn ($key) => !is_numeric($key), ARRAY_FILTER_USE_KEY);
    }

    /**
     * @throws \Exception
     */
    public function run($request)
    {
        $middlewareStack = new MiddlewareStack();

        foreach ($this->middleware as $middleware) {
            $middlewareStack->push($middleware);
        }

        return $middlewareStack->handle($request, fn ($req) => $this->runAction($req));
    }

    /**
     * @throws \ReflectionException
     * @throws \Exception
     */
    protected function runAction($request)
    {
        $action = $this->action;

        if (is_string($action)) {
            [$controller, $method] = explode('@', $action);
            $controller            = $this->namespace ? "$this->namespace\\$controller" : "App\\Http\\Controllers\\$controller";
            $instance              = new $controller();
            $parameters            = $this->resolveClassMethodDependencies($instance, $method, $request);
            return $instance->$method(...$parameters);
        } elseif (is_callable($action)) {
            return $action(...$request->getRouteParameters());
        }

        throw new \Exception('Invalid route action.');
    }

    /**
     * @throws \ReflectionException
     * @throws \Exception
     */
    protected function resolveClassMethodDependencies($instance, $method, $request): array
    {
        $reflector    = new \ReflectionMethod($instance, $method);
        $parameters   = $reflector->getParameters();
        $dependencies = [];

        foreach ($parameters as $parameter) {
            $type = $parameter->getType();

            if ($type && !$type->isBuiltin()) {
                $className = $type->getName();

                if (is_subclass_of($className, FormRequest::class)) {
                    $formRequest = new $className($request);
                    $formRequest->validateResolved();
                    $dependencies[] = $formRequest;
                } elseif ($className === Request::class) {
                    $dependencies[] = $request;
                } else {
                    $dependencies[] = new $className();
                }
            } else {
                $dependencies[] = $request->{$parameter->name};
            }
        }

        return $dependencies;
    }

    public static function getRoutes()
    {
        return static::$router->getRoutes();
    }
}
