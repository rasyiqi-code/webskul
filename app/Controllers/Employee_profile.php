<?php

namespace App\Controllers;

class Employee_profile extends AdminController
{
    protected $pk = 'id';
    protected $table = 'users';

    public function __construct()
    {
        $this->m_users = model('App\Models\M_users');
    }

    public function index()
    {
        helper('form');
        $id = session('user_id') ?? 0;
        $this->vars['title'] = 'Biodata';
        $this->vars['employee_profile'] = true;
        $this->vars['religions'] = ['' => 'Pilih :'] + get_options('religion', false);
        $this->vars['employment_types'] = ['' => 'Pilih :'] + get_options('employment_type', false);
        $this->vars['query'] = model('App\Models\M_helper')->rowObject('id', $id, 'users');
        $this->vars['content'] = 'employee_profile';
        return view('backend/index', $this->vars);
    }

    public function save()
    {
        if ($this->request->isAJAX()) {
            $id = _toInteger(session('user_id'));
            if ($this->validation($id)) {
                $dataset = $this->dataset();
                $this->vars['status'] = model('App\Models\M_helper')->updateData($id, 'users', $dataset) ? 'success' : 'error';
                $this->vars['message'] = $this->vars['status'] == 'success' ? 'updated' : 'not_updated';
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
            'full_name' => $this->request->getPost('full_name'),
            'gender' => $this->request->getPost('gender'),
            'nik' => $this->request->getPost('nik') ? $this->request->getPost('nik') : null,
            'birth_place' => $this->request->getPost('birth_place'),
            'birth_date' => $this->request->getPost('birth_date'),
            'mother_name' => $this->request->getPost('mother_name'),
            'street_address' => $this->request->getPost('street_address'),
            'rt' => $this->request->getPost('rt'),
            'rw' => $this->request->getPost('rw'),
            'sub_village' => $this->request->getPost('sub_village'),
            'village' => $this->request->getPost('village'),
            'sub_district' => $this->request->getPost('sub_district'),
            'district' => $this->request->getPost('district'),
            'postal_code' => $this->request->getPost('postal_code'),
            'religion_id' => _toInteger($this->request->getPost('religion_id')),
            'citizenship' => $this->request->getPost('citizenship'),
            'country' => $this->request->getPost('country'),
            'employment_type_id' => _toInteger($this->request->getPost('employment_type_id')),
            'phone' => $this->request->getPost('phone'),
            'mobile_phone' => $this->request->getPost('mobile_phone'),
            'email' => $this->request->getPost('email') ? $this->request->getPost('email') : null
        ];
    }

    private function validation($id)
    {
        $this->validation->setRules([
            'full_name' => ['label' => 'Nama Lengkap', 'rules' => 'trim|required'],
            'nik' => ['label' => 'NIK', 'rules' => 'trim|required'],
            'email' => ['label' => 'Email', 'rules' => 'trim|required|valid_email'],
            'rt' => ['label' => 'RT', 'rules' => 'trim|numeric'],
            'rw' => ['label' => 'RW', 'rules' => 'trim|numeric'],
            'postal_code' => ['label' => 'Kode Pos', 'rules' => 'trim|numeric'],
        ]);
        return $this->validation->withRequest($this->request)->run();
    }

    public function email_exists($email = '', $id = 0)
    {
        $email_exists = $this->m_users->email_exists($email, $id);
        if ($email_exists) {
            $this->validation->setRule('email', 'Email', 'is_unique[users.email]');
            return false;
        }
        return true;
    }
}
