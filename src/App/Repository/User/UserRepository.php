<?php

namespace App\Repository\User;

use App\Interface\Repository\User\UserRepositoryInterface;
use App\Model\User\UserData;


class UserRepository implements UserRepositoryInterface
{

    public function authFindByEmail(string $email): ?UserData
    {
        $rows = UserData::query()->select("id, email, password")->where("email", "=", $email)->get();
        $user = UserData::getData($rows);
        return $user;
    }

    public function findById(int $id): ?UserData
    {
        $rows = UserData::query()->select("id, name, email")->where("id", "=", $id)->get();
        $user = UserData::getData($rows);
        return $user;
    }

    public function insertUser(array $saveData): int
    {
        $user_id = UserData::query()->insert($saveData);
        return $user_id;
    }
}
