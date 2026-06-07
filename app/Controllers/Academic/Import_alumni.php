<?php

namespace App\Controllers\Academic;

use App\Controllers\AdminController;

class Import_alumni extends AdminController
{
    public function __construct()
    {
        $this->pk = 'id';
        $this->table = 'users';
    }

    public function index()
    {
        $data['title'] = 'Import Alumni';
        $data['academic'] = $data['academic_import'] = $data['import_alumni'] = true;
        $data['content'] = 'academic/import_alumni';
        return view('backend/index', $data);
    }

    public function save()
    {
        if ($this->request->isAJAX()) {
            $rows = explode("\n", $this->request->getPost('students'));
            $db = db_connect();
            foreach ($rows as $row) {
                $split = explode("\t", $row);
                if (count($split) != 8) continue;
                $nis_exists = model('App\Models\M_helper')->is_exists('nis', trim($split[0]), 'users');
                if ($nis_exists) continue;
                $school_profile = session('school_profile');
                $website = str_replace(['http://', 'https://', 'www.'], '', rtrim($school_profile['website'] ?? '', '/'));
                $db->table('users')->set([
                    'nis' => trim($split[0]),
                    'full_name' => trim($split[1]),
                    'birth_date' => trim($split[2]),
                    'gender' => trim($split[3]) == 'L' ? 'M' : 'F',
                    'start_date' => trim($split[4]),
                    'end_date' => trim($split[5]),
                    'mobile_phone' => trim($split[6]),
                    'street_address' => trim($split[7]),
                    'is_alumni' => 'true',
                    'citizenship' => 'WNI',
                    'student_status_id' => get_option_id('student_status', 'lulus'),
                    'created_by' => session('user_id'),
                    'email' => trim($split[0]) . '@' . $website,
                    'password' => password_hash(trim($split[2]), PASSWORD_BCRYPT, ['cost' => 12]),
                ])->insert();
            }
            $this->vars['status'] = 'success';
            $this->vars['message'] = 'Data alumni sudah tersimpan';
            return $this->response->setContentType('application/json')->setJSON($this->vars);
        }
    }
}
