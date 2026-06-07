<?php

namespace App\Controllers\Academic;

use App\Controllers\AdminController;

class Student_attendance_report extends AdminController
{
    public function __construct()
    {
        $this->pk = 'id';
        $this->table = 'meeting_attendences';
    }

    public function index()
    {
        $data['title'] = 'Rekap Presensi Keseluruhan';
        $data['academic'] = $data['student_attendance_report'] = $data['all_attendance_report'] = true;
        $data['content'] = 'academic/student_attendance_report';
        return view('backend/index', $data);
    }

    public function pagination()
    {
        if ($this->request->isAJAX()) {
            $keyword = trim($this->request->getPost('keyword'));
            $page_number = _toInteger($this->request->getPost('page_number'));
            $limit = _toInteger($this->request->getPost('per_page'));
            $offset = ($page_number * $limit);
            $m_meeting_attendences = model('App\Models\M_meeting_attendences');
            $query = $m_meeting_attendences->get_where($keyword, 'rows', $limit, $offset);
            $total_rows = $m_meeting_attendences->get_where($keyword);
            $total_page = $limit > 0 ? ceil(_toInteger($total_rows) / _toInteger($limit)) : 1;
            $this->vars['total_page'] = _toInteger($total_page);
            $this->vars['total_rows'] = _toInteger($total_rows);
            $this->vars['rows'] = $query->getResult();
            return $this->response->setContentType('application/json')->setJSON($this->vars);
        }
    }

    public function save()
    {
        if ($this->request->isAJAX()) {
            $id = _toInteger($this->request->getPost('id'));
            if ($this->validation()) {
                $dataset = $this->dataset();
                if (_isNaturalNumber($id)) {
                    $this->vars['status'] = model('App\Models\M_helper')->updateData($id, $this->table, $dataset) ? 'success' : 'error';
                    $this->vars['message'] = $this->vars['status'] == 'success' ? 'updated' : 'not_updated';
                }
            } else {
                $this->vars['status'] = 'error';
                $this->vars['message'] = $this->validation->getErrors();
            }
            return $this->response->setContentType('application/json')->setJSON($this->vars);
        }
    }

    private function dataset()
    {
        return [
            'presence' => $this->request->getPost('presence'),
        ];
    }

    private function validation()
    {
        $this->validation->setRules([
            'presence' => ['label' => 'Presensi', 'rules' => 'trim|required|in_list[present,sick,absent,permit]'],
        ]);
        return $this->validation->withRequest($this->request)->run();
    }
}
