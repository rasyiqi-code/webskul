<?php

namespace App\Controllers;

class Student_presence extends AdminController
{
    public function __construct()
    {
        $this->m_meeting_attendences = model('App\Models\M_meeting_attendences');
    }

    public function index()
    {
        $current_academic_year = session('current_academic_year');
        $current_academic_semester = session('current_academic_semester') == 'odd' ? 'Ganjil' : 'Genap';
        $this->vars['title'] = 'Presensi ' . $current_academic_year . ' / ' . $current_academic_semester;
        $this->vars['student_presence'] = true;
        $this->vars['content'] = 'student_presence';
        return view('backend/index', $this->vars);
    }

    public function pagination()
    {
        if ($this->request->isAJAX()) {
            $keyword = trim($this->request->getPost('keyword'));
            $page_number = _toInteger($this->request->getPost('page_number'));
            $limit = _toInteger($this->request->getPost('per_page'));
            $offset = ($page_number * $limit);
            $query = $this->m_meeting_attendences->student_presence_report($keyword, 'rows', $limit, $offset);
            $total_rows = $this->m_meeting_attendences->student_presence_report($keyword);
            $total_page = $limit > 0 ? ceil(_toInteger($total_rows) / _toInteger($limit)) : 1;
            $this->vars['total_page'] = _toInteger($total_page);
            $this->vars['total_rows'] = _toInteger($total_rows);
            $this->vars['rows'] = $query;
            return $this->response->setContentType('application/json')->setJSON($this->vars);
        }
    }
}
