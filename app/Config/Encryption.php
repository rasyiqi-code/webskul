<?php

namespace Config;

use CodeIgniter\Config\BaseConfig;

class Encryption extends BaseConfig
{
    public string $key = 'HQ=?cUP@Q3p%7BEWqQdss-vX25eNWthC';

    public string $driver = 'OpenSSL';

    public int $blockSize = 16;

    public string $digest = 'SHA512';

    public array $rawDataKeys = [];

    public string $encryptKey = '';

}
