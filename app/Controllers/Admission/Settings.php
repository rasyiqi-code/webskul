<?php

namespace App\Controllers\Admission;

use App\Controllers\AdminController;

class Settings extends AdminController
{
    public function __construct()
    {
        $this->pk = 'id';
        $this->table = 'settings';
    }

    public function index()
    {
        $data['title'] = 'Pengaturan Umum Penerimaan Peserta Didik Baru';
        $data['admission'] = $data['admission_settings'] = $data['admission_general_settings'] = true;
        $data['content'] = 'admission/settings';
        return view('backend/index', $data);
    }

    public function pagination()
    {
        if ($this->request->isAJAX()) {
            $keyword = trim($this->request->getPost('keyword'));
            $page_number = _toInteger($this->request->getPost('page_number'));
            $limit = _toInteger($this->request->getPost('per_page'));
            $offset = ($page_number * $limit);
            $m_settings = model('App\Models\M_settings');
            $query = $m_settings->get_where($keyword, 'admission', 'rows', $limit, $offset);
            $total_rows = $m_settings->get_where($keyword, 'admission');
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
                $query = model('App\Models\M_helper')->updateData($id, $this->table, $dataset);
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
            'setting_value' => $this->request->getPost('setting_value'),
        ];
    }

    private function validation()
    {
        $this->validation->setRules([
            'setting_value' => ['label' => 'Value', 'rules' => 'trim|required'],
        ]);
        return $this->validation->withRequest($this->request)->run();
    }
}
