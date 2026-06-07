<?php

namespace App\Controllers\Admission;

use App\Controllers\AdminController;

class Exam_schedule_details extends AdminController
{
    public function __construct()
    {
        $this->pk = 'id';
        $this->table = 'admission_exam_schedule_details';
    }

    public function index()
    {
        $subject_setting_detail_id = 0;
        try {
            $subject_setting_detail_id = _toInteger(service('uri')->getSegment(4));
        } catch (\Throwable $e) {
            $subject_setting_detail_id = 0;
        }
        if (_isNaturalNumber($subject_setting_detail_id)) {
            $helper = model('App\Models\M_helper');
            $query = $helper->rowObject($this->pk, $subject_setting_detail_id, 'admission_subject_setting_details');
            $subjects = $helper->rowObject($this->pk, $query->subject_id, 'subjects');
            $subject_setting = $helper->rowObject($this->pk, $query->subject_setting_id, 'admission_subject_settings');
            $academic_year = $helper->rowObject($this->pk, $subject_setting->academic_year_id, 'academic_years');
            $admission_type = $helper->rowObject($this->pk, $subject_setting->admission_type_id, 'options');
            if (session('major_count') > 0 && _toInteger($subject_setting->major_id) > 0) {
                $major = $helper->rowObject($this->pk, $subject_setting->major_id, 'majors');
            }
            $data['title'] = 'Pengaturan Ujian tes Tulis';
            $sub_title = ' Tahun Pelajaran ' . $academic_year->academic_year . ' Jalur ' . $admission_type->option_name;
            if (session('major_count') > 0 && _toInteger($subject_setting->major_id) > 0) {
                $sub_title .= ' - Jurusan ' . $major->major_name;
            }
            $sub_title .= ' Mata Pelajaran ' . $subjects->subject_name;
            $data['sub_title'] = $sub_title;
            $data['admission'] = $data['admission_settings'] = $data['admission_exam_schedules'] = true;
            $data['rooms_dropdown'] = json_encode(model('App\Models\M_rooms')->dropdown());
            $data['content'] = 'admission/exam_schedule_details';
            return view('backend/index', $data);
        }
        throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
    }

    public function pagination()
    {
        if ($this->request->isAJAX()) {
            $subject_setting_detail_id = _toInteger($this->request->getPost('subject_setting_detail_id'));
            $keyword = trim($this->request->getPost('keyword'));
            $page_number = _toInteger($this->request->getPost('page_number'));
            $limit = _toInteger($this->request->getPost('per_page'));
            $offset = ($page_number * $limit);
            $m_admission_exam_schedule_details = model('App\Models\M_admission_exam_schedule_details');
            $query = $m_admission_exam_schedule_details->get_where($keyword, $subject_setting_detail_id, 'rows', $limit, $offset);
            $total_rows = $m_admission_exam_schedule_details->get_where($keyword, $subject_setting_detail_id);
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
            'subject_setting_detail_id' => _toInteger($this->request->getPost('subject_setting_detail_id')),
            'room_id' => _toInteger($this->request->getPost('room_id')),
            'exam_date' => $this->request->getPost('exam_date'),
            'exam_start_time' => $this->request->getPost('exam_start_time'),
            'exam_end_time' => $this->request->getPost('exam_end_time'),
        ];
    }

    private function validation()
    {
        $this->validation->setRules([
            'room_id' => ['label' => 'Ruang Ujian', 'rules' => 'trim|is_natural_no_zero|required'],
            'exam_date' => ['label' => 'Tanggal Pelaksanaan', 'rules' => 'trim|required'],
            'exam_start_time' => ['label' => 'Jam Mulai', 'rules' => 'trim|required'],
            'exam_end_time' => ['label' => 'Jam Selesai', 'rules' => 'trim|required'],
        ]);
        return $this->validation->withRequest($this->request)->run();
    }

    public function print_exam_attendances()
    {
        $id = service('uri')->getSegment(4);
        if (!_isNaturalNumber($id)) throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        $header = model('App\Models\M_admission_exam_schedule_details')->get_title($id);
        $students = model('App\Models\M_admission_exam_attendances')->get_attendance_lists($id);
        $exam_attendances = service('exam_attendances');
        $exam_attendances->pdf($header, $students);
    }
}
