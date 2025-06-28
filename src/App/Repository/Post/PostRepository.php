<?php

namespace App\Repository\Post;

use App\Interface\Repository\Post\PostRepositoryInterface;
use App\Model\Post\PostData;
use App\DB\DBConnection;
use Framework\QueryBuilder;
use Framework\Request;

class PostRepository implements PostRepositoryInterface
{
    public function getPosts(Request $request): array
    {
        $limit = $request->get('limit');
        $offset = $request->get('offset');
        $sort_column = $request->get('sort_column');
        $sort_direction = $request->get('sort_direction', 'ASC');

        $builder = PostData::query();
        
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
            $builder->orderBy($sort_column, $sort_direction);
        }

        $rows = $builder->get();

        $posts = PostData::getDatalist($rows);

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
        $rows = PostData::query()->where("id", "=", $id)->get();
        $post = PostData::getData($rows);
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
