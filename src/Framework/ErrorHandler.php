<?php
namespace Framework;

class ErrorHandler
{
    public static function handleError($errno, $errstr, $errfile, $errline)
    {
        http_response_code(500);
        echo "Error: [$errno] $errstr in $errfile on line $errline";
        // エラーを例外としてスロー
        throw new \ErrorException($errstr, 0, $errno, $errfile, $errline);
    }

    public static function handleException($exception)
    {
        http_response_code(500);
        echo "Exception: " . $exception->getMessage() . 
             " in " . $exception->getFile() . 
             " on line " . $exception->getLine();
    }
}