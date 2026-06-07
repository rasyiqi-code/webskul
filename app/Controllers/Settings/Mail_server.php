<?php

namespace App\Controllers\Settings;

class Mail_server extends \App\Controllers\AdminController
{
    protected $pk = 'id';
    protected $table = 'settings';

    public function __construct()
    {
        $this->m_settings = model('App\Models\M_settings');
    }

    public function index()
    {
        $this->vars['title'] = 'Pengaturan SMTP Server';
        $this->vars['settings'] = $this->vars['mail_server_settings'] = true;
        $this->vars['content'] = 'settings/mail_server';
        return view('backend/index', $this->vars);
    }

    public function pagination()
    {
        if ($this->request->isAJAX()) {
            $keyword = trim($this->request->getPost('keyword'));
            $page_number = _toInteger($this->request->getPost('page_number'));
            $limit = _toInteger($this->request->getPost('per_page'));
            $offset = ($page_number * $limit);
            $query = $this->m_settings->get_where($keyword, 'mail_server', 'rows', $limit, $offset);
            $total_rows = $this->m_settings->get_where($keyword, 'mail_server');
            $total_page = $limit > 0 ? ceil(_toInteger($total_rows) / _toInteger($limit)) : 1;
            $this->vars['total_page'] = _toInteger($total_page);
            $this->vars['total_rows'] = _toInteger($total_rows);
            $this->vars['rows'] = $query;
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
            'setting_value' => $this->request->getPost('setting_value')
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
