<?php

require_once __DIR__.'/../../vendor/autoload.php';
require __DIR__ . '/../bootstrap/database.php';

use Illuminate\Database\Capsule\Manager as Capsule;
use Illuminate\Database\Schema\Blueprint;

Capsule::schema()->create('users', function (Blueprint $table) {
    $table->id();
    $table->integer('balance')->default(1000);
    $table->string('email')->nullable(false)->unique();
    $table->string('password')->nullable(false);
});

echo "Миграция users создана!\n";
