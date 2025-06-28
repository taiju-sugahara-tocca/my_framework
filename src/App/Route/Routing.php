<?php
namespace App\Route;

class Routing
{
    public static function routes()
    {
        $authMiddle = 'App\Middleware\RouteMiddleware\AuthMiddleware';
        //*同じパスは設定できない設定（配列のkeyが同じになるため）
        $routingList = [
            // /login → ログイン画面
            '#^/login$#' => ["GET", 'App\Controller\Login\LoginController', 'index'],
            // /login/authenticate → ログイン処理
            '#^/login/authenticate$#' => ["POST", 'App\Controller\Login\LoginController', 'login'],
            /// /logout → ログアウト処理
            '#^/logout$#' => ["GET", 'App\Controller\Login\LoginController', 'logout', [$authMiddle]],
            // /register → ユーザ登録画面
            '#^/register$#' => ["GET", 'App\Controller\Login\LoginController', 'userRegister'],
            // /register/save → ユーザ登録処理
            '#^/register/store#' => ["POST", 'App\Controller\Login\LoginController', 'userRegisterStore'],
            // /posts → 一覧画面
            '#^/posts$#' => ["GET", 'App\Controller\Post\PostController', 'index', [$authMiddle]],
            // /posts/create → 作成画面
            '#^/posts/create$#' => ["GET", 'App\Controller\Post\PostController', 'create', [$authMiddle]],
            // /posts/edit/123 → 編集画面
            '#^/posts/edit/(?<id>\d+)$#' => ["GET", 'App\Controller\Post\PostController', 'edit', [$authMiddle]],
            // /posts/123 → 詳細画面
            '#^/posts/show/(?<id>\d+)$#' => ["GET", 'App\Controller\Post\PostController', 'show', [$authMiddle]],
            // /posts/save → 作成・更新処理
            '#^/posts/save$#' => ["POST", 'App\Controller\Post\PostController', 'save', [$authMiddle]],
            // /posts/delete/123 → 削除処理
            '#^/posts/delete/(?<id>\d+)$#' => ["POST", 'App\Controller\Post\PostController', 'delete', [$authMiddle]],
        ];
        return $routingList;
    }
}