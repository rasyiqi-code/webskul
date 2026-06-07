<?php

namespace App\Controllers\Admission;

use App\Controllers\AdminController;

class Subject_semester_report_settings extends AdminController
{
    public function __construct()
    {
        $this->pk = 'id';
        $this->table = 'admission_subject_settings';
    }

    public function index()
    {
        $data['title'] = 'Pengaturan Nilai Rapor Sekolah';
        $data['admission'] = $data['admission_settings'] = $data['subject_semester_report_settings'] = true;
        $majors = [0 => 'Unset'];
        if (session('major_count') > 0) {
            $majors = [0 => 'Unset'] + model('App\Models\M_majors')->dropdown();
        }
        $data['major_dropdown'] = json_encode($majors);
        $data['academic_year_dropdown'] = json_encode(model('App\Models\M_academic_years')->dropdown());
        $data['content'] = 'admission/subject_semester_report_settings';
        return view('backend/index', $data);
    }

    public function pagination()
    {
        if ($this->request->isAJAX()) {
            $keyword = trim($this->request->getPost('keyword'));
            $page_number = _toInteger($this->request->getPost('page_number'));
            $limit = _toInteger($this->request->getPost('per_page'));
            $offset = ($page_number * $limit);
            $m_admission_subject_settings = model('App\Models\M_admission_subject_settings');
            $query = $m_admission_subject_settings->get_where($keyword, 'semester_report', 'rows', $limit, $offset);
            $total_rows = $m_admission_subject_settings->get_where($keyword, 'semester_report');
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
            'admission_type_id' => _toInteger($this->request->getPost('admission_type_id')),
            'major_id' => _toInteger($this->request->getPost('major_id')) ? _toInteger($this->request->getPost('major_id')) : 0,
            'subject_type' => 'semester_report',
        ];
    }

    private function validation()
    {
        $rules = [
            'academic_year_id' => ['label' => 'Tahun Pelajaran', 'rules' => 'trim|required|is_natural_no_zero'],
            'admission_type_id' => ['label' => 'Jenis Pendaftaran', 'rules' => 'trim|required|is_natural_no_zero'],
        ];
        if (session('major_count') > 0) {
            $rules['major_id'] = ['label' => 'Jurusan', 'rules' => 'trim|required'];
        }
        $this->validation->setRules($rules);
        return $this->validation->withRequest($this->request)->run();
    }
}
