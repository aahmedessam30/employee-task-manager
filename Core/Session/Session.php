<?php

namespace Core\Session;

class Session
{
    public static function init()
    {
        $session = SessionManager::getInstance();
        $session->start();
        return $session;
    }

    public static function start(): void
    {
        SessionManager::getInstance()->start();
    }

    public static function get(string $key, $default = null)
    {
        return SessionManager::getInstance()->get($key, $default);
    }

    public static function set(string $key, $value): void
    {
        SessionManager::getInstance()->put($key, $value);
    }

    public static function put(string $key, $value): void
    {
        SessionManager::getInstance()->put($key, $value);
    }

    public static function has(string $key): bool
    {
        return SessionManager::getInstance()->has($key);
    }

    public static function remove(string $key): void
    {
        SessionManager::getInstance()->remove($key);
    }

    public static function flash(string $key, $value): void
    {
        SessionManager::getInstance()->flash($key, $value);
    }

    public static function getFlash(string $key, $default = null)
    {
        return SessionManager::getInstance()->getFlash($key, $default);
    }

    public static function all(): array
    {
        return SessionManager::getInstance()->all();
    }

    public static function flush(): void
    {
        SessionManager::getInstance()->flush();
    }

    public static function terminate(): void
    {
        SessionManager::getInstance()->terminate();
    }

    public static function remember(string $key, $value): void
    {
        if (!SessionManager::getInstance()->has($key)) {
            SessionManager::getInstance()->put($key, $value);
        }
    }

    public static function forget(string $key): void
    {
        SessionManager::getInstance()->remove($key);
    }
}
