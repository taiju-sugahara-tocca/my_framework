<?php

namespace App\Interface\Repository\Post;
use App\Model\Post\PostData;
use Framework\Request;

interface PostRepositoryInterface
{
    public function getPosts(Request $request): array;

    public function getPostById(int $id): ?PostData;

    public function insertPost(array $data): int;

    public function updatePost(int $id, array $data): void;

    public function deletePost(int $id): void;
}