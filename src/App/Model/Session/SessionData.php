<?php

namespace App\Model\Session;

use Framework\Model;
use Framework\SessionModelInterface;
use Framework\SessionPayloadInterface;

class SessionData extends Model implements SessionModelInterface
{
    private $id;
    private $session_id;
    private $user_id;
    private $expires_at;
    private $payload; //エラー情報などを格納するためのフィールド

    public function __construct($id=null,$session_id=null,$user_id=null,$expires_at=null, $payload=null) {
        $this->id = $id;
        $this->session_id = $session_id;
        $this->user_id = $user_id;
        $this->expires_at = $expires_at;
        $this->payload = $payload;
    }

    protected static function table(): string {
        return 'session_data';
    }

    protected static function standardSortable(): array
    {
        return ['id', 'session_id', 'user_id', 'expires_at'];
    }

    public function getId()
    {
        return $this->id;
    }

    public function getSessionId()
    {
        return $this->session_id;
    }

    public function getUserId(): ?int
    {
        return $this->user_id;
    }

    public function getExpiresAt()
    {
        return $this->expires_at;
    }   

    public function getAllPayload(): ?string
    {
        return $this->payload;
    }

    public function getPayload(SessionPayloadInterface $payload) : array
    {
        return $payload->getPayload();
    }

    public function setPayload(SessionPayloadInterface $payload, array $value): void
    {
        $payload->setPayload($value);
    }

    public static function createInstancefromArray(array $data)
    {
        return new self(
            $data['id'] ?? null,
            $data['session_id'] ?? null,
            $data['user_id'] ?? null,
            $data['expires_at'] ?? null,
            $data['payload'] ?? null
        );
    }

    public function createSession($session_id, $user_id, $expres_at): void
    {
        $query = $this->query();
        $query->insert([
            'session_id' => $session_id,
            'user_id' => $user_id,
            'expires_at' => $expres_at
        ]);
    }

    public function getSessionById(string $session_id): ?Model
    {
        $now = date('Y-m-d H:i:s');
        $query = $this->query();
        $query->where('session_id', "=", $session_id);
        $query->where('expires_at', '>', $now);
        $rows = $query->get();
        return $this->getData($rows);
    }

    public function deleteSession(string $session_id): void
    {
        $query = $this->query();
        $query->where('session_id', "=", $session_id);
        $query->delete();
    }

    public function deleteSessionByUserId(int $user_id): void
    {
        $query = $this->query();
        $query->where('user_id', "=", $user_id);
        $query->delete();
    }

    public function updatePayload(string $session_id, string $payload): void
    {
        $query = $this->query();
        $query->where('session_id', "=", $session_id);
        $query->update(['payload' => $payload]);
    }
    
}