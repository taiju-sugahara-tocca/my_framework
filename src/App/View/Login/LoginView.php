<?php

namespace App\View\Login;


class LoginView
{
    public static function index(): void
    {
        require_once __DIR__ . '/template/index.php';
    }

    public static function userRegister(): void
    {
        require_once __DIR__ . '/template/user_register.php';
    }
}