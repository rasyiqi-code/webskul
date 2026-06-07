<?php

namespace App\Controllers;

class Scholarships extends AdminController
{
    protected $pk    = 'id';
    protected $table = 'scholarships';

    /** @var \App\Models\M_scholarships */
    protected $mScholarships;

    public function initController($request, $response, $logger)
    {
        parent::initController($request, $response, $logger);
        $this->mScholarships = model('App\Models\M_scholarships');
    }

    public function index()
    {
        $this->vars['title'] = 'BEASISWA';
        $this->vars['scholarships'] = true;
        $this->vars['content'] = 'scholarships';
        return view('backend/index', $this->vars);
    }

    public function pagination()
    {
        if ($this->request->isAJAX()) {
            $keyword = trim($this->request->getPost('keyword'));
            $page_number = _toInteger($this->request->getPost('page_number'));
            $limit = _toInteger($this->request->getPost('per_page'));
            $offset = ($page_number * $limit);
            $query = $this->mScholarships->get_where($keyword, 'rows', $limit, $offset);
            $total_rows = $this->mScholarships->get_where($keyword);
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
                    $query = model('App\Models\M_helper')->rowObject($this->pk, $id, $this->table);
                    if ($query->student_id == session('user_id')) {
                        $this->vars['status'] = model('App\Models\M_helper')->updateData($id, $this->table, $dataset) ? 'success' : 'error';
                        $this->vars['message'] = $this->vars['status'] == 'success' ? 'updated' : 'not_updated';
                    } else {
                        $this->vars['status'] = 'error';
                        $this->vars['message'] = 'forbidden';
                    }
                } else {
                    $dataset['created_by'] = session('user_id');
                    $this->vars['status'] = model('App\Models\M_helper')->insertData($this->table, $dataset) ? 'success' : 'error';
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
            'student_id' => (int)session('user_id'),
            'scholarship_description' => $this->request->getPost('scholarship_description'),
            'scholarship_type' => $this->request->getPost('scholarship_type'),
            'scholarship_start_year' => $this->request->getPost('scholarship_start_year'),
            'scholarship_end_year' => $this->request->getPost('scholarship_end_year')
        ];
    }

    private function validation()
    {
        $this->validation->setRules([
            'scholarship_type' => ['label' => 'Jenis Beasiswa', 'rules' => 'trim|required'],
            'scholarship_description' => ['label' => 'Nama Beasiswa', 'rules' => 'trim|required'],
            'scholarship_start_year' => ['label' => 'Tahun Mulai', 'rules' => 'trim|numeric|required|min_length[4]|max_length[4]'],
            'scholarship_end_year' => ['label' => 'Tahun Selesai', 'rules' => 'trim|numeric|required|min_length[4]|max_length[4]'],
        ]);
        return $this->validation->withRequest($this->request)->run();
    }
}
