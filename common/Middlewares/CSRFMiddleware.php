<?php
namespace Common\Middlewares;

use Common\Exceptions\CSRFException;

class CSRFMiddleware
{
    public static function init(): void
    {
        if (!isset($_SESSION['csrf_token'])) {
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        }
    }

    public static function getToken(): string
    {
        $token = $_SESSION['csrf_token'] ?? '';
        if (empty($token)) {
            throw new CSRFException('CSRF Token not set');
        }
        return $token;
    }

    public static function validate($token): bool
    {
        return isset($_SESSION['csrf_token']) && hash_equals($_SESSION['csrf_token'], $token);
    }

    public static function csrfProtect(): void
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $token = $_POST['csrfToken'] ?? '';
            if (!self::validate($token)) {
                throw new CSRFException('Invalid CSRF token');
            }
        }
    }
}