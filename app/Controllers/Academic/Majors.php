<?php

namespace App\Controllers\Academic;

use App\Controllers\AdminController;

class Majors extends AdminController
{
    public function __construct()
    {
        $this->pk = 'id';
        $this->table = 'majors';
    }

    public function index()
    {
        $data['title'] = 'Jurusan';
        $data['academic'] = $data['academic_references'] = $data['majors'] = true;
        $data['content'] = 'academic/majors';
        return view('backend/index', $data);
    }

    public function pagination()
    {
        if ($this->request->isAJAX()) {
            $keyword = trim($this->request->getPost('keyword'));
            $page_number = _toInteger($this->request->getPost('page_number'));
            $limit = _toInteger($this->request->getPost('per_page'));
            $offset = ($page_number * $limit);
            $m_majors = model('App\Models\M_majors');
            $query = $m_majors->get_where($keyword, 'rows', $limit, $offset);
            $total_rows = $m_majors->get_where($keyword);
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
            'major_name' => $this->request->getPost('major_name'),
            'major_short_name' => $this->request->getPost('major_short_name'),
            'is_active' => $this->request->getPost('is_active'),
        ];
    }

    private function validation()
    {
        $this->validation->setRules([
            'major_name' => ['label' => 'Jurusan', 'rules' => 'trim|required'],
            'major_short_name' => ['label' => 'Nama Singkat', 'rules' => 'trim|required'],
            'is_active' => ['label' => 'Tampil di PPDB', 'rules' => 'trim|required|in_list[true,false]'],
        ]);
        return $this->validation->withRequest($this->request)->run();
    }
}
