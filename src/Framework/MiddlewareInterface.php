<?php
namespace Framework;

interface MiddlewareInterface {
    public function handle($requestUri, $httpMethod, $next);
}

