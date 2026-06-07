<?php

namespace App\Controllers\Employees;

use App\Controllers\AdminController;

class Import extends AdminController
{
    public function __construct()
    {
        $this->pk = 'id';
        $this->table = 'users';
    }

    public function index()
    {
        $data['title'] = 'Import Guru dan Tenaga Kependidikan';
        $data['employees'] = $data['import_employees'] = true;
        $data['content'] = 'employees/import';
        return view('backend/index', $data);
    }

    public function save()
    {
        if ($this->request->isAJAX()) {
            $rows = explode("\n", $this->request->getPost('employees'));
            $db = db_connect();
            foreach ($rows as $row) {
                $split = explode("\t", $row);
                if (count($split) != 6) continue;
                $nik_exists = model('App\Models\M_helper')->is_exists('nik', trim($split[0]), 'users');
                if ($nik_exists) continue;
                $school_profile = session('school_profile');
                $website = str_replace(['http://', 'https://', 'www.'], '', rtrim($school_profile['website'] ?? '', '/'));
                $db->table('users')->set([
                    'nik' => trim($split[0]),
                    'full_name' => trim($split[1]),
                    'gender' => trim($split[2]) == 'L' ? 'M' : 'F',
                    'street_address' => trim($split[3]),
                    'birth_place' => trim($split[4]),
                    'birth_date' => trim($split[5]),
                    'email' => trim($split[0]) . '@' . $website,
                    'password' => password_hash(trim($split[5]), PASSWORD_BCRYPT, ['cost' => 12]),
                    'is_employee' => 'true',
                    'created_by' => session('user_id'),
                ])->insert();
            }
            $this->vars['status'] = 'success';
            $this->vars['message'] = 'Data Guru dan Tenaga Kependidikan sudah tersimpan';
            return $this->response->setContentType('application/json')->setJSON($this->vars);
        }
    }
}
