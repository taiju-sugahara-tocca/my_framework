<?php

namespace App\Model\Session\Payload;

use Framework\SessionPayloadInterface;
use App\Model\Session\SessionData;

class SessionErrorPayload implements SessionPayloadInterface
{
    private $payloadKey = 'errors';

    public function __construct(
        private string $session_id, 
        private SessionData $sessionData)
    {
    }

    public function getPayload(): array
    {
        $allPayload = $this->sessionData->getAllPayload();
        $payload = $allPayload ? json_decode($allPayload, true) : [];
        $errors = $payload[$this->payloadKey] ?? [];
        if(!empty($errors)) {
            unset($payload[$this->payloadKey]);
            $this->sessionData->updatePayload($this->session_id, json_encode($payload));
        }
        return $errors;
    }

    public function setPayload(array $value): void
    {
        $allPayload = $this->sessionData->getAllPayload();
        $payload = $allPayload ? json_decode($allPayload, true) : [];
        $payload[$this->payloadKey] = $value;
        $this->sessionData->updatePayload($this->session_id, json_encode($payload));
    }
}