<?php

namespace App\Service\Post;

use App\Interface\Service\Post\PostServiceInterface;
use App\Model\Post\PostData;
use App\Repository\Post\PostRepository;
use Framework\Request;
use App\Session\Session;


class PostService implements PostServiceInterface
{
    private PostRepository $postRepository;

    public function __construct(PostRepository $postRepository)
    {
        $this->postRepository = $postRepository;
    }

    public function getPosts(Request $request): array
    {
        $posts = $this->postRepository->getPosts($request);
        return $posts;
    }

    public function getPostById(int $id): ?PostData
    {
        $post = $this->postRepository->getPostById($id);
        return $post;
    }

    public function savePost(Request $request): int
    {
        $session = Session::start();
        $user_id = $session->user_id;
        $id = $request->post('id', null);
        $title = $request->post('title', '');
        $content = $request->post('content', '');

        $saveData = [
            'title' =>  $title,
            'content' => $content,
        ];

        if($id){
            $this->postRepository->updatePost($id, $saveData);
        }else{
            $saveData['user_id'] = $user_id;
            $id = $this->postRepository->insertPost($saveData);
        }

        return $id;
    }

    public function deletePost(int $id): void
    {
        $this->postRepository->deletePost($id);
    }
}
