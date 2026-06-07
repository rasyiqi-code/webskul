<?php

namespace App\Controllers;

use CodeIgniter\Controller;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Psr\Log\LoggerInterface;

abstract class BaseController extends Controller
{
    protected $vars = [];

    protected $validation;

    protected $helpers = ['url', 'html', 'form', 'antonsofyan', 'datasource', 'recaptcha', 'setting', 'text', 'blog'];

    public function initController(RequestInterface $request, ResponseInterface $response, LoggerInterface $logger)
    {
        parent::initController($request, $response, $logger);

        $this->loadSettings();

        $this->validation = service('validation');

        $timezone = 'Asia/Jakarta';
        $general = session('general');
        if ($general !== null && isset($general['timezone'])) {
            $timezone = $general['timezone'];
        }
        date_default_timezone_set($timezone);
    }

    private function loadSettings(): void
    {
        if (session('settings_loaded')) {
            $this->loadFormSettings();
            return;
        }

        $settings = model('App\Models\M_settings')->findAll();
        $sessionData = [];
        foreach ($settings as $row) {
            $sessionData[$row->setting_group][$row->setting_variable] = $row->setting_value ?? $row->setting_default_value;
        }

        $sessionData['theme'] = model('App\Models\M_themes')->getActiveTheme();
        $sessionData['settings_loaded'] = true;

        session()->set($sessionData);
        $this->loadFormSettings();
    }

    private function loadFormSettings(): void
    {
        if (session('form_settings_loaded')) {
            return;
        }

        $formSettings = model('App\Models\M_form_settings')->getFormSettings();
        foreach ($formSettings as $field => $fieldSetting) {
            session()->set('form_' . $field, $fieldSetting);
        }
        session()->set('form_settings_loaded', true);
    }
}
