<?php

namespace Core\Database;

use PDO;
use PDOException;

class Connection
{
    private static PDO $connection;
    private static ?Connection $instance = null;

    /**
     * @throws \Exception
     */
    private static function createConnection(bool $forDatabaseCreation = false): void
    {
        $host    = config('database.host');
        $user    = config('database.username');
        $pass    = config('database.password');
        $charset = config('database.charset');

        $dsn     = "mysql:host=$host;charset=$charset";
        $options = [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES   => false,
        ];

        if (!$forDatabaseCreation) {
            $dsn .= ";dbname=" . config('database.database');
        }

        try {
            self::$connection = new PDO($dsn, $user, $pass, $options);
        } catch (PDOException $e) {
            throw new PDOException($e->getMessage(), (int)$e->getCode());
        }
    }

    /**
     * @throws \Exception
     */
    public static function getInstance($forDatabaseCreation = false): PDO
    {
        if ($forDatabaseCreation || self::$instance === null) {
            self::createConnection($forDatabaseCreation);
        }

        return self::$connection;
    }
}
