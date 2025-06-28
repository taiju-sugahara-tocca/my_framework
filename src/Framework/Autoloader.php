<?php
namespace Framework;

class Autoloader
{
    public static function register()
    {
        spl_autoload_register([self::class, 'autoload']);
    }

    private static function autoload($class)
    {
        $baseDir = dirname(__DIR__); // Frameworkの1つ上＝src
        $file = $baseDir . '/' . str_replace('\\', '/', $class) . '.php';
        if (file_exists($file)) {
            require_once $file;
        }
    }
}