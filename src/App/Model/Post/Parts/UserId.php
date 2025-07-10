<?php

namespace App\Model\Post\Parts;

class UserId
{
    private string $value;

    public function __construct(string $value)
    {
        if (!$value) {
            throw new \InvalidArgumentException('ユーザIDは空にできません');
        }

        if(!is_numeric($value)) {
            throw new \InvalidArgumentException('ユーザIDは数値でなければなりません');
        }

        $this->value = $value;
    }

    public function getValue(): string
    {
        return $this->value;
    }
}