<?php

namespace App\Model\Post;

use App\Model\Post\PostData;
use App\Model\User\UserData;

class PostDataFactory
{

    /**
     *１件分のpostとuserのインスタンスを生成する
     */
    public static function createPostWithUser(array $row): PostData
    {
        return self::commonCreatePostWithUser($row);
    }

    /**
     * 複数件のpostとuserのインスタンスを生成する
     */
    public static function createPostsWithUsers(array $rows): array
    {
        $posts = [];
        foreach ($rows as $row) {
            $posts[] = self::commonCreatePostWithUser($row);
        }
        return $posts;
    }

    /**
     * postとuserのインスタンスを生成する
     */
    private static function commonCreatePostWithUser(array $row): PostData
    {
        $post = PostData::createInstancefromArray(
            PostData::extractPrefixedArray($row)
        );
        $user = UserData::createInstancefromArray(
            UserData::extractPrefixedArray($row)
        );
        $post->setUser($user);
        return $post;
    }
    
}