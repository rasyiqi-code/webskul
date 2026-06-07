<?php

namespace App\Controllers\Admission;

use App\Controllers\AdminController;

class Registrants extends AdminController
{
    public function __construct()
    {
        $this->pk = 'id';
        $this->table = 'users';
    }

    public function index()
    {
        $data['title'] = 'Calon Peserta Didik Baru';
        $data['admission_year'] = session('admission_year');
        $data['admission'] = $data['registrants'] = true;
        $data['major_dropdown'] = json_encode([]);
        if (session('major_count') > 0) {
            $data['major_dropdown'] = json_encode([0 => 'Unset'] + model('App\Models\M_majors')->dropdown());
        }
        $data['admission_phase_dropdown'] = json_encode(model('App\Models\M_admission_phases')->dropdown());
        $data['content'] = 'admission/registrants';
        return view('backend/index', $data);
    }

    public function pagination()
    {
        if ($this->request->isAJAX()) {
            $keyword = trim($this->request->getPost('keyword'));
            $page_number = _toInteger($this->request->getPost('page_number'));
            $limit = _toInteger($this->request->getPost('per_page'));
            $offset = ($page_number * $limit);
            $m_registrants = model('App\Models\M_registrants');
            $query = $m_registrants->get_where($keyword, 'rows', $limit, $offset);
            $total_rows = $m_registrants->get_where($keyword);
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

    public function verified()
    {
        if ($this->request->isAJAX()) {
            $id = _toInteger($this->request->getPost('id'));
            $dataset['re_registration'] = $this->request->getPost('re_registration');
            $this->vars['status'] = model('App\Models\M_helper')->updateData($id, $this->table, $dataset) ? 'success' : 'error';
            $this->vars['message'] = $this->vars['status'] == 'success' ? 'updated' : 'not_updated';
            return $this->response->setContentType('application/json')->setJSON($this->vars);
        }
    }

    private function dataset()
    {
        return [
            'is_transfer' => $this->request->getPost('is_transfer'),
            'admission_type_id' => _toInteger($this->request->getPost('admission_type_id')),
            'first_choice_id' => _toInteger($this->request->getPost('first_choice_id')),
            'second_choice_id' => _toInteger($this->request->getPost('second_choice_id')),
            'nik' => $this->request->getPost('nik') ?? null,
            'prev_school_name' => $this->request->getPost('prev_school_name') ?? null,
            'prev_exam_number' => $this->request->getPost('prev_exam_number') ?? null,
            'skhun' => $this->request->getPost('skhun') ?? null,
            'prev_diploma_number' => $this->request->getPost('prev_diploma_number') ?? null,
            'admission_phase_id' => _toInteger($this->request->getPost('admission_phase_id')) ?? null,
            'full_name' => $this->request->getPost('full_name'),
            'gender' => $this->request->getPost('gender'),
            'nisn' => $this->request->getPost('nisn') ?? null,
            'family_card_number' => $this->request->getPost('family_card_number') ?? null,
            'birth_place' => $this->request->getPost('birth_place') ?? null,
            'birth_date' => $this->request->getPost('birth_date') ?? null,
            'birth_certificate_number' => $this->request->getPost('birth_certificate_number') ?? null,
            'religion_id' => _toInteger($this->request->getPost('religion_id')),
            'citizenship' => $this->request->getPost('citizenship') ?? null,
            'country' => $this->request->getPost('country') ?? null,
            'special_need_id' => _toInteger($this->request->getPost('special_need_id')),
            'street_address' => $this->request->getPost('street_address') ?? null,
            'rt' => $this->request->getPost('rt') ?? null,
            'rw' => $this->request->getPost('rw') ?? null,
            'sub_village' => $this->request->getPost('sub_village') ?? null,
            'village' => $this->request->getPost('village') ?? null,
            'sub_district' => $this->request->getPost('sub_district') ?? null,
            'district' => $this->request->getPost('district') ?? null,
            'postal_code' => $this->request->getPost('postal_code') ?? null,
            'latitude' => $this->request->getPost('latitude') ?? null,
            'longitude' => $this->request->getPost('longitude') ?? null,
            'residence_id' => _toInteger($this->request->getPost('residence_id')),
            'transportation_id' => _toInteger($this->request->getPost('transportation_id')),
            'child_number' => $this->request->getPost('child_number') ?? null,
            'employment_id' => $this->request->getPost('employment_id') ?? null,
            'have_kip' => $this->request->getPost('have_kip') ?? null,
            'receive_kip' => $this->request->getPost('receive_kip') ?? null,
            'reject_pip' => $this->request->getPost('reject_pip') ?? null,
            'father_name' => $this->request->getPost('father_name') ?? null,
            'father_nik' => $this->request->getPost('father_nik') ?? null,
            'father_birth_date' => $this->request->getPost('father_birth_date') ?? null,
            'father_birth_place' => $this->request->getPost('father_birth_place') ?? null,
            'father_education_id' => _toInteger($this->request->getPost('father_education_id')),
            'father_employment_id' => _toInteger($this->request->getPost('father_employment_id')),
            'father_monthly_income_id' => _toInteger($this->request->getPost('father_monthly_income_id')),
            'father_special_need_id' => _toInteger($this->request->getPost('father_special_need_id')),
            'mother_name' => $this->request->getPost('mother_name') ?? null,
            'mother_nik' => $this->request->getPost('mother_nik') ?? null,
            'mother_birth_date' => $this->request->getPost('mother_birth_date') ?? null,
            'mother_birth_place' => $this->request->getPost('mother_birth_place') ?? null,
            'mother_education_id' => _toInteger($this->request->getPost('mother_education_id')),
            'mother_employment_id' => _toInteger($this->request->getPost('mother_employment_id')),
            'mother_monthly_income_id' => _toInteger($this->request->getPost('mother_monthly_income_id')),
            'mother_special_need_id' => _toInteger($this->request->getPost('mother_special_need_id')),
            'guardian_name' => $this->request->getPost('guardian_name') ?? null,
            'guardian_nik' => $this->request->getPost('guardian_nik') ?? null,
            'guardian_birth_date' => $this->request->getPost('guardian_birth_date') ?? null,
            'guardian_birth_place' => $this->request->getPost('guardian_birth_place') ?? null,
            'guardian_education_id' => _toInteger($this->request->getPost('guardian_education_id')),
            'guardian_employment_id' => _toInteger($this->request->getPost('guardian_employment_id')),
            'guardian_monthly_income_id' => _toInteger($this->request->getPost('guardian_monthly_income_id')),
            'phone' => $this->request->getPost('phone') ?? null,
            'mobile_phone' => $this->request->getPost('mobile_phone') ?? null,
            'email' => $this->request->getPost('email') ?? null,
            'height' => $this->request->getPost('height') ?? null,
            'weight' => $this->request->getPost('weight') ?? null,
            'head_circumference' => $this->request->getPost('head_circumference') ?? null,
            'mileage' => $this->request->getPost('mileage') ?? null,
            'traveling_time' => $this->request->getPost('traveling_time') ?? null,
            'sibling_number' => $this->request->getPost('sibling_number') ?? null,
            'welfare_type' => $this->request->getPost('welfare_type') ?? null,
            'welfare_number' => $this->request->getPost('welfare_number') ?? null,
            'welfare_name' => $this->request->getPost('welfare_name') ?? null,
        ];
    }

    private function validation($id = 0)
    {
        $rules = [
            'admission_type_id' => ['label' => 'Jenis Pendaftaran', 'rules' => 'trim|required|is_natural_no_zero'],
            'full_name' => ['label' => 'Full Name', 'rules' => 'trim|required'],
            'email' => ['label' => 'Email', 'rules' => 'trim|required|valid_email'],
            'nik' => ['label' => 'NIK', 'rules' => 'trim'],
        ];
        if (session('major_count') > 0) {
            $rules['first_choice_id'] = ['label' => 'First Choice', 'rules' => 'trim|required'];
            $rules['second_choice_id'] = ['label' => 'Second Choice', 'rules' => 'trim'];
        }
        $this->validation->setRules($rules);
        return $this->validation->withRequest($this->request)->run();
    }

    public function print_admission_form()
    {
        $id = service('uri')->getSegment(4);
        if (!_isNaturalNumber($id)) throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        $query = model('App\Models\M_helper')->rowObject($this->pk, $id, $this->table);
        if (!is_object($query)) throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        $res = model('App\Models\M_registrants')->find_registrant($query->registration_number, $query->birth_date);
        if (is_null($res)) throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        if (!filter_var($res['is_prospective_student'], FILTER_VALIDATE_BOOLEAN)) throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        $file_name = 'formulir-penerimaan-peserta-didik-baru-tahun-' . substr($res['registration_number'] ?? '', 0, 4);
        $file_name .= '-' . $res['birth_date'] . '-' . $res['registration_number'] . '.pdf';
        $admission = service('admission');
        $admission->pdf($res, $file_name);
    }

    public function admission_reports()
    {
        if ($this->request->isAJAX()) {
            $query = model('App\Models\M_registrants')->admission_reports();
            return $this->response->setContentType('application/json')->setJSON($query->getResult());
        }
    }

    public function profile()
    {
        $id = _toInteger(service('uri')->getSegment(4));
        if (_isNaturalNumber($id)) {
            $data['student'] = model('App\Models\M_students')->profile($id);
            $data['subjects'] = model('App\Models\M_verification_subject_scores')->student_subject_scores($id);
            $data['title'] = 'Profil Calon Peserta Didik Baru';
            $data['photo'] = base_url('media_library/images/no-image.png');
            $data['scholarships'] = $data['achievements'] = false;
            $photo_name = $data['student']->photo;
            $photo = 'media_library/users/photo/' . $photo_name;
            if ($photo_name && file_exists($_SERVER['DOCUMENT_ROOT'] . '/' . $photo)) {
                $data['photo'] = base_url($photo);
            }
            $data['admission'] = $data['registrants'] = true;
            $data['content'] = 'academic/student_profile';
            return view('backend/index', $data);
        }
        throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
    }
}
