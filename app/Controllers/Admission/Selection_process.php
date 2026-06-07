<?php

namespace App\Controllers\Admission;

use App\Controllers\AdminController;

class Selection_process extends AdminController
{
    public function __construct()
    {
        $this->pk = 'id';
        $this->table = 'users';
    }

    public function index()
    {
        $data['title'] = 'Proses Seleksi';
        $data['admission'] = $data['selection_process'] = true;
        $options = [];
        $options['unapproved'] = 'Tidak Diterima';
        if (session('major_count') > 0) {
            $query = model('App\Models\M_majors')->dropdown();
            foreach ($query as $key => $value) {
                $options[$key] = 'Diterima di ' . $value;
            }
        } else {
            $options['approved'] = 'Diterima';
        }
        $data['options'] = $options;
        $data['admission_year_dropdown'] = model('App\Models\M_academic_years')->dropdown(true);
        $data['admission_type_dropdown'] = get_options('admission_type', false);
        $majors = [0 => 'Unset'];
        if (session('major_count') > 0) {
            $majors = [0 => 'Unset'] + model('App\Models\M_majors')->dropdown();
        }
        $data['major_dropdown'] = $majors;
        $data['content'] = 'admission/selection_process';
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
            $m_admission_subject_scores->generate_subject_scores($admission_year_id, $admission_type_id, $major_id, 'exam_schedule');
            $m_admission_subject_scores->generate_subject_scores($admission_year_id, $admission_type_id, $major_id, 'semester_report');
            $m_admission_subject_scores->generate_subject_scores($admission_year_id, $admission_type_id, $major_id, 'national_exam');
            $m_admission_selection_process = model('App\Models\M_admission_selection_process');
            $query = $m_admission_selection_process->get_prospective_students($admission_year_id, $admission_type_id, $major_id, 'rows', $limit, $offset);
            $total_rows = $m_admission_selection_process->get_prospective_students($admission_year_id, $admission_type_id, $major_id);
            $total_page = $limit > 0 ? ceil(_toInteger($total_rows) / _toInteger($limit)) : 1;
            $this->vars['total_rows'] = _toInteger($total_rows);
            $this->vars['total_page'] = _toInteger($total_page);
            $this->vars['students'] = [];
            if (session('major_count') > 0) {
                foreach ($query->getResult() as $row) {
                    $this->vars['students'][] = [
                        'id' => $row->id,
                        'first_choice' => $row->first_choice,
                        'second_choice' => $row->second_choice,
                        'registration_number' => $row->registration_number,
                        'full_name' => $row->full_name,
                    ];
                }
            } else {
                foreach ($query->getResult() as $row) {
                    $this->vars['students'][] = [
                        'id' => $row->id,
                        'registration_number' => $row->registration_number,
                        'full_name' => $row->full_name,
                    ];
                }
            }
            $this->vars['subjects'] = [];
            foreach (model('App\Models\M_subjects')->dropdown() as $key => $value) {
                $this->vars['subjects'][$key] = $value;
            }
            $exam_scores = $m_admission_subject_scores->get_subject_scores($admission_year_id, $admission_type_id, $major_id, 'exam_schedule', 'rows');
            $this->vars['admission_exam_scores'] = [];
            if (is_object($exam_scores)) {
                foreach ($exam_scores->getResult() as $row) {
                    $this->vars['admission_exam_scores'][] = [
                        'student_id' => $row->student_id,
                        'subject_id' => $row->subject_id,
                        'score' => $row->score,
                    ];
                }
            }
            $semester_scores = $m_admission_subject_scores->get_subject_scores($admission_year_id, $admission_type_id, $major_id, 'semester_report', 'rows');
            $this->vars['semester_report_scores'] = [];
            if (is_object($semester_scores)) {
                foreach ($semester_scores->getResult() as $row) {
                    $this->vars['semester_report_scores'][] = [
                        'student_id' => $row->student_id,
                        'subject_id' => $row->subject_id,
                        'score' => $row->score,
                    ];
                }
            }
            $national_exam_scores = $m_admission_subject_scores->get_subject_scores($admission_year_id, $admission_type_id, $major_id, 'national_exam', 'rows');
            $this->vars['national_exam_scores'] = [];
            if (is_object($national_exam_scores)) {
                foreach ($national_exam_scores->getResult() as $row) {
                    $this->vars['national_exam_scores'][] = [
                        'student_id' => $row->student_id,
                        'subject_id' => $row->subject_id,
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
            $admission_year_id = _toInteger($this->request->getPost('admission_year_id'));
            $admission_type_id = _toInteger($this->request->getPost('admission_type_id'));
            $selection_result = $this->request->getPost('selection_result');
            $student_ids = explode(',', $this->request->getPost('student_ids'));
            $query = model('App\Models\M_admission_selection_process')->selection_process($admission_year_id, $admission_type_id, $selection_result, $student_ids);
            $this->vars['status'] = $query['status'];
            $this->vars['message'] = $query['message'];
            return $this->response->setContentType('application/json')->setJSON($this->vars);
        }
    }
}
