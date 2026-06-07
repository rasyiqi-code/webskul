<?php

if (!function_exists('get_recapture_score')) {
    function get_recapture_score($secret) {
        $url = 'https://www.google.com/recaptcha/api/siteverify';
        $general = __session('general');
        $req = $url . '?secret=' . ($general['recaptcha_secret_key'] ?? '') . '&response=' . $secret . '';
        $res = file_get_contents($req);
        $res = json_decode($res);
        if ($res->success == false) {
            foreach ($res->{'error-codes'} as $code) {
                switch ($code) {
                    case 'missing-input-secret':
                        log_message('error', 'RECAPTCHA: The secret parameter is missing. ' . $req);
                        break;
                    case 'invalid-input-secret':
                        log_message('error', 'RECAPTCHA: The secret parameter is invalid or malformed. ' . $req);
                        break;
                    case 'missing-input-response':
                        log_message('error', 'RECAPTCHA: The response parameter is missing. ' . $req);
                        break;
                    case 'invalid-input-response':
                        log_message('error', 'RECAPTCHA: The response parameter is invalid or malformed. ' . $req);
                        break;
                    case 'bad-request':
                        log_message('error', 'RECAPTCHA: The request is invalid or malformed. ' . $req);
                        break;
                    case 'timeout-or-duplicate':
                        log_message('error', 'RECAPTCHA: The response is no longer valid: either is too old or has been used previously.');
                        break;
                    default:
                        log_message('error', 'RECAPTCHA: Unknown error');
                }
            }
            return 0;
        }
        return $res->score;
    }
}
