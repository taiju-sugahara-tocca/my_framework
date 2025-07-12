<?php

namespace App\Repository\Post;

use App\Interface\Repository\Post\PostRepositoryInterface;
use App\Model\Post\PostData;
use App\DB\DBConnection;
use App\Model\User\UserData;
use App\Model\Post\PostDataFactory;
use Framework\QueryBuilder;
use Framework\Request;
use App\Dto\Post\PostWithTitleLengthTypeDto;

class PostRepository implements PostRepositoryInterface
{
    public function getPosts(Request $request): array
    {
        $limit = $request->get('limit');
        $offset = $request->get('offset');
        $sort_column = $request->get('sort_column');
        $sort_direction = $request->get('sort_direction', 'ASC');

        $builder = PostData::query();
        $select_columns = [
            'post_data.id AS post_data_id',
            'post_data.title AS post_data_title',
            'post_data.content AS post_data_content',
            'post_data.user_id AS post_data_user_id',
            'user_data.name AS user_data_name'
        ];
        $builder->select($select_columns)
        ->selectRaw('CASE WHEN LENGTH(post_data.title) > 3 THEN "long" ELSE "short" end AS title_length_type')
        ->leftJoin('user_data', 'post_data.user_id = user_data.id');
        
        //filter
        $builder = $this->filterPosts($builder, $request);     
        //offset/limit
        if (!empty($limit)) {
            $builder->limit($limit);
        }
        if (!empty($offset)) {
            $builder->offset($offset);
        }
        //sort
        if (!empty($sort_column)) {
            if($sort_column === 'id') {
                $sort_column = 'post_data.id'; // idが複数あるため考慮
            } 
            $builder->orderBy($sort_column, $sort_direction);
        }

        $rows = $builder->get();

        $posts = [];
        
        if (!empty($rows)) {
            foreach ($rows as $row) {
                $post = PostDataFactory::createPostWithUser($row);
                $title_length_type = $row['title_length_type'];
                $postDto = new PostWithTitleLengthTypeDto($post,$title_length_type);
                $posts[] = $postDto;
            }
        }

        return $posts;
    }

    private function filterPosts(QueryBuilder $builder, Request $request): QueryBuilder
    {
        $title = $request->get('title');
        $content = $request->get('content');

        if (!empty($title)) {
            $builder->like('title', $title);
        }

        if (!empty($content)) {
            $builder->like('content', $content);
        }

        return $builder;
    }

    public function getPostById($id): ?PostData
    {
        $select_columns = [
            'post_data.id AS post_data_id',
            'post_data.title AS post_data_title',
            'post_data.content AS post_data_content',
            'post_data.user_id AS post_data_user_id',
            'user_data.name AS user_data_name'
        ];
        $rows = PostData::query()->select($select_columns)
        ->innerJoin('user_data', 'post_data.user_id = user_data.id')
        ->where("post_data.id", "=", $id)->get();
        $row = $rows[0] ?? null;

        $post = PostDataFactory::createPostWithUser($row);
        return $post;
    }

    public function insertPost(array $data): int
    {
        $id = PostData::query()->insert($data);
        return $id;
    }

    public function updatePost(int $id, array $data): void
    {
        PostData::query()->where("id", "=", $id)->update($data);
    }

    public function deletePost(int $id): void
    {
        PostData::query()->where("id", "=", $id)->delete();
    }
}
