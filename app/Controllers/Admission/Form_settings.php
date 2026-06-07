<?php

namespace App\Controllers\Admission;

use App\Controllers\AdminController;

class Form_settings extends AdminController
{
    public function __construct()
    {
        $this->pk = 'id';
        $this->table = 'admission_form_settings';
    }

    public function index()
    {
        $data['admission'] = $data['admission_settings'] = $data['form_settings'] = true;
        $data['query'] = model('App\Models\M_form_settings')->get_rows();
        $data['content'] = 'admission/form_settings';
        return view('backend/index', $data);
    }

    public function save()
    {
        if ($this->request->isAJAX()) {
            $settings = json_decode($this->request->getPost('field_setting'));
            $success = 0;
            $error = 0;
            $db = db_connect();
            foreach ($settings as $values) {
                $field_setting = (array)$values;
                $id = $field_setting['id'];
                unset($field_setting['id']);
                $db->table($this->table)->set([
                    'field_setting' => json_encode($field_setting),
                    'updated_by' => session('user_id'),
                ])->where($this->pk, $id)->update();
                $query = $db->affectedRows();
                $query ? $success++ : $error++;
            }
            $this->vars['message'] = $success . ' record berhasil diperbaharui' . ($error > 0 ? ', dan ' . $error . ' record gagal diperbaharui.' : '');
            return $this->response->setContentType('application/json')->setJSON($this->vars);
        }
    }
}
