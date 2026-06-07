<?php

namespace App\Controllers\Blog;

class Opening_speech extends \App\Controllers\AdminController
{
    protected $pk = 'id';
    protected $table = 'posts';

    public function __construct()
    {
        $this->m_posts = model('App\Models\M_posts');
    }

    public function index()
    {
        $this->vars['title'] = 'Sambutan Kepala Sekolah';
        $this->vars['blog'] = $this->vars['opening_speech'] = true;
        $this->vars['query'] = $this->m_posts->getOpeningSpeech();
        $this->vars['content'] = 'blog/opening_speech';
        return view('backend/index', $this->vars);
    }

    public function save()
    {
        if ($this->request->isAJAX()) {
            if ($this->validation()) {
                $dataset = $this->dataset();
                $dataset['updated_by'] = session('user_id');
                $this->vars['status'] = $this->m_posts->opening_speech_update($dataset) ? 'success' : 'error';
                $this->vars['message'] = $this->vars['status'] == 'success' ? 'updated' : 'not_updated';
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
            'post_content' => $this->request->getPost('post_content'),
            'post_type' => 'opening_speech'
        ];
    }

    private function validation()
    {
        $this->validation->setRules([
            'post_content' => ['label' => 'Sambutan Kepala Sekolah', 'rules' => 'trim|required'],
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
