<?php

namespace App\Session;

use App\Model\Session\SessionData;
use Framework\Session as FrameSession;

class Session
{
    public static function start()
    {
        $sessionData = new SessionData();
        return FrameSession::start($sessionData);
    }
}