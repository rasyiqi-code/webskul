<?php

namespace App\Controllers\Users;

class Administrator extends \App\Controllers\AdminController
{
    protected $pk = 'id';
    protected $table = 'users';

    public function __construct()
    {
        $this->m_users = model('App\Models\M_users');
        $this->m_user_groups = model('App\Models\M_user_groups');
    }

    public function index()
    {
        $this->vars['title'] = 'Pengguna Administrator';
        $this->vars['users'] = $this->vars['administrator'] = true;
        $this->vars['user_group_dropdown'] = json_encode($this->m_user_groups->dropdown());
        $this->vars['content'] = 'users/administrator';
        return view('backend/index', $this->vars);
    }

    public function pagination()
    {
        if ($this->request->isAJAX()) {
            $keyword = trim($this->request->getPost('keyword'));
            $page_number = _toInteger($this->request->getPost('page_number'));
            $limit = _toInteger($this->request->getPost('per_page'));
            $offset = ($page_number * $limit);
            $query = $this->m_users->get_where('admin', $keyword, 'rows', $limit, $offset);
            $total_rows = $this->m_users->get_where('admin', $keyword);
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
            if ($this->validation($id)) {
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
        $data = [];
        $password = $this->request->getPost('password');
        if (!empty($password)) $data['password'] = password_hash($password, PASSWORD_BCRYPT, ['cost' => 12]);
        $data['email'] = $this->request->getPost('email') ? $this->request->getPost('email') : null;
        $data['full_name'] = $this->request->getPost('full_name');
        $data['user_group_id'] = $this->request->getPost('user_group_id') ? $this->request->getPost('user_group_id') : 0;
        $data['is_admin'] = 'true';
        return $data;
    }

    private function validation($id = 0)
    {
        $rules = [];
        if (_isNaturalNumber($id)) {
            $rules['password'] = ['label' => 'Password', 'rules' => 'trim|min_length[6]'];
        } else {
            $rules['password'] = ['label' => 'Password', 'rules' => 'trim|required|min_length[6]'];
        }
        $rules['email'] = ['label' => 'Email', 'rules' => 'trim|required|valid_email'];
        $rules['full_name'] = ['label' => 'Full Name', 'rules' => 'trim|required'];
        $rules['user_group_id'] = ['label' => 'Group', 'rules' => 'trim|required'];
        $this->validation->setRules($rules);
        return $this->validation->withRequest($this->request)->run();
    }

    public function email_exists($email = '', $id = 0)
    {
        $email_exists = $this->m_users->email_exists($email, $id);
        if ($email_exists) {
            $this->validation->setRule('email', 'Email', 'is_unique[users.email]');
            return false;
        }
        return true;
    }
}
