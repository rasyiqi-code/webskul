<?php

namespace App\Controllers\Academic;

use App\Controllers\AdminController;

class Semester_attendance_report extends AdminController
{
    public function __construct()
    {
        $this->pk = 'id';
        $this->table = 'meeting_attendences';
    }

    public function index()
    {
        $data['title'] = 'Rekap Presensi per Semester';
        $data['academic'] = $data['student_attendance_report'] = $data['semester_attendance_report'] = true;
        $data['academic_year_dropdown'] = model('App\Models\M_academic_years')->dropdown();
        $data['class_group_dropdown'] = model('App\Models\M_class_groups')->dropdown();
        $data['content'] = 'academic/semester_attendance_report';
        return view('backend/index', $data);
    }

    public function get_semester_attendance_report()
    {
        if ($this->request->isAJAX()) {
            $academic_year_id = _toInteger($this->request->getPost('academic_year_id'));
            $semester = $this->request->getPost('semester');
            $class_group_id = _toInteger($this->request->getPost('class_group_id'));
            $this->vars['attendance'] = model('App\Models\M_meeting_attendences')->getSemesterAttendanceReport($academic_year_id, $semester, $class_group_id);
            return $this->response->setContentType('application/json')->setJSON($this->vars);
        }
    }
}
