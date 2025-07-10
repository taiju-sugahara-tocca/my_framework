<?php

namespace App\Model\Post\Parts;

class Content
{
    private string $value;

    public function __construct(string $value)
    {
        if ($value == '') {
            throw new \InvalidArgumentException('内容は空にできません');
        }
        $this->value = $value;
    }

    public function getValue(): string
    {
        return $this->value;
    }
}