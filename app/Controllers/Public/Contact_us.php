<?php

namespace App\Controllers\Public;

use App\Controllers\PublicController;

class Contact_us extends PublicController
{
    public function index()
    {
        $data['page_title'] = 'Hubungi Kami';
        $data['content'] = 'themes/' . theme_folder() . '/contact-us';
        return view('themes/' . theme_folder() . '/index', $data);
    }

    public function save()
    {
        if (!$this->request->isAJAX()) {
            return $this->response->setStatusCode(404);
        }

        if (__captchaActivated()) {
            $score = get_recapture_score($this->request->getPost('g-recaptcha-response'));
            if ($score < 0.9) {
                return $this->response->setContentType('application/json')->setJSON([
                    'status' => 'recaptcha_error',
                    'message' => 'Recaptcha Error!'
                ]);
            }
        }

        if ($this->validation()) {
            $db = db_connect();
            $ip = $this->request->getIPAddress();
            $url = $this->request->getPost('comment_url');
            if ($url && !preg_match('#^https?://#i', $url)) {
                $url = 'https://' . $url;
            }
            $db->table('comments')->insert([
                'comment_author' => strip_tags($this->request->getPost('comment_author')),
                'comment_email' => strip_tags($this->request->getPost('comment_email')),
                'comment_url' => $url,
                'comment_content' => strip_tags($this->request->getPost('comment_content')),
                'comment_type' => 'message',
                'comment_ip_address' => $ip,
                'comment_agent' => $this->request->getUserAgent(),
                'created_by' => session('user_id'),
            ]);
            $query = $db->affectedRows() > 0;
            $status = $query ? 'success' : 'error';
            $message = $query ? 'Pesan anda sudah tersimpan.' : 'Pesan anda tidak tersimpan.';
        } else {
            $status = 'validation_errors';
            $message = $this->validation->getErrors();
        }

        return $this->response->setContentType('application/json')->setJSON([
            'status' => $status,
            'message' => $message
        ]);
    }

    private function validation()
    {
        $this->validation->setRules([
            'comment_author' => ['label' => 'Nama Lengkap', 'rules' => 'trim|required|alpha_numeric_space'],
            'comment_email' => ['label' => 'Email', 'rules' => 'trim|required|valid_email'],
            'comment_url' => ['label' => 'URL', 'rules' => 'permit_empty|trim|valid_url'],
            'comment_content' => ['label' => 'Komentar', 'rules' => 'trim|required|alpha_numeric_space'],
        ]);
        return $this->validation->withRequest($this->request)->run();
    }
}
