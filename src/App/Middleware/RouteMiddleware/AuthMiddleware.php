<?php

namespace App\Middleware\RouteMiddleware;

use Framework\MiddlewareInterface;
use App\Session\Session;
use Framework\Response;
use App\Interface\Repository\User\UserRepositoryInterface;

class AuthMiddleware implements MiddlewareInterface
{
    private UserRepositoryInterface $userRepository;

    public function __construct(UserRepositoryInterface $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function handle($requestUri, $httpMethod, $next)
    {
        if ($this->isAuthenticated()) {
            $this->setUser();
            return $next(); // 次のミドルウェアまたはルートハンドラーを呼び出す
        } else {
            Response::redirect('/login');
        }
    }

    private function isAuthenticated()
    {
        $session = Session::start();
        return $session->isLogin();
    }

    public function setUser()
    {
        $session = Session::start();
        $user = $this->userRepository->findById($session->user_id);
        $session->user = $user;
    }
}