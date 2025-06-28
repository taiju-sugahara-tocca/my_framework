<?php

namespace App\Interface\Service\User;

use App\Model\User\UserData;
use Framework\Request;

interface UserServiceInterface
{
    public function authFindByEmail(string $email): ?UserData;

    public function userRegisterStore(Request $request): int;

}