<?php

use Core\Database\Schema;
use Core\Support\Env;

require_once __DIR__ . '/vendor/autoload.php';

Env::load(base_path('.env'));

Schema::createDatabaseIfNotExists();

foreach (glob(__DIR__ . '/database/migrations/*.php') as $file) {
    require_once $file;
}
