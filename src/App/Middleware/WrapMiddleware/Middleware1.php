<?php

namespace App\Middleware\WrapMiddleware;

use Framework\MiddlewareInterface;

class Middleware1 implements MiddlewareInterface
{
    public function handle($requestUri, $httpMethod, $next)
    {
        echo "Middleware1: start\n";
        $next();
        echo "Middleware1: end\n";
    }

}