<?php

namespace App\Controllers;

class Student_profile extends AdminController
{
    protected $pk    = 'id';
    protected $table = 'students';

    /** @var \App\Models\M_students */
    protected $mStudents;

    /** @var \App\Models\M_users */
    protected $mUsers;

    public function initController($request, $response, $logger)
    {
        parent::initController($request, $response, $logger);
        $this->mStudents = model('App\Models\M_students');
        $this->mUsers    = model('App\Models\M_users');
    }

    public function index()
    {
        helper('form');
        $id = _toInteger(session('user_id'));
        $this->vars['title'] = 'Biodata';
        $this->vars['student_profile'] = true;
        $this->vars['religions'] = ['' => 'Pilih :'] + get_options('religion', false);
        $this->vars['special_needs'] = ['' => 'Pilih :'] + get_options('special_need', false);
        $this->vars['residences'] = ['' => 'Pilih :'] + get_options('residence', false);
        $this->vars['transportations'] = ['' => 'Pilih :'] + get_options('transportation', false);
        $this->vars['educations'] = ['' => 'Pilih :'] + get_options('education', false);
        $this->vars['employments'] = ['' => 'Pilih :'] + get_options('employment', false);
        $this->vars['monthly_incomes'] = ['' => 'Pilih :'] + get_options('monthly_income', false);
        $this->vars['query'] = model('App\Models\M_helper')->rowObject($this->pk, $id, $this->table);
        $this->vars['content'] = 'students_profile';
        return view('backend/index', $this->vars);
    }

    public function save()
    {
        if ($this->request->isAJAX()) {
            $id = _toInteger(session('user_id'));
            if (_isNaturalNumber($id)) {
                if ($this->validation($id)) {
                    $dataset = $this->dataset();
                    $this->vars['status'] = model('App\Models\M_helper')->updateData($id, $this->table, $dataset) ? 'success' : 'error';
                    $this->vars['message'] = $this->vars['status'] == 'success' ? 'updated' : 'not_updated';
                } else {
                    $this->vars['status'] = 'error';
                    $this->vars['message'] = $this->validation->getErrors();
                }
            } else {
                $this->vars['status'] = 'error';
                $this->vars['message'] = 'not_updated';
            }
            return $this->response->setContentType('application/json')->setJSON($this->vars);
        }
    }

    private function dataset()
    {
        return [
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
            'email' => $this->request->getPost('email') ? $this->request->getPost('email') : null,
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
        ];
    }

    private function validation($id)
    {
        $this->validation->setRules([
            'email' => ['label' => 'Email', 'rules' => 'trim|required|valid_email'],
            'father_birth_date' => ['label' => 'Tanggal Lahir Ayah', 'rules' => 'trim'],
            'mother_birth_date' => ['label' => 'Tanggal Lahir Ibu', 'rules' => 'trim'],
            'guardian_birth_date' => ['label' => 'Tanggal Lahir Wali', 'rules' => 'trim'],
            'sibling_number' => ['label' => 'Jumlah Saudara Kandung', 'rules' => 'trim|numeric|min_length[1]|max_length[2]'],
            'rt' => ['label' => 'RT', 'rules' => 'trim|numeric'],
            'rw' => ['label' => 'RW', 'rules' => 'trim|numeric'],
            'postal_code' => ['label' => 'Kode Pos', 'rules' => 'trim|numeric'],
            'mileage' => ['label' => 'Jarak Tempat Tinggal ke Sekolah', 'rules' => 'trim|numeric'],
            'traveling_time' => ['label' => 'Waktu Tempuh ke Sekolah', 'rules' => 'trim|numeric'],
            'height' => ['label' => 'Tinggi Badan', 'rules' => 'trim|numeric|min_length[2]|max_length[3]'],
            'weight' => ['label' => 'Berat Badan', 'rules' => 'trim|numeric|min_length[2]|max_length[3]'],
            'head_circumference' => ['label' => 'Lingkar Kepala', 'rules' => 'trim|numeric|min_length[2]|max_length[3]'],
        ]);
        return $this->validation->withRequest($this->request)->run();
    }

    public function email_exists($email = '', $id = 0)
    {
        $email_exists = $this->mUsers->email_exists($email, $id);
        if ($email_exists) {
            $this->validation->setRule('email', 'Email', 'is_unique[users.email]');
            return false;
        }
        return true;
    }
}
