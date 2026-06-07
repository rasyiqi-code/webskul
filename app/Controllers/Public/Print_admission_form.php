<?php

namespace App\Controllers\Public;

use App\Controllers\PublicController;
use CodeIgniter\Exceptions\PageNotFoundException;

class Print_admission_form extends PublicController
{
    protected $m_registrants;

    public function initController($request, $response, $logger)
    {
        parent::initController($request, $response, $logger);
        $this->m_registrants = model('App\Models\M_registrants');
    }

    public function index()
    {
        $data['page_title'] = 'Cetak Formulir Penerimaan Peserta Didik Baru Tahun ' . session('admission_year');
        $data['button'] = 'Cetak Formulir';
        $data['onclick'] = 'print_admission_form()';
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
                    $status = 'success';
                    $id = $res['id'];
                    $reg_no = $res['registration_number'];
                    $bdate = $res['birth_date'];
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

        $file_name = 'FORMULIR-PPDB-TAHUN-' . substr($registration_number ?? '', 0, 4);
        $file_name .= '-' . $res['birth_date'] . '-' . $registration_number . '.pdf';

        $admission = new \App\Libraries\Admission();
        return $admission->pdf($res, $file_name);
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
