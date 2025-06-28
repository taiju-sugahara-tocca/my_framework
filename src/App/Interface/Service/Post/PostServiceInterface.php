<?php

namespace App\Interface\Service\Post;

use App\Model\Post\PostData;
use Framework\Request;

interface PostServiceInterface
{
    public function getPosts(Request $request): array;
    
    public function getPostById(int $id): ?PostData;

    public function savePost(Request $request): int;

    public function deletePost(int $id): void;
}