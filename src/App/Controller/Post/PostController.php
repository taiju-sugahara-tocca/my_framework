<?php
namespace App\Controller\Post;

use App\Interface\Service\Post\PostServiceInterface;
use App\View\Post\PostView;
use App\Service\Post\PostService;
use Framework\Request;
use Framework\Response;
use App\Session\Session;
use App\Validation\Request\Post\PostSaveRequest;

class PostController {
    private PostService $postService;
    private Request $request;

    public function __construct(PostServiceInterface $postService) {
        $this->postService = $postService;
        $this->request = new Request();
    }

    public function index() {
        $session = Session::start();
        $user = $session->user;
        $posts = $this->postService->getPosts($this->request); // 全ての投稿を取得
        PostView::index($user, $posts); // 投稿一覧を表示するビューを呼び出す
    }

    public function show($id) {
        $post = $this->postService->getPostById($id);
        PostView::show($post);
    }

    public function create() {
        PostView::create(); // 投稿作成画面を表示するビューを呼び出す
    }

    public function edit($id) {
        $session = Session::start();
        $user_id = $session->user_id;
        $post = $this->postService->getPostById($id);
        if ($post->getUserId() != $user_id) {
            throw new \Exception('この投稿を編集する権限がありません。');
        }
        PostView::edit($post); // 投稿編集画面を表示するビューを呼び出す
    }

    public function save() {
        $session = Session::start();
        $id = $this->request->post('id', null);
        //バリデーション
        $errors = PostSaveRequest::validate();
        if (!empty($errors)) {
            if(!empty($id)) {
                Response::redirect('/posts/edit/' . $id);
            } else {
                Response::redirect('/posts/create');
            }
        }
        
        $user_id = $session->user_id;
        
        if($id){ // 編集の場合
            $post = $this->postService->getPostById($id);
            if ($post->getUserId() != $user_id) {
                throw new \Exception('この投稿を編集する権限がありません。');
            }
        }

        // 投稿の保存処理を呼び出す
        $this->postService->savePost($this->request);

        $successMessage = $id ? '投稿を更新しました。' : '投稿を作成しました。';
        Session::setMessages([["type" => "success", "text" => $successMessage]]);

        // 保存後、一覧画面にリダイレクト
        Response::redirect('/posts');
    }

    public function delete($id) {
        $session = Session::start();
        $user_id = $session->user_id;
        $post = $this->postService->getPostById($id);
        if ($post->getUserId() != $user_id) {
            throw new \Exception('この投稿を削除する権限がありません。');
        }
        // 投稿の削除処理を呼び出す
        $this->postService->deletePost($id);

        Session::setMessages([["type" => "success", "text" => "投稿を削除しました。"]]);

        // 削除後、一覧画面にリダイレクト
        Response::redirect('/posts');
    }
}