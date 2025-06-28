<?php

namespace App\Middleware\WrapMiddleware;

use Framework\MiddlewareInterface;

class Middleware2 implements MiddlewareInterface
{
    public function handle($requestUri, $httpMethod, $next)
    {
        echo "Middleware2: start\n";
        $next();
        echo "Middleware2: end\n";
    }

}