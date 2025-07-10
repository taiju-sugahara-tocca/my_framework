<?php
namespace Framework;

class Session
{
    private static $instance;
    public $sessionModel;
    public $session_id;
    public $user_id;
    public $user = null; //アプリ側でユーザ情報をセットするための変数

    const ONE_DAY_SECOND = 60 * 60 * 24; // 1 day in seconds

    public static function start(SessionModelInterface $sessionModel)
    {
        if (!isset(self::$instance)) {

            self::$instance = new self();
            self::$instance->sessionModel = $sessionModel;
            $request = new Request();

            $session_id = $request->cookie("my_framework_session_id");
            if(!$session_id){
                $session_id = self::$instance->createSessionId();
                $sessionModel->createSession($session_id, null, date("Y-m-d H:i:s", strtotime("+" . self::ONE_DAY_SECOND . " second")));
                setcookie("my_framework_session_id", $session_id, time() + self::ONE_DAY_SECOND, "/");
            }
            self::$instance->session_id = $session_id;
            $sessionModelWithData = self::$instance->sessionModel->getSessionById($session_id);
            self::$instance->user_id = $sessionModelWithData->getUserId();
        }
        return self::$instance;
    }

    private function createSessionId()
    {
        $now = microtime();
        $random = bin2hex(random_bytes(16));
        $session_id = hash('sha256', $random . $now);
        return $session_id;
    }

    public function login($user_id)
    {
        $session_id = $this->session_id;
        if ($session_id) {
            $session_id = $this->createSessionId();
            $this->sessionModel->deleteSessionByUserId($user_id);
            $this->sessionModel->createSession($session_id, $user_id, date("Y-m-d H:i:s", strtotime("+" . self::ONE_DAY_SECOND . " second")));
            setcookie("my_framework_session_id", $session_id, time() + self::ONE_DAY_SECOND, "/");
            $this->user_id = $user_id;
        } else {
            throw new \Exception("Session ID not found.");
        }
    }

    public function logout()
    {
        $session_id = $this->session_id;
        if ($session_id) {
            $this->sessionModel->deleteSession($session_id);
            setcookie("my_framework_session_id", "", time() - 3600, "/"); // Clear the cookie
        } else {
            throw new \Exception("Session ID not found.");
        }
    }

    public function isLogIn(): bool
    {
        $session_id = $this->session_id;
        if ($session_id) {
            $sessionModel = $this->sessionModel->getSessionById($session_id);
            if ($sessionModel && $sessionModel->getUserId()) {
                return true;
            }
        }
        return false;
    }

    public function getPayload($payload): array
    {
        $data = [];
        $sessionModel = $this->sessionModel->getSessionById($this->session_id);
        if ($sessionModel) {
            $data = $sessionModel->getPayload($payload);
        }
        return $data;
    }

    public function setPayload($payload, $value): void
    {
        $sessionModel = $this->sessionModel->getSessionById($this->session_id);
        if ($sessionModel) {
            $sessionModel->setPayload($payload, $value);
        }
    }
}