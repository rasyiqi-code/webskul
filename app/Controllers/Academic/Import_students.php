<?php

namespace App\Controllers\Academic;

use App\Controllers\AdminController;

class Import_students extends AdminController
{
    public function __construct()
    {
        $this->pk = 'id';
        $this->table = 'users';
    }

    public function index()
    {
        $data['title'] = 'Import Peserta Didik';
        $data['academic'] = $data['academic_import'] = $data['import_students'] = true;
        $data['content'] = 'academic/import_students';
        return view('backend/index', $data);
    }

    public function save()
    {
        if ($this->request->isAJAX()) {
            $rows = explode("\n", $this->request->getPost('students'));
            $db = db_connect();
            foreach ($rows as $row) {
                $split = explode("\t", $row);
                if (count($split) != 6) continue;
                $nis_exists = model('App\Models\M_helper')->is_exists('nis', trim($split[0]), 'users');
                if ($nis_exists) continue;
                $school_profile = session('school_profile');
                $website = str_replace(['http://', 'https://', 'www.'], '', rtrim($school_profile['website'] ?? '', '/'));
                $db->table('users')->set([
                    'nis' => trim($split[0]),
                    'full_name' => trim($split[1]),
                    'gender' => trim($split[2]) == 'L' ? 'M' : 'F',
                    'street_address' => trim($split[3]),
                    'birth_place' => trim($split[4]),
                    'birth_date' => trim($split[5]),
                    'is_student' => 'true',
                    'citizenship' => 'WNI',
                    'student_status_id' => get_option_id('student_status', 'aktif'),
                    'email' => trim($split[0]) . '@' . $website,
                    'password' => password_hash(trim($split[5]), PASSWORD_BCRYPT, ['cost' => 12]),
                    'created_by' => session('user_id'),
                ])->insert();
            }
            $this->vars['status'] = 'success';
            $this->vars['message'] = 'Data Peserta Didik sudah tersimpan';
            return $this->response->setContentType('application/json')->setJSON($this->vars);
        }
    }
}
