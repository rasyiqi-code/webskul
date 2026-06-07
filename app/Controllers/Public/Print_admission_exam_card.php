<?php

namespace App\Controllers\Public;

use App\Controllers\PublicController;
use CodeIgniter\Exceptions\PageNotFoundException;

class Print_admission_exam_card extends PublicController
{
    protected $m_registrants;

    public function initController($request, $response, $logger)
    {
        parent::initController($request, $response, $logger);
        $this->m_registrants = model('App\Models\M_registrants');
    }

    public function index()
    {
        $data['page_title'] = 'Cetak Kartu Ujian Penerimaan Peserta Didik Baru Tahun ' . session('admission_year');
        $data['button'] = 'Cetak Kartu Ujian';
        $data['onclick'] = 'print_admission_exam_card()';
        $data['content'] = 'themes/' . theme_folder() . '/admission-search-form';
        return view('themes/' . theme_folder() . '/index', $data);
    }

    public function process()
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

        $admission = session('admission');
        $print_start = $admission['print_exam_card_start_date'] ?? null;
        $print_end = $admission['print_exam_card_end_date'] ?? null;

        if ($print_start && $print_end) {
            $date_range = array_date($print_start, $print_end);
            if (!in_array(date('Y-m-d'), $date_range)) {
                return $this->response->setContentType('application/json')->setJSON([
                    'status' => 'warning',
                    'message' => 'Kartu peserta ujian tes tulis dapat dicetak mulai tanggal ' . indo_date($print_start) . ' sampai dengan tanggal ' . indo_date($print_end),
                ]);
            }
        }

        $status = 'error';
        $message = '';
        $id = 0;
        $reg_no = '';
        $bdate = '';

        if ($this->validation()) {
            $registration_number = strip_tags($this->request->getPost('registration_number'));
            $birth_date = strip_tags($this->request->getPost('birth_date'));

            if (_isValidDate($birth_date) && strlen($registration_number) == 10 && ctype_digit($registration_number)) {
                $res = $this->m_registrants->find_registrant($registration_number, $birth_date);
                if (is_null($res)) {
                    $status = 'warning';
                    $message = 'Data dengan tanggal lahir ' . indo_date($birth_date) . ' dan nomor pendaftaran ' . $registration_number . ' tidak ditemukan.';
                } else {
                    $helper = model('App\Models\M_helper');
                    $student = $helper->rowObject('id', _toInteger($res['id']), 'users');
                    if ($student && filter_var($student->re_registration, FILTER_VALIDATE_BOOLEAN)) {
                        $status = 'success';
                        $id = $res['id'];
                        $reg_no = $res['registration_number'];
                        $bdate = $res['birth_date'];
                    } else {
                        $status = 'warning';
                        $message = 'Anda belum melakukan pendaftaran ulang!';
                    }
                }
            } else {
                $status = 'error';
                $message = 'Format data yang anda masukan tidak benar.';
            }
        } else {
            $status = 'validation_errors';
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

    public function pdf($id, $registration_number, $birth_date)
    {
        $id = _toInteger($id);
        if (!_isNaturalNumber($id)) {
            throw PageNotFoundException::forPageNotFound();
        }

        $helper = model('App\Models\M_helper');
        $query = $helper->rowObject('id', $id, 'users');

        if (!is_object($query)) {
            throw PageNotFoundException::forPageNotFound();
        }

        $res = $this->m_registrants->find_registrant($registration_number, $birth_date);
        if (is_null($res)) {
            throw PageNotFoundException::forPageNotFound();
        }
        if (!filter_var($res['is_prospective_student'], FILTER_VALIDATE_BOOLEAN)) {
            throw PageNotFoundException::forPageNotFound();
        }
        if ((int)$id !== (int)$res['id']) {
            throw PageNotFoundException::forPageNotFound();
        }

        $m_admission_exam_schedule_details = model('App\Models\M_admission_exam_schedule_details');
        $schedules = $m_admission_exam_schedule_details->get_student_exam_schedules($id);

        if (!is_object($schedules)) {
            throw PageNotFoundException::forPageNotFound();
        }

        $file_name = 'kartu-peserta-ujian-penerimaan-peserta-didik-baru-tahun-';
        $file_name .= substr($query->registration_number, 0, 4) . '-' . $query->registration_number . '.pdf';

        $exam_cards = new \App\Libraries\Exam_cards();
        return $exam_cards->pdf($res, $schedules, $file_name);
    }

    private function validation()
    {
        $this->validation->setRules([
            'registration_number' => ['label' => 'Nomor Pendaftaran', 'rules' => 'trim|required|numeric|max_length[10]|min_length[10]'],
            'birth_date' => ['label' => 'Tanggal Lahir', 'rules' => 'trim|required|valid_date[Y-m-d]'],
        ]);
        return $this->validation->withRequest($this->request)->run();
    }
}
