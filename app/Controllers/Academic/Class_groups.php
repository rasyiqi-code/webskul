<?php

namespace App\Controllers\Academic;

use App\Controllers\AdminController;

class Class_groups extends AdminController
{
    public function __construct()
    {
        $this->pk = 'id';
        $this->table = 'class_groups';
    }

    public function index()
    {
        $data['title'] = 'Kelas';
        $data['academic'] = $data['academic_references'] = $data['class_groups'] = true;
        $data['major_dropdown'] = json_encode([0 => 'Pilih'] + model('App\Models\M_majors')->dropdown());
        $data['content'] = 'academic/class_groups';
        return view('backend/index', $data);
    }

    public function pagination()
    {
        if ($this->request->isAJAX()) {
            $keyword = trim($this->request->getPost('keyword'));
            $page_number = _toInteger($this->request->getPost('page_number'));
            $limit = _toInteger($this->request->getPost('per_page'));
            $offset = ($page_number * $limit);
            $m_class_groups = model('App\Models\M_class_groups');
            $query = $m_class_groups->get_where($keyword, 'rows', $limit, $offset);
            $total_rows = $m_class_groups->get_where($keyword);
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
            'class_group' => $this->request->getPost('class_group'),
            'sub_class_group' => $this->request->getPost('sub_class_group'),
            'major_id' => _toInteger($this->request->getPost('major_id')) ? _toInteger($this->request->getPost('major_id')) : 0,
        ];
    }

    private function validation()
    {
        $this->validation->setRules([
            'class_group' => ['label' => 'Kelas', 'rules' => 'trim|required'],
            'sub_class_group' => ['label' => 'Sub Kelas', 'rules' => 'trim'],
            'major_id' => ['label' => 'Jurusan', 'rules' => 'trim'],
        ]);
        return $this->validation->withRequest($this->request)->run();
    }
}
