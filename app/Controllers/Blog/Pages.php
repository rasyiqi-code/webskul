<?php

namespace App\Controllers\Blog;

class Pages extends \App\Controllers\AdminController
{
    protected $pk = 'id';
    protected $table = 'posts';

    public function __construct()
    {
        $this->m_pages = model('App\Models\M_pages');
    }

    public function index()
    {
        $this->vars['title'] = 'Halaman';
        $this->vars['blog'] = $this->vars['pages'] = true;
        $this->vars['content'] = 'blog/pages_read';
        return view('backend/index', $this->vars);
    }

    public function create()
    {
        helper('form');
        $id = _toInteger(service('uri')->getSegment(4));
        $this->vars['query'] = _isNaturalNumber($id) ? model('App\Models\M_helper')->rowObject($this->pk, $id, $this->table) : false;
        $this->vars['title'] = _isNaturalNumber($id) ? 'Edit Halaman' : 'Tambah Halaman';
        $this->vars['blog'] = $this->vars['pages'] = true;
        $this->vars['action'] = site_url('blog/pages/save/' . $id);
        $this->vars['content'] = 'blog/pages_create';
        return view('backend/index', $this->vars);
    }

    public function pagination()
    {
        if ($this->request->isAJAX()) {
            $keyword = trim($this->request->getPost('keyword'));
            $page_number = _toInteger($this->request->getPost('page_number'));
            $limit = _toInteger($this->request->getPost('per_page'));
            $offset = ($page_number * $limit);
            $query = $this->m_pages->get_where($keyword, 'rows', $limit, $offset);
            $total_rows = $this->m_pages->get_where($keyword);
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
            $id = _toInteger(service('uri')->getSegment(4));
            if ($this->validation()) {
                $dataset = $this->dataset();
                if (_isNaturalNumber($id)) unset($dataset['post_author']);
                $query = model('App\Models\M_helper')->upsert($id, $this->table, $dataset);
                $this->vars['action'] = _isNaturalNumber($id) ? 'update' : 'insert';
                $this->vars['status'] = $query ? 'success' : 'error';
                $this->vars['message'] = $query ? 'Data Anda berhasil disimpan.' : 'Terjadi kesalahan dalam menyimpan data';
            } else {
                $this->vars['status'] = 'error';
                $this->vars['message'] = $this->validation->getErrors();
            }
            return $this->response->setContentType('application/json')->setJSON($this->vars);
        }
    }

    public function set_created_at()
    {
        if ($this->request->isAJAX()) {
            $id = _toInteger($this->request->getPost('id'));
            if (_isNaturalNumber($id)) {
                db_connect()->table($this->table)
                    ->set('created_at', $this->request->getPost('created_at'))
                    ->set('updated_by', session('user_id'))
                    ->where($this->pk, $id)
                    ->update();
                $this->vars['status'] = 'success';
                $this->vars['message'] = 'updated';
                return $this->response->setContentType('application/json')->setJSON($this->vars);
            }
        }
    }

    private function dataset()
    {
        return [
            'post_title' => $this->request->getPost('post_title'),
            'post_content' => $this->request->getPost('post_content'),
            'post_author' => session('user_id'),
            'post_type' => 'page',
            'post_status' => $this->request->getPost('post_status'),
            'post_visibility' => $this->request->getPost('post_visibility'),
            'post_comment_status' => $this->request->getPost('post_comment_status'),
            'post_slug' => slugify($this->request->getPost('post_title'))
        ];
    }

    private function validation()
    {
        $this->validation->setRules([
            'post_title' => ['label' => 'Judul', 'rules' => 'trim|required'],
            'post_content' => ['label' => 'Konten', 'rules' => 'trim|required'],
            'post_status' => ['label' => 'Status', 'rules' => 'trim|required|in_list[publish,draft]'],
            'post_visibility' => ['label' => 'Visibilitas', 'rules' => 'trim|required|in_list[public,private]'],
            'post_comment_status' => ['label' => 'Komentar', 'rules' => 'trim|required|in_list[open,close]'],
        ]);
        return $this->validation->withRequest($this->request)->run();
    }

    public function images_upload_handler()
    {
        $file = $this->request->getFile('file');
        if ($file && $file->isValid() && !$file->hasMoved()) {
            $newName = $file->getRandomName();
            $file->move(FCPATH . 'media_library/posts/', $newName);
            $this->vars['status'] = 'success';
            $this->vars['location'] = base_url('media_library/posts/' . $newName);
        } else {
            $this->vars['status'] = 'error';
            $this->vars['message'] = $file ? $file->getErrorString() : 'No file uploaded';
        }
        return $this->response->setContentType('application/json')->setJSON($this->vars);
    }
}
