<?php

namespace App\Controllers\Academic;

use App\Controllers\AdminController;

class Student_groups extends AdminController
{
    public function __construct()
    {
        $this->pk = 'id';
        $this->table = 'student_groups';
    }

    public function index()
    {
        $data['title'] = 'Rombongan Belajar';
        $data['academic'] = $data['student_groups'] = true;
        $data['content'] = 'academic/student_groups';
        return view('backend/index', $data);
    }

    public function pagination()
    {
        if ($this->request->isAJAX()) {
            $keyword = trim($this->request->getPost('keyword'));
            $page_number = _toInteger($this->request->getPost('page_number'));
            $limit = _toInteger($this->request->getPost('per_page'));
            $offset = ($page_number * $limit);
            $m_student_groups = model('App\Models\M_student_groups');
            $query = $m_student_groups->get_where($keyword, 'rows', $limit, $offset);
            $total_rows = $m_student_groups->get_where($keyword);
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
                    $dataset['updated_by'] = session('user_id');
                    $this->vars['status'] = model('App\Models\M_student_groups')->set_class_president($id, $dataset) ? 'success' : 'error';
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
            'is_class_manager' => $this->request->getPost('is_class_manager'),
        ];
    }

    private function validation()
    {
        $this->validation->setRules([
            'is_class_manager' => ['label' => 'Ketua Kelas', 'rules' => 'trim|required|in_list[true,false]'],
        ]);
        return $this->validation->withRequest($this->request)->run();
    }
}
