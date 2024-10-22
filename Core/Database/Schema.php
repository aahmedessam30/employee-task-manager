<?php

namespace Core\Database;

class Schema
{
    public static function create(string $table, callable $callback): void
    {
        try {
            $blueprint = new Blueprint($table);
            $callback($blueprint);

            $sql        = "CREATE TABLE $table ($blueprint)";
            $connection = Connection::getInstance();
            $connection->exec($sql);
            echo sprintf("Table [%s] created successfully.\n", $table);
        } catch (\PDOException $e) {
            echo "Error creating table '$table': " . $e->getMessage() . "\n";
        }
    }

    public static function drop(string $table): void
    {
        try {
            $sql        = "DROP TABLE $table";
            $connection = Connection::getInstance();
            $connection->exec($sql);
            echo sprintf("Table [%s] dropped successfully.\n", $table);
        } catch (\PDOException $e) {
            echo "Error dropping table '$table': " . $e->getMessage() . "\n";
        }
    }

    public static function table(string $table, callable $callback): void
    {
        try {
            $blueprint = new Blueprint($table);
            $callback($blueprint);

            $sql        = "ALTER TABLE $table ($blueprint)";
            $connection = Connection::getInstance();
            $connection->exec($sql);
            echo sprintf("Table [%s] altered successfully.\n", $table);
        } catch (\PDOException $e) {
            echo "Error altering table '$table': " . $e->getMessage() . "\n";
        }
    }

    public static function dropIfExists(string $table): void
    {
        if (self::hasTable($table)) {
            self::drop($table);
        }
    }

    public static function hasTable(string $table): bool
    {
        $sql        = "SHOW TABLES LIKE '$table'";
        $connection = Connection::getInstance();
        $statement  = $connection->query($sql);
        return $statement->rowCount() > 0;
    }

    public static function hasColumn(string $table, string $column): bool
    {
        $sql        = "SHOW COLUMNS FROM $table LIKE '$column'";
        $connection = Connection::getInstance();
        $statement  = $connection->query($sql);
        return $statement->rowCount() > 0;
    }

    public static function addColumn(string $table, string $column, string $type): void
    {
        try {
            $sql        = "ALTER TABLE $table ADD COLUMN $column $type";
            $connection = Connection::getInstance();
            $connection->exec($sql);
            echo sprintf("Column [%s] added to table [%s] successfully.\n", $column, $table);
        } catch (\PDOException $e) {
            echo "Error adding column '$column' to table '$table': " . $e->getMessage() . "\n";
        }
    }

    public static function dropColumn(string $table, string $column): void
    {
        try {
            $sql        = "ALTER TABLE $table DROP COLUMN $column";
            $connection = Connection::getInstance();
            $connection->exec($sql);
            echo sprintf("Column [%s] dropped from table [%s] successfully.\n", $column, $table);
        } catch (\PDOException $e) {
            echo "Error dropping column '$column' from table '$table': " . $e->getMessage() . "\n";
        }
    }

    public static function renameColumn(string $table, string $oldColumn, string $newColumn, string $type): void
    {
        try {
            $sql        = "ALTER TABLE $table CHANGE COLUMN $oldColumn $newColumn $type";
            $connection = Connection::getInstance();
            $connection->exec($sql);
            echo sprintf("Column [%s] renamed to [%s] in table [%s] successfully.\n", $oldColumn, $newColumn, $table);
        } catch (\PDOException $e) {
            echo "Error renaming column '$oldColumn' to '$newColumn' in table '$table': " . $e->getMessage() . "\n";
        }
    }

    public static function modifyColumn(string $table, string $column, string $type): void
    {
        try {
            $sql        = "ALTER TABLE $table MODIFY COLUMN $column $type";
            $connection = Connection::getInstance();
            $connection->exec($sql);
            echo sprintf("Column [%s] modified in table [%s] successfully.\n", $column, $table);
        } catch (\PDOException $e) {
            echo "Error modifying column '$column' in table '$table': " . $e->getMessage() . "\n";
        }
    }

    public static function renameTable(string $oldTable, string $newTable): void
    {
        try {
            $sql        = "RENAME TABLE $oldTable TO $newTable";
            $connection = Connection::getInstance();
            $connection->exec($sql);
            echo sprintf("Table [%s] renamed to [%s] successfully.\n", $oldTable, $newTable);
        } catch (\PDOException $e) {
            echo "Error renaming table '$oldTable' to '$newTable': " . $e->getMessage() . "\n";
        }
    }

    public static function truncate(string $table): void
    {
        try {
            $sql        = "TRUNCATE TABLE $table";
            $connection = Connection::getInstance();
            $connection->exec($sql);
            echo sprintf("Table [%s] truncated successfully.\n", $table);
        } catch (\PDOException $e) {
            echo "Error truncating table '$table': " . $e->getMessage() . "\n";
        }
    }

    public static function dropAllColumns(string $table): void
    {
        $sql        = "SHOW COLUMNS FROM $table";
        $connection = Connection::getInstance();
        $statement  = $connection->query($sql);
        $columns    = $statement->fetchAll(\PDO::FETCH_COLUMN);

        foreach ($columns as $column) {
            self::dropColumn($table, $column);
        }
    }

    public static function dropAllTables(): void
    {
        $sql        = "SHOW TABLES";
        $connection = Connection::getInstance();
        $statement  = $connection->query($sql);
        $tables     = $statement->fetchAll(\PDO::FETCH_COLUMN);

        foreach ($tables as $table) {
            self::drop($table);
        }
    }

    public static function dropAll($table): void
    {
        self::dropAllColumns($table);
        self::drop($table);
    }

    public static function dropAllIfExists($table): void
    {
        if (self::hasTable($table)) {
            self::dropAll($table);
        }
    }

    public static function dropAllTablesIfExists(): void
    {
        $sql        = "SHOW TABLES";
        $connection = Connection::getInstance();
        $statement  = $connection->query($sql);
        $tables     = $statement->fetchAll(\PDO::FETCH_COLUMN);

        foreach ($tables as $table) {
            self::dropAllIfExists($table);
        }
    }

    public static function dropAllColumnsIfExists($table): void
    {
        if (self::hasTable($table)) {
            self::dropAllColumns($table);
        }
    }

    public static function databaseExists(): bool
    {
        try {
            $database   = env('DB_DATABASE');
            $sql        = "SHOW DATABASES LIKE '$database'";
            $connection = Connection::getInstance();
            $statement  = $connection->query($sql);
            return $statement->rowCount() > 0;
        } catch (\PDOException $e) {
            return false;
        }
    }

    public static function createDatabase(): void
    {
        try {
            $database   = config('database.database');
            $sql        = "CREATE DATABASE $database CHARACTER SET " . config('database.charset') . " COLLATE " . config('database.collation') . ";";
            $connection = Connection::getInstance(true);
            $connection->exec($sql);
            echo sprintf("Database [%s] created successfully.\n", $database);
        } catch (\PDOException|\Exception $e) {
            echo sprintf("Error creating database '%s': %s\n", config('database.database'), $e->getMessage());
        }
    }

    public static function createDatabaseIfNotExists(): void
    {
        if (!self::databaseExists()) {
            self::createDatabase();
        }
    }

    public static function migrate(): void
    {
        foreach (glob(base_path('database/migrations/*.php')) as $file) {
            $file_name = basename($file);
            $file      = require_once $file;
            $migration = new $file;
            $migration->up();
        }
        echo "Migration completed successfully.\n";
    }

    public static function rollback(): void
    {
        foreach (glob(base_path('database/migrations/*.php')) as $file) {
            $file_name = basename($file);
            $file      = require_once $file;
            $migration = new $file;
            $migration->down();
        }
        echo "Rollback completed successfully.\n";
    }

    public static function seed(): void
    {
        foreach (glob(base_path('database/seeders/*.php')) as $file) {
            $seeder = str_replace('.php', '', basename($file));
            $seeder = new ("Database\\Seeders\\$seeder");
            $seeder->run();
            echo sprintf("[%s] Seeded successfully.\n", $seeder::class);
        }
    }

    public static function makeMigration($table): void
    {
        $table         = strtolower($table);
        $migrationName = strtolower($table);
        $migrationName = str_replace(['-', ' '], '_', $migrationName);
        $migrationName = preg_replace('/[^a-zA-Z0-9_]/', '', $migrationName);
        $migrationName = date('Y_m_d_His') . "_create_{$migrationName}_table";
        $migrationFile = migrations_path("$migrationName.php");

        if (file_exists($migrationFile)) {
            echo "Migration already exists.\n";
            return;
        }

        $stub = file_get_contents(stubs_path('migration.stub'));
        $stub = str_replace('{{ $table }}', $table, $stub);

        file_put_contents($migrationFile, $stub);
        echo "Migration created successfully.\n";
    }
}
