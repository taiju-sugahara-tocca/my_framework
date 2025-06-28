<?php

namespace App\View\Post;

use App\Model\Post\PostData;
use App\Model\User\UserData;

class PostView
{
    public static function index(UserData $user, array $posts): void
    {
        require_once __DIR__ . '/template/index.php';
    }

    public static function show(PostData $post): void
    {
        require_once __DIR__ . '/template/show.php';
    }

    public static function create(): void
    {
        require_once __DIR__ . '/template/edit.php';
    }

    public static function edit(PostData $post): void
    {
        require_once __DIR__ . '/template/edit.php';
    }
}