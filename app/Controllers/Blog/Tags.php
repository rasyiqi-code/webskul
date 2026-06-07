<?php

namespace App\Controllers\Blog;

class Tags extends \App\Controllers\AdminController
{
    protected $pk = 'id';
    protected $table = 'tags';

    public function __construct()
    {
        $this->m_tags = model('App\Models\M_tags');
    }

    public function index()
    {
        $this->vars['title'] = 'Tags';
        $this->vars['blog'] = $this->vars['posts'] = $this->vars['tags'] = true;
        $this->vars['content'] = 'blog/tags';
        return view('backend/index', $this->vars);
    }

    public function pagination()
    {
        if ($this->request->isAJAX()) {
            $keyword = trim($this->request->getPost('keyword'));
            $page_number = _toInteger($this->request->getPost('page_number'));
            $limit = _toInteger($this->request->getPost('per_page'));
            $offset = ($page_number * $limit);
            $query = $this->m_tags->get_where($keyword, 'rows', $limit, $offset);
            $total_rows = $this->m_tags->get_where($keyword);
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
            'tag' => $this->request->getPost('tag'),
            'slug' => slugify($this->request->getPost('tag'))
        ];
    }

    private function validation()
    {
        $this->validation->setRules([
            'tag' => ['label' => 'tag', 'rules' => 'trim|required'],
        ]);
        return $this->validation->withRequest($this->request)->run();
    }

    public function autocomplete()
    {
        if ($this->request->isAJAX()) {
            $term = $this->request->getGet('term');
            $query = db_connect()->table('tags')
                ->select('tag')
                ->like('tag', $term)
                ->get();
            $tags = [];
            foreach ($query->getResult() as $row) {
                $tags[] = $row->tag;
            }
            return $this->response->setContentType('application/json')->setJSON($tags);
        }
    }
}
