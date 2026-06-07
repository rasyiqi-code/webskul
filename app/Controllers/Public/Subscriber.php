<?php

namespace App\Controllers\Public;

use App\Controllers\PublicController;
use App\Libraries\Token;

class Subscriber extends PublicController
{
    public function index()
    {
        if (!$this->request->isAJAX()) {
            return $this->response->setStatusCode(404);
        }

        $token = new Token();
        $csrf_token = $this->request->getPost('csrf_token');

        if (!$csrf_token || !$token->is_valid_token($csrf_token)) {
            return $this->response->setContentType('application/json')->setJSON([
                'status' => 'token_error',
                'csrf_token' => $token->get_token(),
            ]);
        }

        if ($this->validation()) {
            $email = $this->request->getPost('subscriber');
            $db = db_connect();
            $builder = $db->table('subscribers');
            $compiled = $builder->set('email', $email)
                ->set('created_by', session('user_id'))
                ->getCompiledInsert();
            $compiled = str_replace('INSERT INTO', 'INSERT IGNORE INTO', $compiled);
            $db->query($compiled);

            $status = 'success';
            $message = 'Email anda sudah tersimpan';
        } else {
            $status = 'error';
            $message = $this->validation->getErrors();
        }

        return $this->response->setContentType('application/json')->setJSON([
            'status' => $status,
            'message' => $message,
            'csrf_token' => $token->get_token(),
        ]);
    }

    private function validation()
    {
        $this->validation->setRules([
            'subscriber' => ['label' => 'Email', 'rules' => 'trim|required|valid_email'],
        ]);
        return $this->validation->withRequest($this->request)->run();
    }
}
