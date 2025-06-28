<?php
namespace App\Controller\Login;

use App\Interface\Service\User\UserServiceInterface;
use App\View\Login\LoginView;
use App\Service\User\UserService;
use Framework\Request;
use Framework\Response;
use App\Session\Session;

class LoginController {
    private UserService $userService;
    private Request $request;

    public function __construct(UserServiceInterface $userService) {
        $this->userService = $userService;
        $this->request = new Request();
    }

    /**
     * ログイン画面
     */
    public function index() {
        $session = Session::start();
        if ($session->isLogin()) {
            Response::redirect('/posts');
        }
        LoginView::index();
    }

    /**
     * ログイン処理
     */
    public function login() {
        $session = Session::start();
        $email = $this->request->post('email', '');
        $password = $this->request->post('password', '');
        $user = $this->userService->authFindByEmail($email);
        if (!$user) {
            throw new \Exception('ユーザが見つかりません。');
        }
        if (!password_verify($password, $user->getPassword())) {
            throw new \Exception('パスワードが違います。');
        }

        //ログイン
        $session->login($user->getId());
        Response::redirect('/posts');
    }

    /**
     * ログアウト処理
     */
    public function logout() {
        $session = Session::start();
        $session->logout();
        Response::redirect('/login');
    }

    /**
     * ユーザ登録画面
     */
    public function userRegister() {
        $session = Session::start();
        if ($session->isLogin()) {
            Response::redirect('/posts');   
        }
        LoginView::userRegister();
    }

    /**
     * ユーザ登録処理
     */
    public function userRegisterStore() {
        $session = Session::start();
        $email = $this->request->post('email', '');
        $password = $this->request->post('password', '');

        if (empty($email) || empty($password)) {
            throw new \Exception('メールアドレスとパスワードは必須です。');
        }

        //ユーザ登録
        $user_id = $this->userService->userRegisterStore($this->request);

        //ログインする
        $session->login($user_id);
        Response::redirect('/posts');
    }

}