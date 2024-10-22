<?php

namespace Core\Session;

use Random\RandomException;

class SessionManager
{
    private static $instance = null;
    private $started = false;

    private function __construct()
    {
    }

    public static function getInstance(): SessionManager
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    public function start(): void
    {
        if (!$this->started) {
            session_start();
            $this->started = true;
        }
    }

    public function get(string $key, $default = null)
    {
        $value = $_SESSION[$key] ?? $_SESSION['_flash'][$key] ?? $default;

        if (isset($_SESSION['_flash'][$key])) {
            unset($_SESSION['_flash'][$key]);
        }

        return $value;
    }

    public function put(string $key, $value): void
    {
        $_SESSION[$key] = $value;
    }

    public function has(string $key): bool
    {
        return isset($_SESSION[$key]) || isset($_SESSION['_flash'][$key]);
    }

    public function remove(string $key): void
    {
        unset($_SESSION[$key]);
    }

    public function flash(string $key, $value): void
    {
        $_SESSION['_flash'][$key] = $value;
    }

    public function getFlash(string $key, $default = null)
    {
        $value = $_SESSION['_flash'][$key] ?? $default;
        $this->removeFlash($key);
        return $value;
    }

    public function removeFlash(string $key): void
    {
        unset($_SESSION['_flash'][$key]);
    }

    public function all(): array
    {
        return $_SESSION;
    }

    public function flush(): void
    {
        session_unset();
    }

    public function terminate(): void
    {
        session_write_close();
    }

    /**
     * @throws RandomException
     */
    public function csrfToken(): string
    {
        $token = $this->get('_token');

        if (!$token) {
            $token = bin2hex(random_bytes(32));
            $token = str_replace(' ', '', $token);
            $this->put('_token', $token);
        }

        return $token;
    }
}
