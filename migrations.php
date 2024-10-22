<?php

require_once __DIR__ . '/vendor/autoload.php';

use Core\Database\Schema;
use Core\Support\Env;

Env::load();

$commands = $argv;
array_shift($commands);

if (empty($commands)) {
    throw new Exception('No command provided');
}

if (!in_array($commands[0], ['migrate', 'rollback', 'seed', 'make:migration'])) {
    throw new Exception('Invalid command');
}

if ($commands[0] === 'make:migration') {
    if (!isset($argv[1])) {
        throw new Exception('Migration name not provided');
    }
}

match ($commands[0]) {
    'migrate'        => Schema::migrate(),
    'rollback'       => Schema::rollback(),
    'seed'           => Schema::seed(),
    'make:migration' => Schema::makeMigration($argv[array_key_last($argv)]),
    default          => throw new Exception('Invalid command'),
};
