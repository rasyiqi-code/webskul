<?php

namespace App\Controllers\Academic;

use App\Controllers\AdminController;

class Transportations extends AdminController
{
    public function __construct()
    {
        $this->pk = 'id';
        $this->table = 'options';
    }

    public function index()
    {
        $data['title'] = 'Moda Transportasi';
        $data['academic'] = $data['academic_references'] = $data['transportations'] = true;
        $data['content'] = 'academic/transportations';
        return view('backend/index', $data);
    }

    public function pagination()
    {
        if ($this->request->isAJAX()) {
            $keyword = trim($this->request->getPost('keyword'));
            $page_number = _toInteger($this->request->getPost('page_number'));
            $limit = _toInteger($this->request->getPost('per_page'));
            $offset = ($page_number * $limit);
            $m_options = model('App\Models\M_options');
            $query = $m_options->get_where($keyword, 'transportation', 'rows', $limit, $offset);
            $total_rows = $m_options->get_where($keyword, 'transportation');
            $total_page = $limit > 0 ? ceil(_toInteger($total_rows) / _toInteger($limit)) : 1;
            $this->vars['total_page'] = _toInteger($total_page);
            $this->vars['total_rows'] = _toInteger($total_rows);
            $this->vars['rows'] = $query->getResult();
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
            'option_group' => 'transportation',
            'option_name' => $this->request->getPost('option_name'),
        ];
    }

    private function validation()
    {
        $this->validation->setRules([
            'option_name' => ['label' => 'Moda Transportasi', 'rules' => 'trim|required'],
        ]);
        return $this->validation->withRequest($this->request)->run();
    }
}
