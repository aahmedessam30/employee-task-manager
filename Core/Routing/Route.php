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
    protected array $parameters = [];
    public static array $spoofedMethods = ['POST', 'PUT', 'PATCH', 'DELETE'];

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

    public static function resource($uri, $controller)
    {
        return static::$router->resource($uri, $controller);
    }

    public static function apiResource($uri, $controller)
    {
        return static::$router->apiResource($uri, $controller);
    }

    public static function view($uri, $view)
    {
        return static::$router->view($uri, $view);
    }

    public static function setRouter(Router $router): void
    {
        static::$router = $router;
    }

    public function name($name): static
    {
        $this->name = $name;

        if ($this->as) {
            $this->name = $this->as . $this->name;
        }

        return $this;
    }

    public function middleware($middleware): static
    {
        $this->middleware = array_merge($this->middleware, (array)$middleware);
        return $this;
    }

    public function namespace($namespace): static
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

    public function is($route)
    {
        $current = static::current();

        if (!$current) {
            return false;
        }

        return $current->getName() === $route;
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

        $method = $request->method();

        if (!in_array($method, $this->methods)) {
            if ($request->method() === 'POST' &&
                $request->input('_method') &&
                in_array(strtoupper($request->input('_method')), $this->methods)) {
                return $this->matchesUri($request);
            }
            return false;
        }

        if ($this->matchesUri($request)) {
            return true;
        }

        return false;
    }

    public function matchesUri($request)
    {
        if (str_starts_with(trim($request->getPathInfo(), '/'), 'public')) {
            return true;
        }

        $pattern = $this->getCompiledPattern();
        $path    = parse_url('/' . trim($request->getPathInfo(), '/'), PHP_URL_PATH);

        if (preg_match($pattern, $path, $matches)) {
            $parameters       = $this->extractParameters($matches);
            $this->parameters = $parameters;
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

    public function getName()
    {
        return $this->name;
    }

    public function uri($params = [])
    {
        $uri = $this->uri;

        foreach ($params as $key => $value) {
            $uri = str_replace("{{$key}}", $value, $uri);
        }

        return url($uri);
    }

    public static function current()
    {
        foreach (static::getRoutes() as $route) {
            if ($route->matchesUri(request())) {
                return $route;
            }
        }

        return null;
    }

    public function parameters()
    {
        return $this->parameters;
    }

    public function parameter($key, $default = null)
    {
        return $this->parameters[$key] ?? $default;
    }
}
