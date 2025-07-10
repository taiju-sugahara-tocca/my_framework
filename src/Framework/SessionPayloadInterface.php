<?php
namespace Framework;

interface SessionPayloadInterface
{
    public function getPayload(): array;

    public function setPayload(array $value): void;
}
