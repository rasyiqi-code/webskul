<?php

namespace App\Controllers;

class Change_password extends AdminController
{
    public function index()
    {
        $this->vars['title'] = 'Ubah Kata Sandi';
        $this->vars['change_password'] = true;
        $this->vars['content'] = 'change_password';
        return view('backend/index', $this->vars);
    }

    public function save()
    {
        if ($this->request->isAJAX()) {
            $id = _toInteger(session('user_id'));
            if (_isNaturalNumber($id)) {
                if ($this->validation()) {
                    $query = model('App\Models\M_helper')->rowObject('id', $id, 'users');
                    if (password_verify($this->request->getPost('current_password'), $query->password)) {
                        $dataset = $this->dataset();
                        $this->vars['status'] = model('App\Models\M_helper')->updateData($id, 'users', $dataset) ? 'success' : 'error';
                        $this->vars['message'] = $this->vars['status'] == 'success' ? 'updated' : 'not_updated';
                    } else {
                        $this->vars['status'] = 'error';
                        $this->vars['message'] = 'not_updated';
                    }
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
            'password' => password_hash($this->request->getPost('new_password'), PASSWORD_BCRYPT, ['cost' => 12])
        ];
    }

    private function validation()
    {
        $this->validation->setRules([
            'current_password' => ['label' => 'Kata Sandi Saat Ini', 'rules' => 'trim|required'],
            'new_password' => ['label' => 'Kata Sandi Baru', 'rules' => 'trim|required'],
            'retype_new_password' => ['label' => 'Ulangi Kata Sandi Baru', 'rules' => 'trim|required|matches[new_password]'],
        ]);
        return $this->validation->withRequest($this->request)->run();
    }
}
