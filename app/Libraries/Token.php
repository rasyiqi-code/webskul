<?php

namespace App\Libraries;

class Token
{
    private $old_token;

    public function __construct()
    {
        $token = session('token');
        if ($token !== null) {
            $this->old_token = $token;
        }
    }

    private function set_token(): string
    {
        $ip = $_SERVER['REMOTE_ADDR'] ?? '127.0.0.1';
        $uniqid = uniqid(mt_rand(), true);
        return md5($ip . $uniqid);
    }

    public function get_token(): string
    {
        $token = $this->set_token();
        session()->set('token', $token);
        return $token;
    }

    public function is_valid_token($token): bool
    {
        return $token === $this->old_token;
    }
}
