<?php
use Framework\Autoloader;
use Framework\Router;
use App\Route\Routing;
use Framework\ErrorHandler;
use Framework\Request;
use Framework\Response;
use App\Container\Container;
use App\Middleware\WrapMiddleware\Middleware1;
use App\Middleware\WrapMiddleware\Middleware2;

require_once __DIR__ . '/Framework/Autoloader.php';

// オートローダー登録
Autoloader::register();
// エラーハンドラ・例外ハンドラ登録
set_error_handler([ErrorHandler::class, 'handleError']);
set_exception_handler([ErrorHandler::class, 'handleException']);

// Request
$request = new Request();
$requestUri = $request->uri();
$requestUri = strtok($requestUri, '?'); //クエリパラム除外
$httpMethod = $request->method();

//ルーティング設定
$routingList = Routing::routes();

$container = new Container();
$container = $container->register();
$router = new Router($routingList, $container);
// ミドルウェアの登録
// $router->addMiddleware(new Middleware1()); //このように全体ミドルウェア追加できる
// $router->addMiddleware(new Middleware2()); //このように全体ミドルウェア追加できる
$isCorrectRoute = $router->dispatch($requestUri, $httpMethod);

if (!$isCorrectRoute) {
    $response = new Response('Not Found', 404,);
    $response->send();
}

?>