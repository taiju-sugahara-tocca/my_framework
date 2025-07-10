<?php

namespace App\View\Post;

use App\Model\Post\PostData;
use App\Model\User\UserData;
use App\Session\Session;

class PostView
{
 
    public static function index(UserData $user, array $posts): void
    {
        $messages = Session::getMessages();
        require_once __DIR__ . '/template/index.php';
    }

    public static function show(PostData $post): void
    {
        require_once __DIR__ . '/template/show.php';
    }

    public static function create(): void
    {
        $errors = Session::getErrors();
        require_once __DIR__ . '/template/edit.php';
    }

    public static function edit(PostData $post): void
    {
        $errors = Session::getErrors();
        require_once __DIR__ . '/template/edit.php';
    }
}