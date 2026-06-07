<?php

namespace App\Controllers\Blog;

class Post_comments extends \App\Controllers\AdminController
{
    protected $pk = 'id';
    protected $table = 'comments';

    public function __construct()
    {
        $this->m_post_comments = model('App\Models\M_post_comments');
    }

    public function index()
    {
        $this->vars['title'] = 'Komentar';
        $this->vars['blog'] = $this->vars['posts'] = $this->vars['post_comments'] = true;
        $this->vars['content'] = 'blog/post_comments';
        return view('backend/index', $this->vars);
    }

    public function pagination()
    {
        if ($this->request->isAJAX()) {
            $keyword = trim($this->request->getPost('keyword'));
            $page_number = _toInteger($this->request->getPost('page_number'));
            $limit = _toInteger($this->request->getPost('per_page'));
            $offset = ($page_number * $limit);
            $query = $this->m_post_comments->get_where($keyword, 'rows', $limit, $offset);
            $total_rows = $this->m_post_comments->get_where($keyword);
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
            'comment_content' => $this->request->getPost('comment_content'),
            'comment_reply' => $this->request->getPost('comment_reply'),
            'comment_status' => $this->request->getPost('comment_status')
        ];
    }

    private function validation()
    {
        $this->validation->setRules([
            'comment_content' => ['label' => 'Komentar', 'rules' => 'trim|required'],
            'comment_status' => ['label' => 'Status', 'rules' => 'trim|required'],
        ]);
        return $this->validation->withRequest($this->request)->run();
    }
}
