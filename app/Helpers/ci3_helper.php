<?php

/**
 * Helper kompatibilitas CI3 -> CI4.
 *
 * Berisi shim untuk function yang dipanggil dari view/controller hasil porting
 * dari CI3 (webskul-ci3). Fungsi-fungsi ini memastikan transisi mulus
 * tanpa harus menyentuh ratusan call site.
 */

if (!function_exists('__session')) {
    /**
     * Ambil data session dengan fallback ke $_SESSION.
     * CI3 helper ini digunakan di banyak view & controller.
     */
    function __session(?string $key = null)
    {
        if ($key === null || $key === '') {
            return session()->get();
        }

        return session($key);
    }
}

if (!function_exists('get_ip_address')) {
    /**
     * Ambil IP address user, fallback ke REMOTE_ADDR.
     */
    function get_ip_address(): string
    {
        $candidates = [
            'HTTP_X_FORWARDED_FOR',
            'HTTP_X_REAL_IP',
            'HTTP_CLIENT_IP',
            'REMOTE_ADDR',
        ];

        foreach ($candidates as $name) {
            if (!empty($_SERVER[$name])) {
                $ip = $_SERVER[$name];
                if (strpos($ip, ',') !== false) {
                    $ip = trim(explode(',', $ip)[0]);
                }
                return filter_var($ip, FILTER_VALIDATE_IP) ? $ip : '0.0.0.0';
            }
        }

        return '0.0.0.0';
    }
}

if (!function_exists('xml_convert')) {
    /**
     * Konversi teks menjadi aman untuk XML (CI3 style).
     */
    function xml_convert(string $str, bool $protectAll = false): string
    {
        $temp = '__TEMP_AMPERSANDS__';

        $str = preg_replace('/&#(\d+);/', $temp . '\\1;', $str);
        $str = preg_replace('/&(\w+);/', $temp . '\\1;', $str);

        $str = str_replace(['&', '<', '>', '"', "'"], ['&amp;', '&lt;', '&gt;', '&quot;', '&apos;'], $str);

        $str = preg_replace('/' . $temp . '(\d+);/', '&#\\1;', $str);
        $str = preg_replace('/' . $temp . '(\w+);/', '&\\1;', $str);

        if ($protectAll) {
            return str_replace(['&', '<', '>', '"', "'"], ['&amp;', '&lt;', '&gt;', '&quot;', '&apos;'], $str);
        }

        return $str;
    }
}

if (!function_exists('config_item')) {
    /**
     * CI3-style accessor untuk config item via CodeIgniter config services.
     */
    function config_item(string $item)
    {
        static $cache = [];

        if (array_key_exists($item, $cache)) {
            return $cache[$item];
        }

        $value = null;

        if (strpos($item, '.') !== false) {
            [$namespace, $name] = explode('.', $item, 2);
            try {
                $cfg = config($namespace);
                if (is_object($cfg) && property_exists($cfg, $name)) {
                    $value = $cfg->{$name};
                }
            } catch (\Throwable $e) {
                $value = null;
            }
        } else {
            try {
                $cfg = config('App');
                if (is_object($cfg) && property_exists($cfg, $item)) {
                    $value = $cfg->{$item};
                }
            } catch (\Throwable $e) {
                $value = null;
            }

            if ($value === null) {
                foreach (['App', 'Database', 'Session', 'Email', 'Auth', 'Site'] as $ns) {
                    try {
                        $cfg = config($ns);
                        if (is_object($cfg) && property_exists($cfg, $item)) {
                            $value = $cfg->{$item};
                            break;
                        }
                    } catch (\Throwable $e) {
                        continue;
                    }
                }
            }
        }

        $cache[$item] = $value;
        return $value;
    }
}

if (!function_exists('_toInteger')) {
    /**
     * Cast apapun ke integer (CI3 helper).
     */
    function _toInteger($value): int
    {
        if (is_int($value)) {
            return $value;
        }
        if (is_numeric($value)) {
            return (int) $value;
        }
        if (is_string($value)) {
            return (int) preg_replace('/[^0-9\-]/', '', $value);
        }
        return 0;
    }
}

if (!function_exists('has_privilege')) {
    /**
     * Cek apakah user memiliki privilege untuk module tertentu.
     */
    function has_privilege(string $moduleUrl, ?string $action = 'read'): bool
    {
        $privileges = session('user_privileges');
        if (!is_array($privileges)) {
            return false;
        }
        if (in_array('*', $privileges, true)) {
            return true;
        }
        return in_array($moduleUrl, $privileges, true);
    }
}

if (!function_exists('user_id')) {
    function user_id()
    {
        return session('user_id');
    }
}

if (!function_exists('user_name')) {
    function user_name()
    {
        return session('user_name') ?? session('user_full_name');
    }
}

if (!function_exists('user_type')) {
    function user_type()
    {
        return session('user_type') ?? session('user_group');
    }
}

if (!function_exists('current_theme')) {
    function current_theme()
    {
        return session('theme') ?? '1';
    }
}
