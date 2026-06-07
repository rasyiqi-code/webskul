<?php

use CodeIgniter\I18n\Time;

if (!function_exists('_isNaturalNumber')) {
    function _isNaturalNumber($n) {
        return ($n != 0 && ctype_digit((string) $n));
    }
}

if (!function_exists('_toInteger')) {
    function _toInteger($n) {
        return abs(intval(strval($n)));
    }
}

if (!function_exists('copyright')) {
    function copyright($year = '', $link = '', $company_name = '') {
        if (!_isValidYear($year)) return;
        $str = 'Copyright &copy; ';
        $str .= date('Y') > $year ? $year . '-' . date('Y') : $year;
        $str .= '<a href="';
        $str .= $link == '' ? base_url() : $link;
        $str .= '" target="_blank"> ';
        $str .= $company_name == '' ? str_replace(['http://', 'https://', 'www.'], '', rtrim(base_url(), '/')) : $company_name;
        $str .= '</a>';
        return $str;
    }
}

if (!function_exists('filesize_formatted')) {
    function filesize_formatted($size) {
        $units = ['B', 'KB', 'MB', 'GB', 'TB', 'PB', 'EB', 'ZB', 'YB'];
        $power = $size > 0 ? floor(log($size, 1024)) : 0;
        return number_format($size / pow(1024, $power), 2, '.', ',') . ' ' . $units[$power];
    }
}

if (!function_exists('create_dir')) {
    function create_dir($dir) {
        if (!is_dir($dir)) {
            if (!mkdir($dir, 0777, true)) {
                die('Tidak dapat membuat folder: ' . $dir);
            }
        }
    }
}

if (!function_exists('extract_themes')) {
    function extract_themes() {
        $zip = new ZipArchive;
        $zip->open('./views/themes/default.zip');
        @chmod(FCPATH . 'views/themes', 0775);
        $zip->extractTo('./views/themes/');
        @chmod(FCPATH . 'views/themes/default/', 0775);
        $zip->close();
    }
}

if (!function_exists('get_options')) {
    function get_options($option_group = '', $encode = true) {
        $model = model('App\Models\M_options');
        $options = $model->getOptions($option_group);
        if ($encode) return json_encode($options, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_AMP | JSON_HEX_QUOT | JSON_THROW_ON_ERROR | JSON_UNESCAPED_SLASHES);
        return $options;
    }
}

if (!function_exists('get_option_id')) {
    function get_option_id($option_group = '', $option_name = '') {
        $model = model('App\Models\M_options');
        return _toInteger($model->getOptionId($option_group, $option_name));
    }
}

if (!function_exists('__session')) {
    function __session($session_key) {
        return session($session_key);
    }
}

if (!function_exists('encode_str')) {
    function encode_str($str) {
        $encrypter = service('encrypter');
        $ret = bin2hex($encrypter->encrypt($str));
        $ret = strtr($ret, ['+' => '.', '=' => '-', '/' => '~']);
        return $ret;
    }
}

if (!function_exists('decode_str')) {
    function decode_str($str) {
        $encrypter = service('encrypter');
        $str = strtr($str, ['.' => '+', '-' => '=', '~' => '/']);
        return $encrypter->decrypt(hex2bin($str));
    }
}

if (!function_exists('indo_date')) {
    function indo_date($date) {
        if (_isValidDate($date)) {
            $parts = explode("-", $date);
            return $parts[2] . ' ' . bulan($parts[1]) . ' ' . $parts[0];
        }
        return '';
    }
}

if (!function_exists('english_date')) {
    function english_date($date) {
        if (_isValidDate($date)) {
            $parts = explode("-", $date);
            return $parts[2] . ', ' . month($parts[1]) . ' ' . $parts[0];
        }
        return '';
    }
}

if (!function_exists('day_name')) {
    function day_name($idx) {
        $arr = ['', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jum\'at', 'Sabtu', 'Minggu'];
        return $arr[$idx];
    }
}

if (!function_exists('_isValidDate')) {
    function _isValidDate($date = '') {
        if (!empty($date)) {
            if (preg_match("/^([0-9]{4})-([0-9]{2})-([0-9]{2})$/", $date, $parts)) {
                return checkdate($parts[2], $parts[3], $parts[1]);
            }
        }
        return false;
    }
}

if (!function_exists('_isValidYear')) {
    function _isValidYear($year) {
        if (_toInteger($year) < 1000 || _toInteger($year) > 9999) return false;
        return true;
    }
}

if (!function_exists('_isValidMonth')) {
    function _isValidMonth($month) {
        if (_toInteger($month) < 1 || _toInteger($month) > 12) return false;
        return true;
    }
}

if (!function_exists('bulan')) {
    function bulan($key = '') {
        $data = [
            '01' => 'Januari', '02' => 'Februari', '03' => 'Maret',
            '04' => 'April', '05' => 'Mei', '06' => 'Juni',
            '07' => 'Juli', '08' => 'Agustus', '09' => 'September',
            '10' => 'Oktober', '11' => 'November', '12' => 'Desember',
        ];
        return $key === '' ? $data : $data[$key];
    }
}

if (!function_exists('month')) {
    function month($key = '') {
        $data = [
            '01' => 'January', '02' => 'February', '03' => 'March',
            '04' => 'April', '05' => 'May', '06' => 'June',
            '07' => 'July', '08' => 'August', '09' => 'September',
            '10' => 'October', '11' => 'November', '12' => 'December',
        ];
        return $key === '' ? $data : $data[$key];
    }
}

if (!function_exists('get_ip_address')) {
    function get_ip_address() {
        $request = service('request');
        return $request->getIPAddress();
    }
}

if (!function_exists('check_internet_connection')) {
    function check_internet_connection() {
        return checkdnsrr('google.com');
    }
}

if (!function_exists('array_date')) {
    function array_date($start_date, $end_date) {
        if (!_isValidDate($start_date)) return [];
        if (!_isValidDate($end_date)) return [];
        $start_date = strtotime($start_date);
        $end_date = strtotime($end_date);
        if ($start_date > $end_date) return array_date($end_date, $start_date);
        $dates = [];
        do {
            $dates[] = date('Y-m-d', $start_date);
            $start_date = strtotime("+ 1 day", $start_date);
        } while ($start_date <= $end_date);
        return $dates;
    }
}

if (!function_exists('delete_cache')) {
    function delete_cache() {
        // Di CI4, cache disimpan di writable/cache (WRITEPATH), bukan APPPATH.'cache'
        $path  = WRITEPATH . 'cache';
        $files = directory_map($path, false, true);
        foreach ($files as $file) {
            if ($file !== 'index.html' && $file !== '.htaccess') {
                @chmod($path . '/' . $file, 0777);
                @unlink($path . '/' . $file);
            }
        }
    }
}

if (!function_exists('alpha_dash')) {
    function alpha_dash($str) {
        return (!preg_match("/^([-a-z0-9_])+$/i", $str)) ? false : true;
    }
}

if (!function_exists('slugify')) {
    function slugify($str) {
        $lettersNumbersSpacesHyphens = '/[^\-\s\pN\pL]+/u';
        $spacesDuplicateHypens = '/[\-\s]+/';
        $str = preg_replace($lettersNumbersSpacesHyphens, '', $str);
        $str = preg_replace($spacesDuplicateHypens, '-', $str);
        $str = trim($str, '-');
        return strtolower($str);
    }
}

if (!function_exists('timezone_list')) {
    function timezone_list() {
        static $regions = [DateTimeZone::ASIA];
        $timezones = [];
        foreach ($regions as $region) {
            $timezones = array_merge($timezones, DateTimeZone::listIdentifiers($region));
        }
        $timezone_offsets = [];
        foreach ($timezones as $timezone) {
            $tz = new DateTimeZone($timezone);
            $timezone_offsets[$timezone] = $tz->getOffset(new DateTime);
        }
        asort($timezone_offsets);
        $timezone_list = [];
        foreach ($timezone_offsets as $timezone => $offset) {
            $offset_prefix = $offset < 0 ? '-' : '+';
            $offset_formatted = gmdate('H:i', abs($offset));
            $pretty_offset = "UTC{$offset_prefix}{$offset_formatted}";
            $timezone_list[$timezone] = "({$pretty_offset}) {$timezone}";
        }
        return $timezone_list;
    }
}

if (!function_exists('subject_desc')) {
    function subject_desc($key = '') {
        $subject = [
            'semester_report' => 'Nilai Rapor Sekolah',
            'national_exam' => 'Nilai Ujian Nasional',
            'exam_schedule' => 'Nilai Tes Tulis'
        ];
        return !empty($key) ? $subject[$key] : '';
    }
}

if (!function_exists('presence')) {
    function presence($str) {
        if ($str == 'present') return 'H';
        if ($str == 'sick') return 'S';
        if ($str == 'permit') return 'I';
        if ($str == 'absent') return 'A';
        return '';
    }
}

if (!function_exists('__captchaActivated')) {
    function __captchaActivated() {
        $general = __session('general');
        return $general !== null && isset($general['recaptcha_status']) && $general['recaptcha_status'] == 'enable';
    }
}

if (!function_exists('dd')) {
    function dd(): void {
        echo '<pre>';
        array_map(function ($args) {
            var_dump($args);
        }, func_get_args());
        die();
    }
}
