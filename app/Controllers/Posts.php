<?php

namespace App\Controllers;

class Posts extends AdminController
{
    protected $pk    = 'id';
    protected $table = 'posts';

    /** @var \App\Models\M_posts */
    protected $mPosts;

    /** @var \App\Models\M_post_categories */
    protected $mPostCategories;

    public function initController($request, $response, $logger)
    {
        parent::initController($request, $response, $logger);
        $this->mPosts          = model('App\Models\M_posts');
        $this->mPostCategories = model('App\Models\M_post_categories');
    }

    public function index()
    {
        $this->vars['title'] = 'Tulisan';
        $this->vars['posts'] = true;
        $this->vars['content'] = 'posts_read';
        return view('backend/index', $this->vars);
    }

    public function create()
    {
        helper('form');
        $id = _toInteger(service('uri')->getSegment(3));
        if (_isNaturalNumber($id)) {
            $this->vars['query'] = model('App\Models\M_helper')->rowObject($this->pk, $id, $this->table);
            $post_image = 'media_library/posts/medium/' . $this->vars['query']->post_image;
            $this->vars['post_image'] = file_exists(FCPATH . $post_image) ? base_url($post_image) : '';
            if (_toInteger($this->vars['query']->post_author) !== _toInteger(session('user_id'))) {
                return redirect()->to('dashboard');
            }
            if ($this->vars['query']->post_status === 'publish') {
                return redirect()->to('dashboard');
            }
        } else {
            $this->vars['query'] = false;
        }
        $this->vars['option_categories'] = $this->mPostCategories->getPostCategories();
        $this->vars['title'] = _isNaturalNumber($id) ? 'Edit Tulisan' : 'Tambah Tulisan';
        $this->vars['posts'] = true;
        $this->vars['action'] = site_url('posts/save/' . $id);
        $this->vars['content'] = 'posts_create';
        return view('backend/index', $this->vars);
    }

    public function pagination()
    {
        if ($this->request->isAJAX()) {
            $keyword = trim($this->request->getPost('keyword'));
            $page_number = _toInteger($this->request->getPost('page_number'));
            $limit = _toInteger($this->request->getPost('per_page'));
            $offset = ($page_number * $limit);
            $query = $this->mPosts->get_where($keyword, 'rows', $limit, $offset);
            $total_rows = $this->mPosts->get_where($keyword);
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
            $id = _toInteger(service('uri')->getSegment(3));
            if ($this->validation()) {
                $dataset = $this->dataset();
                $error_message = '';
                $file = $this->request->getFile('post_image');
                if ($file && $file->isValid() && !$file->hasMoved()) {
                    $upload = $this->upload_image($id);
                    if ($upload['status'] == 'success') {
                        $dataset['post_image'] = $upload['file_name'];
                    } else {
                        $error_message = $upload['message'];
                    }
                }
                if (!empty($error_message)) {
                    $this->vars['status'] = 'error';
                    $this->vars['message'] = $error_message;
                } else {
                    if (_isNaturalNumber($id)) unset($dataset['post_author']);
                    $query = model('App\Models\M_helper')->upsert($id, $this->table, $dataset);
                    $this->vars['action'] = _isNaturalNumber($id) ? 'update' : 'insert';
                    $this->vars['status'] = $query ? 'success' : 'error';
                    $this->vars['message'] = $query ? 'Data Anda berhasil disimpan.' : 'Terjadi kesalahan dalam menyimpan data';
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
            'post_title' => $this->request->getPost('post_title'),
            'post_content' => $this->request->getPost('post_content'),
            'post_author' => session('user_id'),
            'post_categories' => $this->request->getPost('post_categories'),
            'post_type' => 'post',
            'post_slug' => slugify($this->request->getPost('post_title'))
        ];
    }

    private function validation()
    {
        $this->validation->setRules([
            'post_title' => ['label' => 'Title', 'rules' => 'trim|required'],
            'post_content' => ['label' => 'Content', 'rules' => 'trim|required'],
        ]);
        return $this->validation->withRequest($this->request)->run();
    }

    private function upload_image($id)
    {
        $file = $this->request->getFile('post_image');
        if ($file && $file->isValid() && !$file->hasMoved()) {
            $newName = $file->getRandomName();
            $file->move(FCPATH . 'media_library/images/', $newName);
            @chmod(FCPATH . 'media_library/images/' . $newName, 0777);
            $this->image_resize(FCPATH . 'media_library/images', $newName);
            $this->vars['status'] = 'success';
            $this->vars['file_name'] = $newName;
            if (_isNaturalNumber($id)) {
                $query = model('App\Models\M_helper')->rowObject($this->pk, $id, $this->table);
                @chmod(FCPATH . 'media_library/posts/thumbnail/' . $query->post_image, 0777);
                @chmod(FCPATH . 'media_library/posts/medium/' . $query->post_image, 0777);
                @chmod(FCPATH . 'media_library/posts/large/' . $query->post_image, 0777);
                @unlink(FCPATH . 'media_library/posts/thumbnail/' . $query->post_image);
                @unlink(FCPATH . 'media_library/posts/medium/' . $query->post_image);
                @unlink(FCPATH . 'media_library/posts/large/' . $query->post_image);
            }
        } else {
            $this->vars['status'] = 'error';
            $this->vars['message'] = $file ? $file->getErrorString() : 'No file uploaded';
        }
        return $this->vars;
    }

    private function image_resize($path, $file_name)
    {
        $writing = session('writing');
        $image = \Config\Services::image()
            ->withFile($path . '/' . $file_name)
            ->resize((int)($writing['post_image_thumbnail_width'] ?? 150), (int)($writing['post_image_thumbnail_height'] ?? 150), false);
        $image->save(FCPATH . 'media_library/posts/thumbnail/' . $file_name);
        $image->withFile($path . '/' . $file_name)
            ->resize((int)($writing['post_image_medium_width'] ?? 300), (int)($writing['post_image_medium_height'] ?? 300), false);
        $image->save(FCPATH . 'media_library/posts/medium/' . $file_name);
        $image->withFile($path . '/' . $file_name)
            ->resize((int)($writing['post_image_large_width'] ?? 600), (int)($writing['post_image_large_height'] ?? 600), false);
        $image->save(FCPATH . 'media_library/posts/large/' . $file_name);
        @unlink($path . '/' . $file_name);
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
