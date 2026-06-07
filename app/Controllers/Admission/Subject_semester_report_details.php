<?php

namespace App\Controllers\Admission;

use App\Controllers\AdminController;

class Subject_semester_report_details extends AdminController
{
    public function __construct()
    {
        $this->pk = 'id';
        $this->table = 'admission_subject_setting_details';
    }

    public function index()
    {
        $subject_setting_id = service('uri')->getSegment(4);
        if ($subject_setting_id) {
            $helper = model('App\Models\M_helper');
            $query = $helper->rowObject($this->pk, $subject_setting_id, 'admission_subject_settings');
            $academic_year = $helper->rowObject($this->pk, $query->academic_year_id, 'academic_years');
            $admission_type = $helper->rowObject($this->pk, $query->admission_type_id, 'options');
            if (session('major_count') > 0 && _toInteger($query->major_id) > 0) {
                $major = $helper->rowObject($this->pk, $query->major_id, 'majors');
            }
            $data['title'] = 'Pengaturan Nilai Rapor Sekolah';
            $sub_title = 'Tahun Pelajaran ' . $academic_year->academic_year . ' Jalur ' . $admission_type->option_name;
            if (session('major_count') > 0 && _toInteger($query->major_id) > 0) {
                $sub_title .= ' - ' . $major->major_name;
            }
            $data['sub_title'] = $sub_title;
            $data['admission'] = $data['admission_settings'] = $data['subject_semester_report_settings'] = true;
            $data['subjects_dropdown'] = json_encode(model('App\Models\M_subjects')->dropdown());
            $data['content'] = 'admission/subject_semester_report_details';
            return view('backend/index', $data);
        }
        throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
    }

    public function pagination()
    {
        if ($this->request->isAJAX()) {
            $subject_setting_id = _toInteger($this->request->getPost('subject_setting_id'));
            $keyword = trim($this->request->getPost('keyword'));
            $page_number = _toInteger($this->request->getPost('page_number'));
            $limit = _toInteger($this->request->getPost('per_page'));
            $offset = ($page_number * $limit);
            $m_admission_subject_setting_details = model('App\Models\M_admission_subject_setting_details');
            $query = $m_admission_subject_setting_details->get_where($keyword, $subject_setting_id, 'rows', $limit, $offset);
            $total_rows = $m_admission_subject_setting_details->get_where($keyword, $subject_setting_id);
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
            'subject_setting_id' => _toInteger($this->request->getPost('subject_setting_id')),
            'subject_id' => _toInteger($this->request->getPost('subject_id')),
            'visibility' => $this->request->getPost('visibility'),
        ];
    }

    private function validation()
    {
        $this->validation->setRules([
            'subject_id' => ['label' => 'Mata Pelajaran', 'rules' => 'trim|required'],
            'visibility' => ['label' => 'Tampil di Form Pendaftaran?', 'rules' => 'trim|required|in_list[public,private]'],
        ]);
        return $this->validation->withRequest($this->request)->run();
    }
}
