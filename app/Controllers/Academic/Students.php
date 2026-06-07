<?php

namespace App\Controllers\Academic;

use App\Controllers\AdminController;

class Students extends AdminController
{
    public function __construct()
    {
        $this->pk = 'id';
        $this->table = 'users';
    }

    public function index()
    {
        $data['title'] = 'Peserta Didik';
        $data['academic'] = $data['academic_references'] = $data['students'] = true;
        $majors = [0 => 'Unset'];
        if (session('major_count') > 0) {
            $majors = [0 => 'Unset'] + model('App\Models\M_majors')->dropdown();
        }
        $data['major_dropdown'] = json_encode($majors);
        $data['content'] = 'academic/students';
        return view('backend/index', $data);
    }

    public function profile()
    {
        $id = _toInteger(service('uri')->getSegment(4));
        if (_isNaturalNumber($id)) {
            $m_students = model('App\Models\M_students');
            $data['student'] = $m_students->profile($id);
            $data['scholarships'] = model('App\Models\M_scholarships')->get_by_student_id($id);
            $data['achievements'] = model('App\Models\M_achievements')->get_by_student_id($id);
            $data['title'] = 'Profil Peserta Didik';
            $data['photo'] = base_url('media_library/images/no-image.png');
            $photo_name = $data['student']->photo;
            $photo = 'media_library/users/photo/' . $photo_name;
            if ($photo_name && file_exists($_SERVER['DOCUMENT_ROOT'] . '/' . $photo)) {
                $data['photo'] = base_url($photo);
            }
            $data['academic'] = $data['academic_references'] = $data['students'] = true;
            $data['content'] = 'academic/student_profile';
            return view('backend/index', $data);
        } else {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }
    }

    public function pagination()
    {
        if ($this->request->isAJAX()) {
            $keyword = trim($this->request->getPost('keyword'));
            $page_number = _toInteger($this->request->getPost('page_number'));
            $limit = _toInteger($this->request->getPost('per_page'));
            $offset = ($page_number * $limit);
            $m_students = model('App\Models\M_students');
            $query = $m_students->get_where($keyword, 'rows', $limit, $offset);
            $total_rows = $m_students->get_where($keyword);
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
            if ($this->validation($id)) {
                $dataset = $this->dataset();
                $query = model('App\Models\M_helper')->upsert($id, $this->table, $dataset);
                if ($query) model('App\Models\M_users')->activate_account($dataset['email']);
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
            'is_student' => 'true',
            'is_transfer' => $this->request->getPost('is_transfer'),
            'start_date' => $this->request->getPost('start_date') ?? null,
            'nis' => $this->request->getPost('nis') ?? null,
            'major_id' => _toInteger($this->request->getPost('major_id')),
            'full_name' => $this->request->getPost('full_name'),
            'gender' => $this->request->getPost('gender'),
            'nisn' => $this->request->getPost('nisn') ?? null,
            'nik' => $this->request->getPost('nik') ?? null,
            'skhun' => $this->request->getPost('skhun') ?? null,
            'prev_exam_number' => $this->request->getPost('prev_exam_number') ?? null,
            'prev_diploma_number' => $this->request->getPost('prev_diploma_number') ?? null,
            'prev_school_name' => $this->request->getPost('prev_school_name') ?? null,
            'birth_place' => $this->request->getPost('birth_place') ?? null,
            'birth_date' => $this->request->getPost('birth_date') ?? null,
            'family_card_number' => $this->request->getPost('family_card_number') ?? null,
            'birth_certificate_number' => $this->request->getPost('birth_certificate_number') ?? null,
            'religion_id' => _toInteger($this->request->getPost('religion_id')),
            'special_need_id' => _toInteger($this->request->getPost('special_need_id')),
            'street_address' => $this->request->getPost('street_address') ?? null,
            'rt' => $this->request->getPost('rt') ?? null,
            'rw' => $this->request->getPost('rw') ?? null,
            'sub_village' => $this->request->getPost('sub_village') ?? null,
            'village' => $this->request->getPost('village') ?? null,
            'sub_district' => $this->request->getPost('sub_district') ?? null,
            'district' => $this->request->getPost('district') ?? null,
            'postal_code' => $this->request->getPost('postal_code') ?? null,
            'residence_id' => _toInteger($this->request->getPost('residence_id')),
            'transportation_id' => _toInteger($this->request->getPost('transportation_id')),
            'child_number' => _toInteger($this->request->getPost('child_number')),
            'phone' => $this->request->getPost('phone') ?? null,
            'mobile_phone' => $this->request->getPost('mobile_phone') ?? null,
            'email' => $this->request->getPost('email'),
            'welfare_type' => $this->request->getPost('welfare_type') ?? null,
            'welfare_number' => $this->request->getPost('welfare_number') ?? null,
            'welfare_name' => $this->request->getPost('welfare_name') ?? null,
            'citizenship' => $this->request->getPost('citizenship') ?? null,
            'country' => $this->request->getPost('country') ?? null,
            'father_name' => $this->request->getPost('father_name') ?? null,
            'father_nik' => $this->request->getPost('father_nik') ?? null,
            'father_birth_place' => $this->request->getPost('father_birth_place') ?? null,
            'father_birth_date' => $this->request->getPost('father_birth_date') ?? null,
            'father_education_id' => _toInteger($this->request->getPost('father_education_id')),
            'father_employment_id' => _toInteger($this->request->getPost('father_employment_id')),
            'father_monthly_income_id' => _toInteger($this->request->getPost('father_monthly_income_id')),
            'father_special_need_id' => _toInteger($this->request->getPost('father_special_need_id')),
            'mother_name' => $this->request->getPost('mother_name') ?? null,
            'mother_nik' => $this->request->getPost('mother_nik') ?? null,
            'mother_birth_place' => $this->request->getPost('mother_birth_place') ?? null,
            'mother_birth_date' => $this->request->getPost('mother_birth_date') ?? null,
            'mother_education_id' => _toInteger($this->request->getPost('mother_education_id')),
            'mother_employment_id' => _toInteger($this->request->getPost('mother_employment_id')),
            'mother_monthly_income_id' => _toInteger($this->request->getPost('mother_monthly_income_id')),
            'mother_special_need_id' => _toInteger($this->request->getPost('mother_special_need_id')),
            'guardian_name' => $this->request->getPost('guardian_name') ?? null,
            'guardian_nik' => $this->request->getPost('guardian_nik') ?? null,
            'guardian_birth_place' => $this->request->getPost('guardian_birth_place') ?? null,
            'guardian_birth_date' => $this->request->getPost('guardian_birth_date') ?? null,
            'guardian_education_id' => _toInteger($this->request->getPost('guardian_education_id')),
            'guardian_employment_id' => _toInteger($this->request->getPost('guardian_employment_id')),
            'guardian_monthly_income_id' => _toInteger($this->request->getPost('guardian_monthly_income_id')),
            'mileage' => $this->request->getPost('mileage') ?? null,
            'traveling_time' => $this->request->getPost('traveling_time') ?? null,
            'height' => $this->request->getPost('height') ?? null,
            'weight' => $this->request->getPost('weight') ?? null,
            'head_circumference' => $this->request->getPost('head_circumference') ?? null,
            'sibling_number' => $this->request->getPost('sibling_number') ?? null,
            'student_status_id' => _toInteger($this->request->getPost('student_status_id')),
            'end_date' => $this->request->getPost('end_date') ?? null,
            'reason' => $this->request->getPost('reason') ?? null,
        ];
    }

    private function validation($id = 0)
    {
        $this->validation->setRules([
            'nis' => ['label' => 'NIS', 'rules' => 'trim|required'],
            'full_name' => ['label' => 'Nama Lengkap', 'rules' => 'trim|required'],
            'student_status_id' => ['label' => 'Status Peserta Didik', 'rules' => 'trim|required'],
            'email' => ['label' => 'Email', 'rules' => 'trim|required|valid_email'],
            'nik' => ['label' => 'NIK', 'rules' => 'trim'],
            'nisn' => ['label' => 'NISN', 'rules' => 'trim'],
            'sibling_number' => ['label' => 'Jumlah Saudara Kandung', 'rules' => 'trim|numeric|min_length[1]|max_length[2]'],
            'rt' => ['label' => 'RT', 'rules' => 'trim|numeric'],
            'rw' => ['label' => 'RW', 'rules' => 'trim|numeric'],
            'postal_code' => ['label' => 'Kode Pos', 'rules' => 'trim|numeric'],
            'mileage' => ['label' => 'Jarak Tempuh', 'rules' => 'trim|numeric'],
            'traveling_time' => ['label' => 'Waktu Tempuh', 'rules' => 'trim|numeric'],
            'height' => ['label' => 'Tinggi Badan', 'rules' => 'trim|numeric|min_length[2]|max_length[3]'],
            'weight' => ['label' => 'Berat Badan', 'rules' => 'trim|numeric|min_length[2]|max_length[3]'],
            'head_circumference' => ['label' => 'Lingkar Kepala', 'rules' => 'trim|numeric|min_length[2]|max_length[3]'],
        ]);
        return $this->validation->withRequest($this->request)->run();
    }

    public function student_reports()
    {
        if ($this->request->isAJAX()) {
            $query = model('App\Models\M_students')->student_reports();
            return $this->response->setContentType('application/json')->setJSON($query->getResult());
        }
    }
}
