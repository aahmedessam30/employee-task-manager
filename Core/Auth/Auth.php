<?php

namespace Core\Auth;

/**
 * @method static user()
 * @method static check()
 * @method static guest()
 * @method static id()
 * @method static attempt(array $credentials)
 */
class Auth
{
    protected static ?Authenticate $instance = null;

    public static function __callStatic($method, $arguments)
    {
        return static::getInstance()->$method(...$arguments);
    }

    protected static function getInstance(): Authenticate
    {
        if (static::$instance === null) {
            static::$instance = new Authenticate();
        }

        return static::$instance;
    }
}
