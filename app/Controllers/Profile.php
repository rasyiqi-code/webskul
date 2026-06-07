<?php

namespace App\Controllers;

class Profile extends AdminController
{
    protected $pk = 'id';
    protected $table = 'users';

    public function __construct()
    {
        $this->m_users = model('App\Models\M_users');
    }

    public function index()
    {
        $id = _toInteger(session('user_id'));
        $this->vars['title'] = 'Ubah Profil';
        $this->vars['user_profile'] = true;
        $this->vars['query'] = model('App\Models\M_helper')->rowObject($this->pk, $id, $this->table);
        $this->vars['content'] = 'users/profile';
        return view('backend/index', $this->vars);
    }

    public function save()
    {
        if ($this->request->isAJAX()) {
            $id = _toInteger(session('user_id'));
            if (_isNaturalNumber($id)) {
                if ($this->validation($id)) {
                    $dataset = $this->dataset();
                    $this->vars['status'] = model('App\Models\M_helper')->updateData($id, $this->table, $dataset) ? 'success' : 'error';
                    $this->vars['message'] = $this->vars['status'] == 'success' ? 'updated' : 'not_updated';
                } else {
                    $this->vars['status'] = 'error';
                    $this->vars['message'] = $this->validation->getErrors();
                }
            } else {
                $this->vars['status'] = 'error';
                $this->vars['message'] = 'not_updated';
            }
            return $this->response->setContentType('application/json')->setJSON($this->vars);
        }
    }

    private function dataset()
    {
        return [
            'full_name' => $this->request->getPost('full_name'),
            'email' => $this->request->getPost('email')
        ];
    }

    private function validation($id)
    {
        $this->validation->setRules([
            'full_name' => ['label' => 'Full Name', 'rules' => 'trim|required'],
            'email' => ['label' => 'Email', 'rules' => 'trim|required|valid_email'],
        ]);
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
