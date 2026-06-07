<?php

namespace App\Controllers\Academic;

use App\Controllers\AdminController;

class Student_status extends AdminController
{
    public function __construct()
    {
        $this->pk = 'id';
        $this->table = 'options';
    }

    public function index()
    {
        $data['title'] = 'Status Peserta Didik';
        $data['academic'] = $data['academic_references'] = $data['student_status'] = true;
        $data['content'] = 'academic/student_status';
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
            $query = $m_options->get_where($keyword, 'student_status', 'rows', $limit, $offset);
            $total_rows = $m_options->get_where($keyword, 'student_status');
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
                if (_isNaturalNumber($id)) {
                    $query = model('App\Models\M_helper')->rowObject($this->pk, $id, $this->table);
                    if (!in_array(strtolower($query->option_name), ['lulus', 'aktif'])) {
                        $this->vars['status'] = model('App\Models\M_helper')->updateData($id, $this->table, $dataset) ? 'success' : 'error';
                        $this->vars['message'] = $this->vars['status'] == 'success' ? 'updated' : 'not_updated';
                    } else {
                        $this->vars['status'] = 'info';
                        $this->vars['message'] = 'Status peserta didik Lulus dan Aktif tidak dapat diubah !';
                    }
                } else {
                    if (!in_array(strtolower($dataset['option_name']), ['lulus', 'aktif'])) {
                        $this->vars['status'] = model('App\Models\M_helper')->insertData($this->table, $dataset) ? 'success' : 'error';
                        $this->vars['message'] = $this->vars['status'] == 'success' ? 'created' : 'not_created';
                    } else {
                        $this->vars['status'] = 'info';
                        $this->vars['message'] = 'Status peserta didik ' . strtoupper($dataset['option_name']) . ' sudah ada !';
                    }
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
            'option_group' => 'student_status',
            'option_name' => $this->request->getPost('option_name'),
        ];
    }

    private function validation()
    {
        $this->validation->setRules([
            'option_name' => ['label' => 'Status Peserta Didik', 'rules' => 'trim|required'],
        ]);
        return $this->validation->withRequest($this->request)->run();
    }
}
