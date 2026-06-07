<?php

namespace App\Controllers\Academic;

use App\Controllers\AdminController;

class Daily_attendance_report extends AdminController
{
    public function __construct()
    {
        $this->pk = 'id';
        $this->table = 'meeting_attendences';
    }

    public function index()
    {
        $data['title'] = 'Rekap Presensi Per Hari';
        $data['academic'] = $data['student_attendance_report'] = $data['daily_attendance_report'] = true;
        $data['academic_year_dropdown'] = model('App\Models\M_academic_years')->dropdown();
        $data['class_group_dropdown'] = model('App\Models\M_class_groups')->dropdown();
        $data['content'] = 'academic/daily_attendance_report';
        return view('backend/index', $data);
    }

    public function get_daily_attendance_report()
    {
        if ($this->request->isAJAX()) {
            $academic_year_id = _toInteger($this->request->getPost('academic_year_id'));
            $semester = $this->request->getPost('semester');
            $class_group_id = _toInteger($this->request->getPost('class_group_id'));
            $start_date = substr($this->request->getPost('start_date'), 0, 10);
            $end_date = substr($this->request->getPost('end_date'), 0, 10);
            if (!_isValidDate($start_date)) $start_date = date('Y-m-d');
            if (!_isValidDate($end_date)) $end_date = date('Y-m-d');
            $this->vars['dates'] = array_date($start_date, $end_date);
            $this->vars['attendance'] = model('App\Models\M_meeting_attendences')->getDailyAttendanceReport($academic_year_id, $semester, $class_group_id, $start_date, $end_date);
            return $this->response->setContentType('application/json')->setJSON($this->vars);
        }
    }
}
