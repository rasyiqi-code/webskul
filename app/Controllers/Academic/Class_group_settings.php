<?php

namespace App\Controllers\Academic;

use App\Controllers\AdminController;

class Class_group_settings extends AdminController
{
    public function __construct()
    {
        $this->pk = 'id';
        $this->table = 'class_group_settings';
    }

    public function index()
    {
        $data['title'] = 'Pengaturan Wali Kelas';
        $data['academic'] = $data['academic_settings'] = $data['class_group_settings'] = true;
        $data['academic_year_dropdown'] = json_encode(model('App\Models\M_academic_years')->dropdown());
        $data['class_group_dropdown'] = json_encode(model('App\Models\M_class_groups')->dropdown());
        $data['employee_dropdown'] = json_encode(model('App\Models\M_employees')->dropdown());
        $data['content'] = 'academic/class_group_settings';
        return view('backend/index', $data);
    }

    public function pagination()
    {
        if ($this->request->isAJAX()) {
            $keyword = trim($this->request->getPost('keyword'));
            $page_number = _toInteger($this->request->getPost('page_number'));
            $limit = _toInteger($this->request->getPost('per_page'));
            $offset = ($page_number * $limit);
            $m_class_group_settings = model('App\Models\M_class_group_settings');
            $query = $m_class_group_settings->get_where($keyword, 'rows', $limit, $offset);
            $total_rows = $m_class_group_settings->get_where($keyword);
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
                $query = model('App\Models\M_helper')->upsert($id, $this->table, $dataset);
                $this->vars['status'] = $query ? 'success' : 'error';
                $this->vars['message'] = $query ? 'Data Anda berhasil disimpan.' : 'Terjadi kesalahan dalam menyimpan data';
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
            'academic_year_id' => _toInteger($this->request->getPost('academic_year_id')),
            'class_group_id' => _toInteger($this->request->getPost('class_group_id')),
            'employee_id' => _toInteger($this->request->getPost('employee_id')),
        ];
    }

    private function validation()
    {
        $this->validation->setRules([
            'academic_year_id' => ['label' => 'Tahun Pelajaran', 'rules' => 'trim|required'],
            'class_group_id' => ['label' => 'Kelas', 'rules' => 'trim|required'],
            'employee_id' => ['label' => 'Wali Kelas', 'rules' => 'trim|required'],
        ]);
        return $this->validation->withRequest($this->request)->run();
    }
}
