<?php

namespace App\Controllers\Blog;

class Subscribers extends \App\Controllers\AdminController
{
    protected $pk = 'id';
    protected $table = 'subscribers';

    public function __construct()
    {
        $this->m_subscribers = model('App\Models\M_subscribers');
    }

    public function index()
    {
        $this->vars['title'] = 'Subscriber';
        $this->vars['blog'] = $this->vars['subscribers'] = true;
        $this->vars['content'] = 'blog/subscribers';
        return view('backend/index', $this->vars);
    }

    public function pagination()
    {
        if ($this->request->isAJAX()) {
            $keyword = trim($this->request->getPost('keyword'));
            $page_number = _toInteger($this->request->getPost('page_number'));
            $limit = _toInteger($this->request->getPost('per_page'));
            $offset = ($page_number * $limit);
            $query = $this->m_subscribers->get_where($keyword, 'rows', $limit, $offset);
            $total_rows = $this->m_subscribers->get_where($keyword);
            $total_page = $limit > 0 ? ceil(_toInteger($total_rows) / _toInteger($limit)) : 1;
            $this->vars['total_page'] = _toInteger($total_page);
            $this->vars['total_rows'] = _toInteger($total_rows);
            $this->vars['rows'] = is_array($query) || $query instanceof \CodeIgniter\Database\ResultInterface ? $query->getResult() : $query;
            return $this->response->setContentType('application/json')->setJSON($this->vars);
        }
    }
}
