<?php

use Illuminate\Database\Capsule\Manager as Capsule;

$capsule = new Capsule;

$capsule->addConnection([
    'driver'    => 'pgsql',
    'host'      => env('DB_HOST', 'postgres'),
    'database'  => env('DB_DATABASE', 'postgres'),
    'username'  => env('DB_USERNAME', 'postgres'),
    'password'  => env('DB_PASSWORD', 'postgres'),
    'charset'   => 'utf8',
    'collation' => 'utf8_unicode_ci',
    'prefix'    => '',
]);

// Инициализация Eloquent ORM
$capsule->setAsGlobal();
$capsule->bootEloquent();
