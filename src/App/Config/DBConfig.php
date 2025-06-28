<?php
namespace App\Config;

class DBConfig
{
    private static $config = [
        'host' => 'my_framework_db', // Docker Composeで定義したサービス名
        'dbname' => 'my_framework_app',
        'user' => 'user',
        'password' => 'password',
        'charset' => 'utf8mb4',
        'port' => 3306
    ];

    public static function getConfig(): array
    {
        return self::$config;
    }
}

