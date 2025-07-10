<?php

namespace App\Model\Post\Parts;

class Title
{
    private string $value;

    public function __construct(string $value)
    {
        if ($value == '') {
            throw new \InvalidArgumentException('タイトルは空にできません');
        }

        if(mb_strlen($value) > 10) {
            throw new \InvalidArgumentException('タイトルは10文字以内でなければなりません');
        }

        $this->value = $value;
    }

    public function getValue(): string
    {
        return $this->value;
    }
}