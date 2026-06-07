<?php

namespace App\Controllers\Admission;

use App\Controllers\AdminController;

class Registrants_unapproved extends AdminController
{
    public function __construct()
    {
        $this->pk = 'id';
        $this->table = 'users';
    }

    public function index()
    {
        $data['title'] = 'Pendaftar Tidak Diterima';
        $data['admission'] = $data['registrants_unapproved'] = true;
        $options = [];
        $options['unset'] = 'Hapus Dari Hasil Seleksi';
        $options['unapproved'] = 'Tidak Diterima';
        $data['options'] = json_encode($options);
        $data['content'] = 'admission/registrants_unapproved';
        return view('backend/index', $data);
    }

    public function pagination()
    {
        if ($this->request->isAJAX()) {
            $keyword = trim($this->request->getPost('keyword'));
            $page_number = _toInteger($this->request->getPost('page_number'));
            $limit = _toInteger($this->request->getPost('per_page'));
            $offset = ($page_number * $limit);
            $m_registrants_unapproved = model('App\Models\M_registrants_unapproved');
            $query = $m_registrants_unapproved->get_where($keyword, 'rows', $limit, $offset);
            $total_rows = $m_registrants_unapproved->get_where($keyword);
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
        $selection_result = $this->request->getPost('selection_result');
        $dataset = [];
        $dataset['is_student'] = 'true';
        $dataset['selection_result'] = $selection_result == 'unset' ? null : $selection_result;
        if ($selection_result == 'unapproved' || $selection_result == 'unset') {
            $dataset['is_student'] = 'false';
        }
        return $dataset;
    }

    private function validation()
    {
        $this->validation->setRules([
            'selection_result' => ['label' => 'Selection Result', 'rules' => 'trim|required'],
        ]);
        return $this->validation->withRequest($this->request)->run();
    }

    public function admission_reports()
    {
        if ($this->request->isAJAX()) {
            $query = model('App\Models\M_registrants_unapproved')->admission_reports();
            return $this->response->setContentType('application/json')->setJSON($query->getResult());
        }
    }
}
