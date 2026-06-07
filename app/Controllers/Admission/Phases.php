<?php

namespace App\Controllers\Admission;

use App\Controllers\AdminController;

class Phases extends AdminController
{
    public function __construct()
    {
        $this->pk = 'id';
        $this->table = 'admission_phases';
    }

    public function index()
    {
        $data['title'] = 'Gelombang Pendaftaran';
        $data['admission'] = $data['admission_settings'] = $data['admission_phases'] = true;
        $data['academic_year_dropdown'] = json_encode(model('App\Models\M_academic_years')->dropdown());
        $data['content'] = 'admission/phases';
        return view('backend/index', $data);
    }

    public function pagination()
    {
        if ($this->request->isAJAX()) {
            $keyword = trim($this->request->getPost('keyword'));
            $page_number = _toInteger($this->request->getPost('page_number'));
            $limit = _toInteger($this->request->getPost('per_page'));
            $offset = ($page_number * $limit);
            $m_admission_phases = model('App\Models\M_admission_phases');
            $query = $m_admission_phases->get_where($keyword, 'rows', $limit, $offset);
            $total_rows = $m_admission_phases->get_where($keyword);
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
            'academic_year_id' => _toInteger($this->request->getPost('academic_year_id')),
            'phase_name' => $this->request->getPost('phase_name'),
            'phase_start_date' => $this->request->getPost('phase_start_date'),
            'phase_end_date' => $this->request->getPost('phase_end_date'),
        ];
    }

    private function validation()
    {
        $this->validation->setRules([
            'academic_year_id' => ['label' => 'Tahun Pelajaran', 'rules' => 'trim|is_natural_no_zero|required'],
            'phase_name' => ['label' => 'Gelombang Pendaftaran', 'rules' => 'trim|required'],
            'phase_start_date' => ['label' => 'Start Date', 'rules' => 'trim|required|min_length[10]|max_length[10]'],
            'phase_end_date' => ['label' => 'End Date', 'rules' => 'trim|required|min_length[10]|max_length[10]'],
        ]);
        return $this->validation->withRequest($this->request)->run();
    }
}
