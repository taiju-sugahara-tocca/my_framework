<?php

namespace App\Container;
//interface
use App\Interface\Service\Post\PostServiceInterface;
use App\Interface\Repository\Post\PostRepositoryInterface;
use App\Interface\Service\User\UserServiceInterface;
use App\Interface\Repository\User\UserRepositoryInterface;
//class
use App\Service\Post\PostService;
use App\Repository\Post\PostRepository;
use App\Service\User\UserService;
use App\Repository\User\UserRepository;
use Framework\DIContainer;

class Container
{
    public function register()
    {
        $container = new DIContainer();

        $container->bind(PostServiceInterface::class, function($c) {
            return new PostService($c->make(PostRepositoryInterface::class));
        });
        $container->bind(PostRepositoryInterface::class, PostRepository::class);

        $container->bind(UserServiceInterface::class, function($c) {
            return new UserService($c->make(UserRepositoryInterface::class));
        });
        $container->bind(UserRepositoryInterface::class, UserRepository::class);

        return $container;
    }
}
