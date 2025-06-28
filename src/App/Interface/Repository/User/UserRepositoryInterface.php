<?php

namespace App\Interface\Repository\User;
use App\Model\User\UserData;
use Framework\Request;

interface UserRepositoryInterface
{
    public function authFindByEmail(string $email): ?UserData;

    public function findById(int $id): ?UserData;

    public function insertUser(array $saveData): int;

}