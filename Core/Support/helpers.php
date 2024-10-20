<?php

function app_name(): string
{
    return basename(base_path());
}

function base_path($path = ''): string
{
    $baseDir = str_replace('Core' . DIRECTORY_SEPARATOR . 'Support', '', __DIR__);
    $baseDir = trim($baseDir, DIRECTORY_SEPARATOR);

    return $baseDir . ($path ? DIRECTORY_SEPARATOR . trim($path, DIRECTORY_SEPARATOR) : $path);
}

function public_path($path = ''): string
{
    return base_path('public' . ($path ? DIRECTORY_SEPARATOR . $path : $path));
}

function config_path($path = ''): string
{
    return base_path('config' . ($path ? DIRECTORY_SEPARATOR . $path : $path));
}

function core_path($path = ''): string
{
    return base_path('Core' . ($path ? DIRECTORY_SEPARATOR . $path : $path));
}

function support_path($path = ''): string
{
    return base_path('Core/Support' . ($path ? DIRECTORY_SEPARATOR . $path : $path));
}

function app_path($path = ''): string
{
    return base_path('app' . ($path ? DIRECTORY_SEPARATOR . $path : $path));
}

function database_path($path = ''): string
{
    return base_path('database' . ($path ? DIRECTORY_SEPARATOR . $path : $path));
}

function migrations_path($path = ''): string
{
    return database_path('migrations' . ($path ? DIRECTORY_SEPARATOR . $path : $path));
}

function env($key, $default = null)
{
    return $_ENV[$key] ?? $default;
}

/**
 * @throws Exception
 */
function config($key, $default = null)
{
    $keys = explode('.', $key);
    $config = require config_path("$keys[0].php");

    if (!file_exists(config_path("$keys[0].php"))) {
        throw new Exception("Config file '$keys[0].php' not found.");
    }

    foreach (array_slice($keys, 1) as $key) {
        $config = $config[$key] ?? null;
    }

    return $config ?? $default;
}

function dd(...$vars): void
{
    foreach ($vars as $var) {
        echo '<pre>';
        var_dump($var);
        echo '</pre>';
        echo '<br>';
    }
    die();
}

function dump(...$vars): void
{
    foreach ($vars as $var) {
        echo '<pre>';
        var_dump($var);
        echo '</pre>';
        echo '<br>';
    }
}

function redirect($url): void
{
    response()->redirect($url);
    exit;
}

function back(): void
{
    response()->back();
    exit;
}

function response(): \Core\Http\Response
{
    return new \Core\Http\Response();
}

function request(): \Core\Http\Request
{
    return new \Core\Http\Request();
}

function str_after($subject, $search)
{
    if ($search === '') {
        return $subject;
    }

    $pos = strpos($subject, $search);

    return $pos !== false ? substr($subject, $pos + strlen($search)) : $subject;
}

/**
 * @throws Exception
 */
function route($name, $params = [])
{
    try {
        $routes = \Core\Routing\Route::getRoutes();

        foreach ($routes as $route) {
            if ($route->getName() === $name) {
                return $route->uri($params);
            }
        }

        throw new Exception("Route '$name' not found.");

    } catch (Exception $e) {
        echo $e->getMessage();
    }

    return '';
}

function pluck($array, $key)
{
    return array_map(function ($value) use ($key) {
        return is_object($value) ? $value->$key : $value[$key];
    }, $array);
}

function view($view, $data = [])
{
    $view = str_replace('.', DIRECTORY_SEPARATOR, $view);
    $view = app_path("Views/$view.php");

    if (!file_exists($view)) {
        throw new Exception("View '$view' not found.");
    }

    extract($data);

    ob_start();
    require $view;
    return ob_get_clean();
}

function session()
{
    return \Core\Session\Session::init();
}

function auth()
{
    return new \Core\Auth\Authenticate();
}
