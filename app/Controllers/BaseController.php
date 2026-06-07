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

    protected $helpers = ['url', 'html', 'form', 'core', 'datasource', 'recaptcha', 'setting', 'text', 'blog', 'ci3'];

    public function initController(RequestInterface $request, ResponseInterface $response, LoggerInterface $logger)
    {
        helper($this->helpers);

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
            $this->loadAcademicSession();
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
        $this->loadAcademicSession();
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

    /**
     * Load academic year, admission phase, dan major count ke session.
     * Pengganti hook Trigger.php milik CI3.
     */
    private function loadAcademicSession(): void
    {
        if (session('academic_session_loaded')) {
            return;
        }

        $academicYear = model('App\Models\M_academic_years')->getActiveAcademicYear();
        $sessionData = [];

        if (isset($academicYear['admission_semester_id'])) {
            $sessionData['admission_semester_id'] = $academicYear['admission_semester_id'];
        }
        if (isset($academicYear['admission_semester'])) {
            $sessionData['admission_semester'] = $academicYear['admission_semester'];
        }
        if (isset($academicYear['admission_year'])) {
            $sessionData['admission_year'] = $academicYear['admission_year'];
        }
        if (isset($academicYear['current_academic_year_id'])) {
            $sessionData['current_academic_year_id'] = $academicYear['current_academic_year_id'];
        }
        if (isset($academicYear['current_academic_year'])) {
            $sessionData['current_academic_year'] = $academicYear['current_academic_year'];
        }
        if (isset($academicYear['current_academic_semester'])) {
            $sessionData['current_academic_semester'] = $academicYear['current_academic_semester'];
        }

        $admissionPhase = model('App\Models\M_admission_phases')->getCurrentPhase();
        if (!empty($admissionPhase)) {
            $sessionData['admission_phase_id']   = $admissionPhase['id'];
            $sessionData['admission_phase']      = $admissionPhase['phase_name'];
            $sessionData['admission_start_date'] = $admissionPhase['phase_start_date'];
            $sessionData['admission_end_date']   = $admissionPhase['phase_end_date'];
        }

        $sessionData['major_count'] = model('App\Models\M_majors')->majorCount();
        $sessionData['academic_session_loaded'] = true;

        session()->set($sessionData);
    }
}
