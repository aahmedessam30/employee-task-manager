<?php

use Core\Support\Env;

require_once __DIR__ . '/vendor/autoload.php';

Env::load(base_path('.env'));

foreach (glob(__DIR__ . '/database/seeders/*.php') as $file) {

    $seeder = str_replace('.php', '', basename($file));
    $seeder = new ("Database\\Seeders\\$seeder");
    $seeder->run();

    echo sprintf("[%s] Seeded successfully.\n", $seeder::class);
}
