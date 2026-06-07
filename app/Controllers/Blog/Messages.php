<?php

namespace App\Controllers\Blog;

class Messages extends \App\Controllers\AdminController
{
    protected $pk = 'id';
    protected $table = 'comments';

    public function __construct()
    {
        $this->m_messages = model('App\Models\M_messages');
    }

    public function index()
    {
        $this->vars['title'] = 'Pesan Masuk';
        $this->vars['blog'] = $this->vars['messages'] = true;
        $this->vars['content'] = 'blog/messages';
        return view('backend/index', $this->vars);
    }

    public function pagination()
    {
        if ($this->request->isAJAX()) {
            $keyword = trim($this->request->getPost('keyword'));
            $page_number = _toInteger($this->request->getPost('page_number'));
            $limit = _toInteger($this->request->getPost('per_page'));
            $offset = ($page_number * $limit);
            $query = $this->m_messages->get_where($keyword, 'rows', $limit, $offset);
            $total_rows = $this->m_messages->get_where($keyword);
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
                if (_isNaturalNumber($id)) {
                    $dataset['updated_by'] = session('user_id');
                    $this->vars['status'] = $this->m_messages->reply($id, $dataset) == 202 ? 'success' : 'error';
                    $this->vars['message'] = $this->vars['status'] == 'success' ? 'email_send' : 'email_not_send';
                } else {
                    $this->vars['status'] = 'error';
                    $this->vars['message'] = 'forbidden';
                }
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
            'comment_subject' => $this->request->getPost('comment_subject'),
            'comment_reply' => $this->request->getPost('comment_reply')
        ];
    }

    private function validation()
    {
        $this->validation->setRules([
            'comment_subject' => ['label' => 'Subject', 'rules' => 'trim|required'],
            'comment_reply' => ['label' => 'Reply', 'rules' => 'trim|required'],
        ]);
        return $this->validation->withRequest($this->request)->run();
    }
}
