<?php

use App\Models\User;

if (!function_exists('env')) {
    function env($key, $default = null) {
        $value = getenv($key);

        if ($value === false) {
            return $default;
        }

        return $value;
    }
}

if (!function_exists('dd')) {
    function dd(...$vars): void {
        foreach ($vars as $var) {
            echo "<pre>";
            var_dump($var);
            echo "</pre>";
        }
        die(1);
    }
}

if (!function_exists('redirect')) {
    function redirect(string $uri): void {
        $url = env('APP_URL');
        $uri = \Common\Requests\Router::normalizeUri($uri);
        header("Location: $url/$uri");
        exit;
    }
}

if (!function_exists('checkAuth')) {
    function checkAuth(): void {
        if (!isset($_SESSION['user_id'])) {
            $url = env('APP_URL');
            header("Location: $url/login");
            exit;
        }
    }
}

if (!function_exists('currentUser')) {
    function currentUser(): ?User {
        if (isset($_SESSION['user_id'])) {
            return User::find($_SESSION['user_id']);
        }
        return null;
    }
}

if (!function_exists('view')) {
    function view(string $template, array $data = []): void {
        $html = file_get_contents($template);
        foreach ($data as $name => $value) {
            $html = str_replace("{{$name}}", $value, $html);
        }

        echo $html;
    }
}