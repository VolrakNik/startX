<?php

namespace Common\Requests;

use Common\Exceptions\HttpException;
use Common\Invokers\ControllerInvoker;
use ReflectionException;

class Router
{
    public const GET_METHOD = 'GET';
    public const POST_METHOD = 'POST';

    private static $routes = [];
    private ControllerInvoker $controllerInvoker;

    public function __construct()
    {
        require_once __DIR__ . '/../../app/routes/routes.php';
        $this->controllerInvoker = new ControllerInvoker();
    }

    /**
     * @param string $uri
     * @param array $callable $callable
     * @return void
     */
    public static function get(string $uri, array $callable): void
    {
        $uri = self::normalizeUri($uri);
        self::$routes[self::GET_METHOD][$uri] = $callable;
    }

    public static function post(string $uri, array $callable): void
    {
        $uri = self::normalizeUri($uri);
        self::$routes[self::POST_METHOD][$uri] = $callable;
    }

    /**
     * @param string $uri
     * @param string $method
     * @return void
     * @throws ReflectionException
     */
    public function runRoute(string $uri, string $method): void
    {
        $method = strtoupper($method);
        $uri = parse_url($uri, PHP_URL_PATH);
        $uri = self::normalizeUri($uri);

        /** @var array|null $route */
        $route = self::$routes[$method][$uri] ?? null;
        if (!$route) {
            throw new HttpException(404, 'Page not Found');
        }

        [$class, $method] = $route;
        $controller = new $class();
        $this->controllerInvoker->call($controller, $method);
    }

    /**
     * @param string $uri
     * @return string
     */
    public static function normalizeUri(string $uri): string
    {
        return trim($uri, '/');
    }
}