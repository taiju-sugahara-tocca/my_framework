<?php

namespace App\Model\Session\Payload;

use Framework\SessionPayloadInterface;
use App\Model\Session\SessionData;
use App\Model\Session\Payload\FlashMessage;

class SessionMessagePayload implements SessionPayloadInterface
{
    private $payloadKey = 'messages';

    public function __construct(
        private string $session_id, 
        private SessionData $sessionData)
    {
    }


    /**
     *
     * @return array<int, array{type: string, text: string}> $value
     */
    public function getPayload(): array
    {
        $allPayload = $this->sessionData->getAllPayload();
        $payload = $allPayload ? json_decode($allPayload, true) : [];
        $messages = $payload[$this->payloadKey] ?? [];
        if(!empty($messages)) {
            unset($payload[$this->payloadKey]);
            $this->sessionData->updatePayload($this->session_id, json_encode($payload));
        }

        return $messages;
    }

    /**
     * @param array<int, array{type: string, text: string}> $value
     */
    public function setPayload(array $value): void
    {
        foreach ($value as $msg) {
            //型が正しいかチェック
            $message = new FlashMessage($msg['type'], $msg['text']);
        }
        $allPayload = $this->sessionData->getAllPayload();
        $payload = $allPayload ? json_decode($allPayload, true) : [];
        $payload[$this->payloadKey] = $value;
        $this->sessionData->updatePayload($this->session_id, json_encode($payload));
    }
}