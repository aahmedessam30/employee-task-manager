<?php

require_once __DIR__ . '/../vendor/autoload.php';

use Core\{Exceptions\ExceptionHandler, Http\Request, Support\Env, Routing\Router};

try {
    Env::load(base_path('.env'));

    $request = Request::capture();

    (new Router())->dispatch($request);

} catch (Throwable $e) {
    (new ExceptionHandler())->handle($e);
}
