<?php

namespace App\Controllers\Admission;

use App\Controllers\AdminController;

class Exam_attendances extends AdminController
{
    public function __construct()
    {
        $this->pk = 'id';
        $this->table = 'admission_exam_attendances';
    }

    public function index()
    {
        $exam_schedule_id = _toInteger(service('uri')->getSegment(4));
        if (_isNaturalNumber($exam_schedule_id)) {
            $query = model('App\Models\M_helper')->rowObject('id', $exam_schedule_id, 'admission_exam_schedules');
            $data['title'] = 'Pengaturan Peserta Ujian Tes Tulis';
            $data['query'] = model('App\Models\M_admission_exam_schedule_details')->get_title($exam_schedule_id);
            $data['admission'] = $data['admission_settings'] = $data['admission_exam_schedules'] = true;
            $data['content'] = 'admission/exam_attendances';
            return view('backend/index', $data);
        }
        throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
    }

    public function get_prospective_students()
    {
        if ($this->request->isAJAX()) {
            $exam_schedule_id = _toInteger($this->request->getPost('exam_schedule_id'));
            $this->vars['students'] = [];
            if (_isNaturalNumber($exam_schedule_id)) {
                $query = model('App\Models\M_helper')->rowObject('id', $exam_schedule_id, 'admission_exam_schedules');
                $students = model('App\Models\M_admission_exam_attendances')->get_prospective_students($query->subject_setting_detail_id);
                if ($students->num_rows() > 0) {
                    foreach ($students->getResult() as $row) {
                        $this->vars['students'][] = [
                            'student_id' => $row->student_id,
                            'registration_number' => $row->registration_number,
                            'full_name' => $row->full_name,
                        ];
                    }
                }
            }
            return $this->response->setContentType('application/json')->setJSON($this->vars);
        }
    }

    public function save_attendance()
    {
        if ($this->request->isAJAX()) {
            $presences = json_decode($this->request->getPost('presences'), true);
            $this->vars['message'] = model('App\Models\M_admission_exam_attendances')->save_attendance($presences) ? 'updated' : 'not_updated';
            return $this->response->setContentType('application/json')->setJSON($this->vars);
        }
    }

    public function get_attendance_lists()
    {
        if ($this->request->isAJAX()) {
            $exam_schedule_id = _toInteger($this->request->getPost('exam_schedule_id'));
            $this->vars['students'] = [];
            if (_isNaturalNumber($exam_schedule_id)) {
                $query = model('App\Models\M_admission_exam_attendances')->get_attendance_lists($exam_schedule_id);
                if ($query->num_rows() > 0) {
                    foreach ($query->getResult() as $row) {
                        $this->vars['students'][] = [
                            'id' => $row->id,
                            'registration_number' => $row->registration_number,
                            'full_name' => $row->full_name,
                            'presence' => $row->presence,
                        ];
                    }
                }
            }
            return $this->response->setContentType('application/json')->setJSON($this->vars);
        }
    }

    public function save_attendance_lists()
    {
        if ($this->request->isAJAX()) {
            $student_ids = $this->request->getPost('student_ids');
            $exam_schedule_id = _toInteger($this->request->getPost('exam_schedule_id'));
            $ids = [];
            foreach (explode(',', $student_ids) as $student_id) {
                array_push($ids, trim($student_id));
            }
            $query = model('App\Models\M_admission_exam_attendances')->save_attendance_lists($ids, $exam_schedule_id);
            $this->vars['status'] = $query ? 'success' : 'error';
            $this->vars['message'] = $query ? 'Data sudah disipman' : 'Data tidak tersimpan. Kemungkinan terjadi duplikasi data atau server bermasalah, silahkan periksa kembali data Anda.';
            return $this->response->setContentType('application/json')->setJSON($this->vars);
        }
    }

    public function delete_attendance_lists()
    {
        if ($this->request->isAJAX()) {
            $ids = $this->request->getPost('ids');
            $array_ids = [];
            foreach (explode(',', $ids) as $id) {
                array_push($array_ids, trim($id));
            }
            $query = model('App\Models\M_admission_exam_attendances')->delete_attendance_lists($array_ids);
            $this->vars['status'] = $query ? 'success' : 'error';
            $this->vars['message'] = $query ? 'Data sudah terhapus' : 'Data tidak terhapus.';
            return $this->response->setContentType('application/json')->setJSON($this->vars);
        }
    }
}
