<?php

namespace Core\Support;

abstract class Env
{
    /**
     * @throws \Exception
     */
    public static function load($filePath = null)
    {
        $filePath = $filePath ?: base_path('.env');
        self::loadFile($filePath);
    }

    /**
     * @throws \Exception
     */
    private static function loadFile($filePath)
    {
        if (!file_exists($filePath)) {
            throw new \Exception('.env file not found.');
        }

        $lines = file($filePath, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        foreach ($lines as $line) {
            if (str_starts_with(trim($line), '#')) {
                continue;
            }

            list($key, $value) = explode('=', $line, 2);
            $_ENV[trim($key)] = trim($value);
        }
    }

    public static function get($key, $default = null)
    {
        return $_ENV[$key] ?? $default;
    }
}
