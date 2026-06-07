<?php

namespace App\Controllers\Teacher;

use App\Controllers\AdminController;

class Meeting_attendance_report extends AdminController
{
    public function __construct()
    {
        $this->pk = 'id';
        $this->table = 'meeting_attendences';
    }

    public function index()
    {
        $employment_type = session('employment_type');
        if ($employment_type !== null && strpos(strtolower($employment_type), 'guru') === false) {
            return redirect()->to(base_url('dashboard'));
        }
        $data['title'] = 'Rekap Presensi';
        $data['meeting_attendance_report'] = true;
        $data['academic_year_dropdown'] = model('App\Models\M_academic_years')->dropdown();
        $data['class_group_dropdown'] = model('App\Models\M_class_groups')->dropdown();
        $data['subject_dropdown'] = model('App\Models\M_subjects')->dropdown();
        $data['content'] = 'teacher/meeting_attendance_report';
        return view('backend/index', $data);
    }

    public function summary_report()
    {
        if ($this->request->isAJAX()) {
            $academic_year_id = _toInteger($this->request->getPost('academic_year_id'));
            $semester = $this->request->getPost('semester');
            $class_group_id = _toInteger($this->request->getPost('class_group_id'));
            $subject_id = _toInteger($this->request->getPost('subject_id'));
            $start_date = substr($this->request->getPost('start_date'), 0, 10);
            $end_date = substr($this->request->getPost('end_date'), 0, 10);
            if (!_isValidDate($start_date)) $start_date = date('Y-m-d');
            if (!_isValidDate($end_date)) $end_date = date('Y-m-d');
            $employee_id = (int)session('user_id');
            $course_class_id = model('App\Models\M_course_classes')->find_id($academic_year_id, $semester, $class_group_id, $subject_id, $employee_id);
            $this->vars['rows'] = _isNaturalNumber($course_class_id) ? model('App\Models\M_meeting_attendences')->get_meeting_attendance_summary_report($course_class_id, $start_date, $end_date) : [];
            return $this->response->setContentType('application/json')->setJSON($this->vars);
        }
    }

    public function detail_report()
    {
        if ($this->request->isAJAX()) {
            $academic_year_id = _toInteger($this->request->getPost('academic_year_id'));
            $semester = $this->request->getPost('semester');
            $class_group_id = _toInteger($this->request->getPost('class_group_id'));
            $subject_id = _toInteger($this->request->getPost('subject_id'));
            $start_date = substr($this->request->getPost('start_date'), 0, 10);
            $end_date = substr($this->request->getPost('end_date'), 0, 10);
            if (!_isValidDate($start_date)) $start_date = date('Y-m-d');
            if (!_isValidDate($end_date)) $end_date = date('Y-m-d');
            $employee_id = (int)session('user_id');
            $course_class_id = model('App\Models\M_course_classes')->find_id($academic_year_id, $semester, $class_group_id, $subject_id, $employee_id);
            $this->vars['dates'] = array_date($start_date, $end_date);
            $this->vars['rows'] = [];
            if (_isNaturalNumber($course_class_id)) {
                $this->vars['rows'] = model('App\Models\M_meeting_attendences')->get_meeting_attendance_detail_report($course_class_id, $start_date, $end_date);
            }
            return $this->response->setContentType('application/json')->setJSON($this->vars);
        }
    }
}
