<?php

use Common\Requests\Router;

Router::get('bank/home', [\App\Controllers\BankController::class, 'homeView']);
Router::post('bank/account/send', [\App\Controllers\BankController::class, 'bankAccountSend']);
Router::post('register', [\App\Controllers\AuthController::class, 'register']);
Router::post('login', [\App\Controllers\AuthController::class, 'login']);
Router::get('register', [\App\Controllers\AuthController::class, 'registerView']);
Router::get('login', [\App\Controllers\AuthController::class, 'loginView']);