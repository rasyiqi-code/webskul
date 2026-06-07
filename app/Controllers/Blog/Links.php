<?php

namespace App\Controllers\Blog;

class Links extends \App\Controllers\AdminController
{
    protected $pk = 'id';
    protected $table = 'links';

    public function __construct()
    {
        $this->m_links = model('App\Models\M_links');
    }

    public function index()
    {
        $this->vars['title'] = 'Tautan';
        $this->vars['blog'] = $this->vars['links'] = true;
        $this->vars['content'] = 'blog/links';
        return view('backend/index', $this->vars);
    }

    public function pagination()
    {
        if ($this->request->isAJAX()) {
            $keyword = trim($this->request->getPost('keyword'));
            $page_number = _toInteger($this->request->getPost('page_number'));
            $limit = _toInteger($this->request->getPost('per_page'));
            $offset = ($page_number * $limit);
            $query = $this->m_links->get_where($keyword, 'rows', $limit, $offset);
            $total_rows = $this->m_links->get_where($keyword);
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
            'link_type' => 'link',
            'link_url' => prep_url($this->request->getPost('link_url')),
            'link_title' => $this->request->getPost('link_title'),
            'link_target' => $this->request->getPost('link_target')
        ];
    }

    private function validation()
    {
        $this->validation->setRules([
            'link_url' => ['label' => 'URL', 'rules' => 'trim|required|valid_url'],
            'link_title' => ['label' => 'Description', 'rules' => 'trim|required'],
            'link_target' => ['label' => 'Target', 'rules' => 'trim|required'],
        ]);
        return $this->validation->withRequest($this->request)->run();
    }
}
