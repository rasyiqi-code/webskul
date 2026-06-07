<?php

namespace App\Controllers\Blog;

class Post_categories extends \App\Controllers\AdminController
{
    protected $pk = 'id';
    protected $table = 'categories';

    public function __construct()
    {
        $this->m_post_categories = model('App\Models\M_post_categories');
    }

    public function index()
    {
        $this->vars['title'] = 'Kategori Tulisan';
        $this->vars['blog'] = $this->vars['posts'] = $this->vars['post_categories'] = true;
        $this->vars['content'] = 'blog/post_categories';
        return view('backend/index', $this->vars);
    }

    public function pagination()
    {
        if ($this->request->isAJAX()) {
            $keyword = trim($this->request->getPost('keyword'));
            $page_number = _toInteger($this->request->getPost('page_number'));
            $limit = _toInteger($this->request->getPost('per_page'));
            $offset = ($page_number * $limit);
            $query = $this->m_post_categories->get_where($keyword, 'rows', $limit, $offset);
            $total_rows = $this->m_post_categories->get_where($keyword);
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
                    $this->vars['status'] = model('App\Models\M_helper')->updateData($id, $this->table, $dataset) ? 'success' : 'error';
                    $this->vars['message'] = $this->vars['status'] == 'success' ? 'updated' : 'not_updated';
                } else {
                    $dataset['created_by'] = session('user_id');
                    $this->vars['insert_id'] = $this->m_post_categories->insertCategory($dataset);
                    $this->vars['status'] = $this->vars['insert_id'] > 0 ? 'success' : 'error';
                    $this->vars['message'] = $this->vars['status'] == 'success' ? 'created' : 'not_created';
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
            'category_type' => 'post',
            'category_name' => $this->request->getPost('category_name'),
            'category_description' => $this->request->getPost('category_description'),
            'category_slug' => slugify($this->request->getPost('category_name'))
        ];
    }

    private function validation()
    {
        $this->validation->setRules([
            'category_name' => ['label' => 'Kategori Tulisan', 'rules' => 'trim|required'],
            'category_description' => ['label' => 'Keterangan', 'rules' => 'trim'],
        ]);
        return $this->validation->withRequest($this->request)->run();
    }
}
