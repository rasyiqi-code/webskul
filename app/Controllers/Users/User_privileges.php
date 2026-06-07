<?php

namespace App\Controllers\Users;

class User_privileges extends \App\Controllers\AdminController
{
    protected $pk = 'id';
    protected $table = 'user_privileges';

    public function __construct()
    {
        $this->m_user_groups = model('App\Models\M_user_groups');
        $this->m_modules = model('App\Models\M_modules');
        $this->m_user_privileges = model('App\Models\M_user_privileges');
    }

    public function index()
    {
        $this->vars['title'] = 'Hak Akses';
        $this->vars['sub_title'] = 'Hak akses modul untuk administrator';
        $this->vars['users'] = $this->vars['user_privileges'] = true;
        $this->vars['user_group_dropdown'] = json_encode($this->m_user_groups->dropdown());
        $this->vars['module_dropdown'] = json_encode($this->m_modules->dropdown());
        $this->vars['content'] = 'users/user_privileges';
        return view('backend/index', $this->vars);
    }

    public function pagination()
    {
        if ($this->request->isAJAX()) {
            $keyword = trim($this->request->getPost('keyword'));
            $page_number = _toInteger($this->request->getPost('page_number'));
            $limit = _toInteger($this->request->getPost('per_page'));
            $offset = ($page_number * $limit);
            $query = $this->m_user_privileges->get_where($keyword, 'rows', $limit, $offset);
            $total_rows = $this->m_user_privileges->get_where($keyword);
            $total_page = $limit > 0 ? ceil(_toInteger($total_rows) / _toInteger($limit)) : 1;
            $this->vars['total_page'] = _toInteger($total_page);
            $this->vars['total_rows'] = _toInteger($total_rows);
            $this->vars['rows'] = is_array($query) || $query instanceof \CodeIgniter\Database\ResultInterface ? $query->getResult() : $query;
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
            'user_group_id' => _toInteger($this->request->getPost('user_group_id')),
            'module_id' => _toInteger($this->request->getPost('module_id'))
        ];
    }

    private function validation()
    {
        $this->validation->setRules([
            'user_group_id' => ['label' => 'Grup Pengguna', 'rules' => 'trim|required|numeric'],
            'module_id' => ['label' => 'Modul', 'rules' => 'trim|required|numeric'],
        ]);
        return $this->validation->withRequest($this->request)->run();
    }
}
