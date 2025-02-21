<?php

use Common\Exceptions\HandleExceptions;
use Common\Middlewares\CSRFMiddleware;
use Common\Requests\Router;
use Dotenv\Dotenv;

//session_set_cookie_params([
//    'secure' => true,
//    'samesite' => 'None'
//]);
session_start();
//session_destroy();
//setcookie(session_name(), '', time() - 3600, '/');
//exit();

require_once __DIR__.'/../vendor/autoload.php';
require_once __DIR__ . '/../app/bootstrap/database.php';

$handler = new HandleExceptions();
$handler->bootstrap(__DIR__ . '/../app/logs/app.log');

$dotenv = Dotenv::createUnsafeImmutable(__DIR__ . '/../');
$dotenv->load();

CSRFMiddleware::init();
CSRFMiddleware::csrfProtect();

$router = new Router();
$router->runRoute($_SERVER['REQUEST_URI'], $_SERVER['REQUEST_METHOD']);
