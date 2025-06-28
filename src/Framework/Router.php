<?php
namespace Framework;

class Router
{
    private $routes;
    private $container;
    private $middlewares = [];
    private $correctRoute = false;

    public function __construct(array $routes, $container)
    {
        $this->routes = $routes;
        $this->container = $container;
    }

    public function addMiddleware(MiddlewareInterface $middleware) {
        $this->middlewares[] = $middleware;
    }

    public function dispatch($requestUri, $httpMethod)
    {
        $middlewareChain = array_reverse($this->middlewares); //全体ミドルウェア。最初が外側になるようにする。ラップ状態
        $next = function() use ($requestUri, $httpMethod) {
            foreach ($this->routes as $pattern => $routeParam) {
                if (preg_match($pattern, $requestUri, $matches) && $routeParam[0] === $httpMethod) {
                    $controllerName = $routeParam[1];
                    $methodName = $routeParam[2];
                    $routeMiddlewares = $routeParam[3] ?? [];

                    $routeMiddlewareChain = array_reverse($routeMiddlewares); //ルートミドルウェア。最初が外側になるようにする。ラップ状態

                    // DIコンテナを使用してコントローラーのインスタンスを取得
                    $controller = $this->container->make($controllerName);

                    $params = [];
                    foreach ($matches as $key => $value) {
                        if (!is_int($key)) { // 名前付きキャプチャのみ
                            $params[] = $value;
                        }
                    }

                    $route_next = function(){}; //コントローラー+ルートミドルウェア 
                    if (method_exists($controller, $methodName)) {
                        $this->correctRoute = true;
                        $route_next = function() use ($controller, $methodName, $params) {
                            call_user_func_array([$controller, $methodName], $params);
                        };
                    }

                    // ルートミドルウェアを適用
                    foreach ($routeMiddlewareChain as $middleware) {
                        $route_next = function() use ($middleware, $requestUri, $httpMethod, $route_next) {
                            $middlewareInstance = $this->container->make($middleware);
                            return $middlewareInstance->handle($requestUri, $httpMethod, $route_next);
                        };
                    }

                    return $route_next(); //コントローラー+ルートミドルウェア
                }
            }
        };

        // 全体ミドルウェアミドルウェアを適用
        foreach ($middlewareChain as $middleware) {
            $next = function() use ($middleware, $requestUri, $httpMethod, $next) {
                $middlewareInstance = $this->container->make($middleware);
                return $middlewareInstance->handle($requestUri, $httpMethod, $next);
            };
        }

        $next(); //実行
        return $this->correctRoute;
    }
}