<?php

namespace App\Model\Session\Payload;

class FlashMessage
{
    public function __construct(
        public string $type,
        public string $text
    ) {
        if (!in_array($type, ['success', 'error', 'info'])) {
            throw new \InvalidArgumentException('Invalid flash message type');
        }
        if (empty($text)) {
            throw new \InvalidArgumentException('Flash message text cannot be empty');
        }
    }
}