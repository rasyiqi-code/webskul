<?php

namespace App\Controllers\Teacher;

use App\Controllers\AdminController;

class Academic_schedules extends AdminController
{
    public function __construct()
    {
        $this->pk = 'id';
        $this->table = 'course_classes';
    }

    public function index()
    {
        $employment_type = session('employment_type');
        if ($employment_type !== null && strpos(strtolower($employment_type), 'guru') === false) {
            return redirect()->to(base_url('dashboard'));
        }
        $data['title'] = 'Jadwal Mengajar';
        $data['sub_title'] = 'Untuk masuk kelas, silahkan klik tombol dengan icon <i class="fa fa-edit"></i>';
        $data['academic_schedules'] = true;
        $data['current_semester_id'] = session('current_academic_year_id');
        $data['content'] = 'teacher/academic_schedules';
        return view('backend/index', $data);
    }

    public function pagination()
    {
        if ($this->request->isAJAX()) {
            $keyword = trim($this->request->getPost('keyword'));
            $page_number = _toInteger($this->request->getPost('page_number'));
            $limit = _toInteger($this->request->getPost('per_page'));
            $offset = ($page_number * $limit);
            $m_academic_schedules = model('App\Models\M_academic_schedules');
            $query = $m_academic_schedules->get_where($keyword, 'rows', $limit, $offset);
            $total_rows = $m_academic_schedules->get_where($keyword);
            $total_page = $limit > 0 ? ceil(_toInteger($total_rows) / _toInteger($limit)) : 1;
            $this->vars['total_page'] = _toInteger($total_page);
            $this->vars['total_rows'] = _toInteger($total_rows);
            $this->vars['rows'] = $query->getResult();
            return $this->response->setContentType('application/json')->setJSON($this->vars);
        }
    }
}
