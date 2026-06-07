<?php

namespace Config;

use CodeIgniter\Config\BaseConfig;
use CodeIgniter\Session\Handlers\DatabaseHandler;

class Session extends BaseConfig
{
    public string $driver = 'CodeIgniter\Session\Handlers\DatabaseHandler';
    public string $cookieName = '_sessions';
    public int $expiration = 7200;
    public string $savePath = '_sessions';
    public bool $matchIP = false;
    public int $timeToUpdate = 300;
    public bool $regenerateDestroy = false;
    public ?string $memcachedHost = null;
    public int $memcachedPort = 11211;
    public string $redisHost = '127.0.0.1';
    public int $redisPort = 6379;
    public string $redisAuth = '';
    public string $redisPrefix = 'ci_session:';
    public string $redisDatabase = '0';
    public array $cookie = [
        'prefix'   => '',
        'domain'   => '',
        'path'     => '/',
        'secure'   => false,
        'httponly' => false,
        'samesite' => 'Lax',
    ];
}
