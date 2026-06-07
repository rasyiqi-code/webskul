<?php

namespace App\Controllers\Media;

class Videos extends \App\Controllers\AdminController
{
    protected $pk = 'id';
    protected $table = 'posts';

    public function __construct()
    {
        $this->m_videos = model('App\Models\M_videos');
    }

    public function index()
    {
        $this->vars['title'] = 'Video';
        $this->vars['sub_title'] = 'Youtube Video ID diambil dari URL Youtube. Contoh : https://www.youtube.com/watch?v=<b class="text-red">ImoK3cu3nXk</b>, maka yang harus diinputkan yaitu hanya yang diberi warna merah';
        $this->vars['media'] = $this->vars['videos'] = true;
        $this->vars['content'] = 'media/videos';
        return view('backend/index', $this->vars);
    }

    public function pagination()
    {
        if ($this->request->isAJAX()) {
            $keyword = trim($this->request->getPost('keyword'));
            $page_number = _toInteger($this->request->getPost('page_number'));
            $limit = _toInteger($this->request->getPost('per_page'));
            $offset = ($page_number * $limit);
            $query = $this->m_videos->get_where($keyword, 'rows', $limit, $offset);
            $total_rows = $this->m_videos->get_where($keyword);
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
            'post_title' => $this->request->getPost('post_title'),
            'post_content' => $this->request->getPost('post_content'),
            'post_type' => 'video'
        ];
    }

    private function validation()
    {
        $this->validation->setRules([
            'post_title' => ['label' => 'Judul Video', 'rules' => 'trim|required'],
            'post_content' => ['label' => 'Youtube Video ID', 'rules' => 'trim|required'],
        ]);
        return $this->validation->withRequest($this->request)->run();
    }
}
