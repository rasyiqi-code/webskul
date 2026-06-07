<?php

namespace App\Controllers\Maintenance;

class Backup_database extends \App\Controllers\AdminController
{
    public function __construct()
    {
        if (!filter_var(session('is_super_admin'), FILTER_VALIDATE_BOOLEAN)) {
            return redirect()->to(base_url());
        }
    }

    public function index()
    {
        $db = db_connect();
        $prefs = [
            'tables' => [],
            'ignore' => [],
            'format' => 'txt',
            'filename' => 'backup-database-on-' . date("Y-m-d H-i-s") . '.sql',
            'add_drop' => true,
            'add_insert' => true,
            'newline' => "\n"
        ];
        $util = new \CodeIgniter\Database\DatabaseUtility();
        $backup = $util->backup($db, $prefs);
        $file_name = 'backup-database-on-' . date("Y-m-d-H-i-s") . '.sql';
        if (!is_dir(WRITEPATH . 'backup')) {
            @mkdir(WRITEPATH . 'backup', 0777, true);
        }
        write_file(WRITEPATH . 'backup/' . $file_name, $backup);
        return $this->response->download(WRITEPATH . 'backup/' . $file_name, null)->setFileName($file_name);
    }
}
