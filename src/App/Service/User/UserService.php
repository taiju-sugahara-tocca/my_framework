<?php

namespace App\Service\User;

use App\Interface\Service\User\UserServiceInterface;
use App\Model\User\UserData;
use App\Repository\User\UserRepository;
use Framework\Request;

class UserService implements UserServiceInterface
{
    private UserRepository $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function authFindByEmail(string $email): ?UserData
    {
        $user = $this->userRepository->authFindByEmail($email);
        return $user;
    }

    public function  userRegisterStore(Request $request): int
    {
        $name = $request->post('name', '');
        $email = $request->post('email', '');
        $password = $request->post('password', '');

        // パスワードをハッシュ化
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        $userData = [
            'name' => $name,
            'email' => $email,
            'password' => $hashedPassword,
        ];

        // ユーザを登録
        $user_id =  $this->userRepository->insertUser($userData);

        return $user_id;
    }
}
