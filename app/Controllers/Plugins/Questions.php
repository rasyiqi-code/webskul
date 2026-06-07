<?php

namespace App\Controllers\Plugins;

class Questions extends \App\Controllers\AdminController
{
    protected $pk = 'id';
    protected $table = 'questions';

    public function __construct()
    {
        $this->m_questions = model('App\Models\M_questions');
    }

    public function index()
    {
        $this->vars['title'] = 'Pertanyaan';
        $this->vars['plugins'] = $this->vars['pollings'] = $this->vars['questions'] = true;
        $this->vars['content'] = 'plugins/questions';
        return view('backend/index', $this->vars);
    }

    public function pagination()
    {
        if ($this->request->isAJAX()) {
            $keyword = trim($this->request->getPost('keyword'));
            $page_number = _toInteger($this->request->getPost('page_number'));
            $limit = _toInteger($this->request->getPost('per_page'));
            $offset = ($page_number * $limit);
            $query = $this->m_questions->get_where($keyword, 'rows', $limit, $offset);
            $total_rows = $this->m_questions->get_where($keyword);
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
            'question' => $this->request->getPost('question'),
            'is_active' => $this->request->getPost('is_active')
        ];
    }

    private function validation()
    {
        $this->validation->setRules([
            'question' => ['label' => 'Question', 'rules' => 'trim|required'],
            'is_active' => ['label' => 'Is Active', 'rules' => 'trim|required'],
        ]);
        return $this->validation->withRequest($this->request)->run();
    }
}
