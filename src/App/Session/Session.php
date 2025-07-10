<?php

namespace App\Session;

use App\Model\Session\SessionData;
use Framework\Session as FrameSession;
use App\Model\Session\Payload\SessionErrorPayload;
use App\Model\Session\Payload\SessionMessagePayload;

class Session
{
    public static function start()
    {
        $sessionData = new SessionData();
        return FrameSession::start($sessionData);
    }

    public static function getErrors(): array
    {
        $session = self::start();
        $sessionModelWithData = $session->sessionModel->getSessionById($session->session_id);
        $errorPaylod = new SessionErrorPayload($session->session_id, $sessionModelWithData);
        return $session->getPayload($errorPaylod);
    }

    public static function setErrors(array $errors): void
    {
        $session = self::start();
        $sessionModelWithData = $session->sessionModel->getSessionById($session->session_id);
        $errorPayload = new SessionErrorPayload($session->session_id, $sessionModelWithData);
        $session->setPayload($errorPayload, $errors);
    }

    public static function getMessages(): array
    {
        $session = self::start();
        $sessionModelWithData = $session->sessionModel->getSessionById($session->session_id);
        $messagePayload = new SessionMessagePayload($session->session_id, $sessionModelWithData);
        return $session->getPayload($messagePayload);
    }

    public static function setMessages(array $messages): void
    {
        $session = self::start();
        $sessionModelWithData = $session->sessionModel->getSessionById($session->session_id);
        $messagePayload = new SessionMessagePayload($session->session_id, $sessionModelWithData);
        $session->setPayload($messagePayload, $messages);
    }

}