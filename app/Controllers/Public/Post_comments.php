<?php

namespace App\Controllers\Public;

use App\Controllers\PublicController;

class Post_comments extends PublicController
{
    public function index()
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
            $comment_moderation = session('discussion')['comment_moderation'] ?? 'false';
            $db->table('comments')->insert([
                'comment_author' => $this->request->getPost('comment_author'),
                'comment_email' => $this->request->getPost('comment_email'),
                'comment_url' => $this->request->getPost('comment_url'),
                'comment_content' => $this->request->getPost('comment_content'),
                'comment_type' => 'post',
                'comment_post_id' => _toInteger($this->request->getPost('comment_post_id')),
                'comment_status' => filter_var($comment_moderation, FILTER_VALIDATE_BOOLEAN) ? 'unapproved' : 'approved',
                'comment_ip_address' => get_ip_address(),
                'comment_agent' => $this->request->getUserAgent(),
                'created_by' => session('user_id'),
            ]);
            $query = $db->affectedRows() > 0;
            $status = $query ? 'success' : 'error';
            $message = $query ? 'Komentar anda sudah tersimpan.' : 'Komentar anda tidak tersimpan.';
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
            'comment_author' => ['label' => 'Nama Lengkap', 'rules' => 'trim|required|alpha_numeric_spaces'],
            'comment_email' => ['label' => 'Email', 'rules' => 'trim|required|valid_email'],
            'comment_url' => ['label' => 'URL', 'rules' => 'trim|valid_url'],
            'comment_content' => ['label' => 'Komentar', 'rules' => 'trim|required|alpha_numeric_spaces'],
            'comment_post_id' => ['label' => 'ID Post', 'rules' => 'trim|required|numeric'],
        ]);
        return $this->validation->withRequest($this->request)->run();
    }

    public function get_post_comments()
    {
        if (!$this->request->isAJAX()) {
            return $this->response->setStatusCode(404);
        }

        $post_id = _toInteger($this->request->getPost('comment_post_id'));
        $page_number = _toInteger($this->request->getPost('page_number'));
        $reading = session('reading');
        $comment_per_page = (int)($reading['comment_per_page'] ?? 5);
        $offset = ($page_number - 1) * $comment_per_page;

        $comments = [];
        if ($post_id > 0) {
            $m_post_comments = model('App\Models\M_post_comments');
            $query = $m_post_comments->getPostComments($post_id, $comment_per_page, $offset);
            $comments = $query->getResult();
        }

        return $this->response->setContentType('application/json')->setJSON([
            'comments' => $comments
        ]);
    }
}
