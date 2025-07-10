<?php

namespace App\Model\Post\Parts;

class Id
{
    private string $value;

    public function __construct(string $value)
    {
        if (!$value) {
            throw new \InvalidArgumentException('IDは空にできません');
        }

        if(!is_numeric($value)) {
            throw new \InvalidArgumentException('IDは数値でなければなりません');
        }

        $this->value = $value;
    }

    public function getValue(): string
    {
        return $this->value;
    }
}