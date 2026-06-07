<?php

namespace App\Controllers\Public;

use App\Controllers\PublicController;

class Admission_form extends PublicController
{
    protected $m_registrants;

    public function initController($request, $response, $logger)
    {
        parent::initController($request, $response, $logger);

        $admission = session('admission');
        if ($admission && isset($admission['admission_status']) && $admission['admission_status'] == 'close') {
            return redirect()->to(base_url());
        }

        $admission_start_date = session('admission_start_date');
        $admission_end_date = session('admission_end_date');
        if ($admission_start_date && $admission_end_date) {
            $date_range = array_date($admission_start_date, $admission_end_date);
            if (!in_array(date('Y-m-d'), $date_range)) {
                return redirect()->to(base_url());
            }
        }

        $this->m_registrants = model('App\Models\M_registrants');
    }

    public function index()
    {
        $m_majors = model('App\Models\M_majors');
        $data['page_title'] = 'Formulir Penerimaan Peserta Didik Baru Tahun ' . session('admission_year');
        $data['religions'] = ['' => 'Pilih :'] + get_options('religion', false);
        $data['special_needs'] = get_options('special_need', false);
        $data['residences'] = ['' => 'Pilih :'] + get_options('residence', false);
        $data['transportations'] = ['' => 'Pilih :'] + get_options('transportation', false);
        $data['educations'] = ['' => 'Pilih :'] + get_options('education', false);
        $data['employments'] = ['' => 'Pilih :'] + get_options('employment', false);
        $data['monthly_incomes'] = ['' => 'Pilih :'] + get_options('monthly_income', false);
        $data['majors'] = ['' => 'Pilih :'] + $m_majors->dropdown();
        $data['admission_types'] = ['' => 'Pilih :'] + get_options('admission_type', false);
        $data['content'] = 'themes/' . theme_folder() . '/admission-form';
        return view('themes/' . theme_folder() . '/index', $data);
    }

    public function save()
    {
        if (!$this->request->isAJAX()) {
            return $this->response->setStatusCode(404);
        }

        if (__captchaActivated()) {
            $score = get_recapture_score($this->request->getPost('g-recaptcha-response'));
            if ($score < 0.9) {
                return $this->response->setContentType('application/json')->setJSON([
                    'status' => 'recaptcha_error',
                    'message' => 'Recaptcha Error!'
                ]);
            }
        }

        $status = 'error';
        $message = '';
        $id = 0;
        $reg_no = '';
        $bdate = '';

        if ($this->validation()) {
            $dataset = $this->dataset();
            $documents = [
                ['name' => 'photo', 'upload_status' => false],
                ['name' => 'family_card', 'upload_status' => false],
                ['name' => 'birth_certificate', 'upload_status' => false],
                ['name' => 'father_identity_card', 'upload_status' => false],
                ['name' => 'mother_identity_card', 'upload_status' => false],
                ['name' => 'guardian_identity_card', 'upload_status' => false],
            ];

            foreach ($documents as $key => $document) {
                if (!empty($_FILES[$document['name']]['name'])) {
                    $upload = $this->upload_file($document['name']);
                    if ($upload['status'] == 'success') {
                        $documents[$key]['upload_status'] = true;
                        $dataset[$document['name']] = $upload['file_name'];
                    } else {
                        return $this->response->setContentType('application/json')->setJSON([
                            'status' => $upload['status'],
                            'message' => $upload['message']
                        ]);
                    }
                }
            }

            $subject_scores = json_decode($this->request->getPost('subject_scores'), true);
            $query = $this->m_registrants->saveRegistrationForm($dataset, $subject_scores);

            if ($query) {
                $res = $this->m_registrants->findRegistrant($dataset['registration_number'], $dataset['birth_date']);
                if (is_null($res)) {
                    $status = 'warning';
                    $message = 'Data tidak ditemukan.';
                } else {
                    $status = 'success';
                    $id = $res['id'];
                    $reg_no = $res['registration_number'];
                    $bdate = $res['birth_date'];
                }
            } else {
                $status = 'error';
                $message = 'Terjadi kesalahan dalam menyimpan data';
            }

            foreach ($documents as $document) {
                if (!$query && $document['upload_status']) {
                    @unlink(FCPATH . 'media_library/users/' . $document['name'] . '/' . $dataset[$document['name']]);
                }
            }
        } else {
            $status = 'error';
            $message = $this->validation->getErrors();
        }

        return $this->response->setContentType('application/json')->setJSON([
            'status' => $status,
            'message' => $message,
            'id' => $id,
            'registration_number' => $reg_no,
            'birth_date' => $bdate,
        ]);
    }

    public function get_subject_settings()
    {
        if (!$this->request->isAJAX()) {
            return $this->response->setStatusCode(404);
        }

        $admission_type_id = _toInteger($this->request->getPost('admission_type_id'));
        $major_id = _toInteger($this->request->getPost('major_id'));
        $m_admission_subject_settings = model('App\Models\M_admission_subject_settings');

        return $this->response->setContentType('application/json')->setJSON([
                'semester_report_subjects' => $m_admission_subject_settings->getSubjectSettings($admission_type_id, $major_id, 'semester_report', 'public'),
                'national_exam_subjects' => $m_admission_subject_settings->getSubjectSettings($admission_type_id, $major_id, 'national_exam', 'public'),
        ]);
    }

    private function upload_file($document_name)
    {
        $file = $this->request->getFile($document_name);
        $result = ['status' => 'error', 'message' => '', 'file_name' => ''];

        if ($file && $file->isValid() && !$file->hasMoved()) {
            $allowed = ['jpg', 'jpeg', 'png'];
            $ext = strtolower($file->getExtension());
            if (in_array($ext, $allowed) && $file->getSize() <= 524288) {
                $newName = $file->getRandomName();
                $file->move(FCPATH . 'media_library/users/' . $document_name, $newName);
                @chmod(FCPATH . 'media_library/users/' . $document_name . '/' . $newName, 0777);
                if ($document_name === 'photo') {
                    $this->image_resize(FCPATH . 'media_library/users/' . $document_name . '/', $newName);
                }
                $result['status'] = 'success';
                $result['message'] = 'uploaded';
                $result['file_name'] = $newName;
            } else {
                $result['message'] = 'File harus JPG/JPEG/PNG dan maksimal 512 KB.';
            }
        } else {
            $result['message'] = 'Tidak ada file yang diupload.';
        }
        return $result;
    }

    private function image_resize($path, $file_name)
    {
        $width = session('media')['user_photo_width'] ?? 250;
        if ($width > 0) {
            \Config\Services::image()
                ->withFile($path . '/' . $file_name)
                ->fit($width, session('media')['user_photo_height'] ?? 600, 'center')
                ->save($path . '/' . $file_name);
        }
    }

    private function dataset()
    {
        $data = [];
        $data['registration_number'] = $this->m_registrants->registrationNumber();
        $data['is_prospective_student'] = 'true';
        $data['is_transfer'] = $this->request->getPost('is_transfer');
        $data['admission_type_id'] = _toInteger($this->request->getPost('admission_type_id'));
        $data['admission_phase_id'] = session('admission_phase_id') ?: 0;

        $fields = [
            'first_choice_id', 'second_choice_id', 'nik', 'prev_school_name',
            'prev_exam_number', 'skhun', 'prev_diploma_number', 'full_name', 'gender',
            'nisn', 'family_card_number', 'birth_place', 'birth_date',
            'birth_certificate_number', 'religion_id', 'citizenship', 'country',
            'special_need_id', 'street_address', 'rt', 'rw', 'sub_village', 'village',
            'sub_district', 'district', 'postal_code', 'latitude', 'longitude',
            'residence_id', 'transportation_id', 'child_number', 'employment_id',
            'have_kip', 'receive_kip', 'reject_pip',
            'father_name', 'father_nik', 'father_birth_place', 'father_birth_date',
            'father_education_id', 'father_employment_id', 'father_monthly_income_id',
            'father_special_need_id',
            'mother_name', 'mother_nik', 'mother_birth_place', 'mother_birth_date',
            'mother_education_id', 'mother_employment_id', 'mother_monthly_income_id',
            'mother_special_need_id',
            'guardian_name', 'guardian_nik', 'guardian_birth_place', 'guardian_birth_date',
            'guardian_education_id', 'guardian_employment_id', 'guardian_monthly_income_id',
            'phone', 'mobile_phone', 'email',
            'height', 'weight', 'head_circumference', 'mileage', 'traveling_time',
            'sibling_number',
            'welfare_type', 'welfare_number', 'welfare_name',
        ];

        foreach ($fields as $field) {
            $value = $this->request->getPost($field);
            if ($value !== null) {
                $data[$field] = $value;
            }
        }

        return $data;
    }

    private function validation()
    {
        $this->validation->setRules([
            'is_transfer' => ['label' => 'Jenis Pendaftaran', 'rules' => 'trim|required|in_list[true,false]'],
            'admission_type_id' => ['label' => 'Jalur Pendaftaran', 'rules' => 'trim|is_natural_no_zero|required'],
            'full_name' => ['label' => 'Nama Lengkap', 'rules' => 'trim|required'],
            'gender' => ['label' => 'Jenis Kelamin', 'rules' => 'trim|required|in_list[M,F]'],
            'birth_date' => ['label' => 'Tanggal Lahir', 'rules' => 'trim|required|valid_date[Y-m-d]'],
            'district' => ['label' => 'Kabupaten', 'rules' => 'trim|required'],
            'declaration' => ['label' => 'Pernyataan', 'rules' => 'trim|required|in_list[true]'],
        ]);
        return $this->validation->withRequest($this->request)->run();
    }
}
