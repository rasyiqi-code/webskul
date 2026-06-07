<?php

namespace App\Controllers\Admission;

use App\Controllers\AdminController;

class Verification_subject_scores extends AdminController
{
    public function __construct()
    {
        $this->pk = 'id';
        $this->table = 'verification_subject_scores';
    }

    public function index()
    {
        $data['title'] = 'Verifikasi Nilai';
        $data['admission'] = $data['verification_subject_scores'] = true;
        $data['content'] = 'admission/verification_subject_scores';
        return view('backend/index', $data);
    }

    public function pagination()
    {
        if ($this->request->isAJAX()) {
            $keyword = trim($this->request->getPost('keyword'));
            $page_number = _toInteger($this->request->getPost('page_number'));
            $limit = _toInteger($this->request->getPost('per_page'));
            $offset = ($page_number * $limit);
            $m_verification_subject_scores = model('App\Models\M_verification_subject_scores');
            $query = $m_verification_subject_scores->get_where($keyword, 'rows', $limit, $offset);
            $total_rows = $m_verification_subject_scores->get_where($keyword);
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
            'score' => $this->request->getPost('score'),
        ];
    }

    private function validation()
    {
        $this->validation->setRules([
            'score' => ['label' => 'Skor', 'rules' => 'trim|required'],
        ]);
        return $this->validation->withRequest($this->request)->run();
    }
}
