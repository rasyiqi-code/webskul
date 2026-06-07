<?php

namespace App\Controllers\Users;

class Employees extends \App\Controllers\AdminController
{
    protected $pk = 'id';
    protected $table = 'users';

    public function __construct()
    {
        $this->m_users = model('App\Models\M_users');
    }

    public function index()
    {
        $this->vars['title'] = 'Guru dan Tenaga Kependidikan';
        $this->vars['users'] = $this->vars['user_employees'] = true;
        $this->vars['content'] = 'users/employees';
        return view('backend/index', $this->vars);
    }

    public function pagination()
    {
        if ($this->request->isAJAX()) {
            $keyword = trim($this->request->getPost('keyword'));
            $page_number = _toInteger($this->request->getPost('page_number'));
            $limit = _toInteger($this->request->getPost('per_page'));
            $offset = ($page_number * $limit);
            $query = $this->m_users->get_where('employee', $keyword, 'rows', $limit, $offset);
            $total_rows = $this->m_users->get_where('employee', $keyword);
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
            'password' => password_hash($this->request->getPost('password'), PASSWORD_BCRYPT, ['cost' => 12])
        ];
    }

    private function validation($id = 0)
    {
        $this->validation->setRules([
            'password' => ['label' => 'Kata Sandi', 'rules' => 'trim|required|min_length[6]'],
            'password_confirm' => ['label' => 'Kata Sandi', 'rules' => 'trim|required|matches[password]'],
        ]);
        return $this->validation->withRequest($this->request)->run();
    }
}
