<?php

namespace App\Controllers\Employees;

use App\Controllers\AdminController;

class Employees extends AdminController
{
    public function __construct()
    {
        $this->pk = 'id';
        $this->table = 'users';
    }

    public function index()
    {
        $data['title'] = 'Guru dan Tenaga Kependidikan';
        $data['employees'] = $data['all_employees'] = true;
        $data['content'] = 'employees/employees';
        return view('backend/index', $data);
    }

    public function pagination()
    {
        if ($this->request->isAJAX()) {
            $keyword = trim($this->request->getPost('keyword'));
            $page_number = _toInteger($this->request->getPost('page_number'));
            $limit = _toInteger($this->request->getPost('per_page'));
            $offset = ($page_number * $limit);
            $m_employees = model('App\Models\M_employees');
            $query = $m_employees->get_where($keyword, 'rows', $limit, $offset);
            $total_rows = $m_employees->get_where($keyword);
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
            if ($this->validation($id)) {
                $dataset = $this->dataset();
                $query = model('App\Models\M_helper')->upsert($id, $this->table, $dataset);
                if ($query) model('App\Models\M_users')->activate_account($dataset['email']);
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
            'is_employee' => 'true',
            'full_name' => $this->request->getPost('full_name'),
            'gender' => $this->request->getPost('gender'),
            'nik' => $this->request->getPost('nik') ?? null,
            'birth_place' => $this->request->getPost('birth_place') ?? null,
            'birth_date' => $this->request->getPost('birth_date') ?? null,
            'street_address' => $this->request->getPost('street_address') ?? null,
            'rt' => $this->request->getPost('rt') ?? null,
            'rw' => $this->request->getPost('rw') ?? null,
            'sub_village' => $this->request->getPost('sub_village') ?? null,
            'village' => $this->request->getPost('village') ?? null,
            'sub_district' => $this->request->getPost('sub_district') ?? null,
            'district' => $this->request->getPost('district') ?? null,
            'postal_code' => $this->request->getPost('postal_code') ?? null,
            'religion_id' => _toInteger($this->request->getPost('religion_id')),
            'citizenship' => $this->request->getPost('citizenship') ?? null,
            'country' => $this->request->getPost('country') ?? null,
            'employment_type_id' => _toInteger($this->request->getPost('employment_type_id')),
            'phone' => $this->request->getPost('phone') ?? null,
            'mobile_phone' => $this->request->getPost('mobile_phone') ?? null,
            'email' => $this->request->getPost('email') ?? null,
        ];
    }

    private function validation($id = 0)
    {
        $this->validation->setRules([
            'full_name' => ['label' => 'Full Name', 'rules' => 'trim|required'],
            'nik' => ['label' => 'NIK', 'rules' => 'trim|required'],
            'email' => ['label' => 'Email', 'rules' => 'trim|required|valid_email'],
        ]);
        return $this->validation->withRequest($this->request)->run();
    }

    public function employee_reports()
    {
        if ($this->request->isAJAX()) {
            $query = model('App\Models\M_employees')->employee_reports();
            return $this->response->setContentType('application/json')->setJSON($query->getResult());
        }
    }
}
