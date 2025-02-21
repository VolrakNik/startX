<?php

use Common\Exceptions\HandleExceptions;
use Common\Middlewares\CSRFMiddleware;
use Common\Requests\Router;
use Dotenv\Dotenv;

session_start();

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
