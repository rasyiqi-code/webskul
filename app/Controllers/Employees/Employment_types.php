<?php

namespace App\Controllers\Employees;

use App\Controllers\AdminController;

class Employment_types extends AdminController
{
    public function __construct()
    {
        $this->pk = 'id';
        $this->table = 'options';
    }

    public function index()
    {
        $data['title'] = 'Jenis Guru dan Tenaga Kependidikan';
        $data['employees'] = $data['employment_types'] = true;
        $data['content'] = 'employees/employment_types';
        return view('backend/index', $data);
    }

    public function pagination()
    {
        if ($this->request->isAJAX()) {
            $keyword = trim($this->request->getPost('keyword'));
            $page_number = _toInteger($this->request->getPost('page_number'));
            $limit = _toInteger($this->request->getPost('per_page'));
            $offset = ($page_number * $limit);
            $m_options = model('App\Models\M_options');
            $query = $m_options->get_where($keyword, 'employment_type', 'rows', $limit, $offset);
            $total_rows = $m_options->get_where($keyword, 'employment_type');
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
            'option_group' => 'employment_type',
            'option_name' => $this->request->getPost('option_name'),
        ];
    }

    private function validation()
    {
        $this->validation->setRules([
            'option_name' => ['label' => 'Jenis Guru dan Tenaga Kependidikan', 'rules' => 'trim|required'],
        ]);
        return $this->validation->withRequest($this->request)->run();
    }
}
