<?php

namespace App\Controllers\Public;

use App\Controllers\PublicController;

class Admission_selection_results extends PublicController
{
    protected $m_registrants;
    protected $m_verification_subject_scores;

    public function initController($request, $response, $logger)
    {
        parent::initController($request, $response, $logger);

        $this->m_registrants = model('App\Models\M_registrants');
        $this->m_verification_subject_scores = model('App\Models\M_verification_subject_scores');
    }

    public function index()
    {
        $admission = session('admission');
        $start_date = $admission['announcement_start_date'] ?? null;
        $end_date = $admission['announcement_end_date'] ?? null;

        if ($start_date && $end_date) {
            $date_range = array_date($start_date, $end_date);
            if (!in_array(date('Y-m-d'), $date_range)) {
                return redirect()->to(base_url());
            }
        }

        $data['page_title'] = 'Hasil Seleksi Penerimaan Peserta Didik Baru Tahun ' . session('admission_year');
        $data['button'] = 'Lihat Hasil Seleksi';
        $data['onclick'] = 'selection_results()';
        $data['content'] = 'themes/' . theme_folder() . '/admission-search-form';
        return view('themes/' . theme_folder() . '/index', $data);
    }

    public function selection_results()
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
        $subject_scores = [];

        if ($this->validation()) {
            $registration_number = strip_tags($this->request->getPost('registration_number'));
            $birth_date = strip_tags($this->request->getPost('birth_date'));

            if (_isValidDate($birth_date) && strlen($registration_number) == 10 && ctype_digit($registration_number)) {
                $query = $this->m_registrants->selection_result($registration_number, $birth_date);
                $status = $query['status'];
                $message = $query['message'];
                $subject_scores = $this->m_verification_subject_scores->getSubjectScores($registration_number, $birth_date);
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
            'subject_scores' => $subject_scores,
        ]);
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
