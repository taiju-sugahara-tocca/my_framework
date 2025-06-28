<?php

namespace App\Model\User;

use Framework\Model;

class UserData extends Model
{
    private $id;
    private $name;
    private $email;
    private $password; // ハッシュ済みパスワード

    public function __construct($id, $name, $email, $password = null)
    {
        $this->id = $id;
        $this->name = $name;
        $this->email = $email;
        $this->password = $password;
    }

    protected static function table(): string {
        return 'user_data';
    }

    protected static function standardSortable(): array
    {
        return ['id', 'name', 'email'];
    }

    public function getId()
    {
        return $this->id;
    }

    public function getName()
    {
        return $this->name;
    }

    public function getEmail()
    {
        return $this->email;
    }

    public function getPassword()
    {
        return $this->password;
    }

    public static function createInstancefromArray(array $data)
    {
        return new self(
            $data['id'] ?? null,
            $data['name'] ?? null,
            $data['email'] ?? null,
            $data['password'] ?? null
        );
    }
}