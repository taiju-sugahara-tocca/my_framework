<?php
namespace Framework;
use Framework\SessionPayloadInterface;

interface SessionModelInterface
{
    public function createSession(string $session_id, ?int $user_id, int $expires_at): void;

    public function getSessionById(string $session_id): ?Model;

    public function deleteSession(string $session_id): void;

    public function getUserId(): ?int;

    public function deleteSessionByUserId(int $user_id): void;

    public function getPayload(SessionPayloadInterface $payload): array;

    public function setPayload(SessionPayloadInterface $payload, array $value): void;
}
