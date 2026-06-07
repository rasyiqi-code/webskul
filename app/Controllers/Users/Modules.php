<?php

namespace App\Controllers\Users;

class Modules extends \App\Controllers\AdminController
{
    protected $pk = 'id';
    protected $table = 'modules';

    public function __construct()
    {
        $this->m_modules = model('App\Models\M_modules');
    }

    public function index()
    {
        $this->vars['title'] = 'Daftar Modul';
        $this->vars['users'] = $this->vars['modules'] = true;
        $this->vars['content'] = 'users/modules';
        return view('backend/index', $this->vars);
    }

    public function pagination()
    {
        if ($this->request->isAJAX()) {
            $keyword = trim($this->request->getPost('keyword'));
            $page_number = _toInteger($this->request->getPost('page_number'));
            $limit = _toInteger($this->request->getPost('per_page'));
            $offset = ($page_number * $limit);
            $query = $this->m_modules->get_where($keyword, 'rows', $limit, $offset);
            $total_rows = $this->m_modules->get_where($keyword);
            $total_page = $limit > 0 ? ceil(_toInteger($total_rows) / _toInteger($limit)) : 1;
            $this->vars['total_page'] = _toInteger($total_page);
            $this->vars['total_rows'] = _toInteger($total_rows);
            $this->vars['rows'] = $query;
            return $this->response->setContentType('application/json')->setJSON($this->vars);
        }
    }

    public function save()
    {
        if ($this->request->isAJAX()) {
            $id = _toInteger($this->request->getPost('id'));
            if ($this->validation()) {
                $dataset = $this->dataset();
                $query = model('App\Models\M_helper')->updateData($id, $this->table, $dataset);
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
            'module_name' => $this->request->getPost('module_name'),
            'module_description' => $this->request->getPost('module_description')
        ];
    }

    private function validation()
    {
        $this->validation->setRules([
            'module_name' => ['label' => 'Nama Modul', 'rules' => 'trim|required'],
            'module_description' => ['label' => 'Keterangan', 'rules' => 'trim'],
        ]);
        return $this->validation->withRequest($this->request)->run();
    }
}
