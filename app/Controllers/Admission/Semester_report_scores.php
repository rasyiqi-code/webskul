<?php

namespace App\Controllers\Admission;

use App\Controllers\AdminController;

class Semester_report_scores extends AdminController
{
    public function __construct()
    {
        $this->pk = 'id';
        $this->table = 'admission_subject_scores';
    }

    public function index()
    {
        $data['title'] = 'Input Nilai Rapor';
        $data['admission'] = $data['semester_report_scores'] = true;
        $data['admission_year_dropdown'] = model('App\Models\M_academic_years')->dropdown(true);
        $data['admission_type_dropdown'] = get_options('admission_type', false);
        $majors = [0 => 'Unset'];
        if (session('major_count') > 0) {
            $majors = [0 => 'Unset'] + model('App\Models\M_majors')->dropdown();
        }
        $data['major_dropdown'] = $majors;
        $data['content'] = 'admission/semester_report_scores';
        return view('backend/index', $data);
    }

    public function get_prospective_students()
    {
        if ($this->request->isAJAX()) {
            $admission_year_id = _toInteger($this->request->getPost('admission_year_id'));
            $admission_type_id = _toInteger($this->request->getPost('admission_type_id'));
            $major_id = _toInteger($this->request->getPost('major_id'));
            $page_number = _toInteger($this->request->getPost('page_number'));
            $limit = _toInteger($this->request->getPost('per_page'));
            $offset = ($page_number * $limit);
            $m_admission_subject_scores = model('App\Models\M_admission_subject_scores');
            $m_admission_subject_scores->generate_subject_scores($admission_year_id, $admission_type_id, $major_id, 'semester_report');
            $query = $m_admission_subject_scores->get_subject_scores($admission_year_id, $admission_type_id, $major_id, 'semester_report', 'rows', $limit, $offset);
            $total_rows = $m_admission_subject_scores->get_subject_scores($admission_year_id, $admission_type_id, $major_id, 'semester_report');
            $total_page = $limit > 0 ? ceil(_toInteger($total_rows) / _toInteger($limit)) : 1;
            $this->vars['students'] = [];
            $this->vars['total_rows'] = _toInteger($total_rows);
            $this->vars['total_page'] = _toInteger($total_page);
            if (is_object($query)) {
                foreach ($query->getResult() as $row) {
                    $this->vars['students'][] = [
                        'id' => $row->id,
                        'registration_number' => $row->registration_number,
                        'full_name' => $row->full_name,
                        'subject_name' => $row->subject_name,
                        'score' => $row->score,
                    ];
                }
            }
            return $this->response->setContentType('application/json')->setJSON($this->vars);
        }
    }

    public function save()
    {
        if ($this->request->isAJAX()) {
            $scores = json_decode($this->request->getPost('scores'), true);
            $this->vars['message'] = model('App\Models\M_admission_subject_scores')->save($scores) ? 'updated' : 'not_updated';
            return $this->response->setContentType('application/json')->setJSON($this->vars);
        }
    }
}
