<?php

namespace App\Controllers;

use App\Models\User;
use Common\Exceptions\ValidationException;
use Common\Middlewares\CSRFMiddleware;

class AuthController
{
    public function registerView(): void
    {
        view(__DIR__ . "/../views/bank/register.html", ['csrfToken' => CSRFMiddleware::getToken()]);
    }

    public function loginView(): void
    {
        view(__DIR__ . "/../views/bank/login.html", ['csrfToken' => CSRFMiddleware::getToken()]);
    }

    public function register(): void
    {
        $email = $_POST['email'] ? (string)$_POST['email'] : null;
        $password = $_POST['password'] ? (string)$_POST['password'] : null;

        if (!$email || !$password) {
            throw new ValidationException("Parameters 'email' and 'password' are required");
        }

        $user = new User();
        $user->email = $email;
        $user->password = password_hash($password, PASSWORD_DEFAULT);
        $user->save();

        $_SESSION['user_id'] = $user->id;
        redirect('bank/home');
    }

    public function login(): void
    {
        $email = $_POST['email'] ? (string)$_POST['email'] : null;
        $password = $_POST['password'] ? (string)$_POST['password'] : null;

        if (!$email || !$password) {
            throw new ValidationException("Parameters 'email' and 'password' are required");
        }

        $user = User::where('email', $email)->first();
        if ($user && password_verify($password, $user->password)) {
            $_SESSION['user_id'] = $user->id;
        }

        redirect('bank/home');
    }
}