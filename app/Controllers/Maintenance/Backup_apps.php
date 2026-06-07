<?php

namespace App\Controllers\Maintenance;

class Backup_apps extends \App\Controllers\AdminController
{
    public function __construct()
    {
        if (!filter_var(session('is_super_admin'), FILTER_VALIDATE_BOOLEAN)) {
            return redirect()->to(base_url());
        }
    }

    public function index()
    {
        $zip = new \ZipArchive();
        $file_name = 'backup-app-on-' . date("Y-m-d-H-i-s") . '.zip';
        $file_path = WRITEPATH . 'backup/' . $file_name;
        if (!is_dir(WRITEPATH . 'backup')) {
            @mkdir(WRITEPATH . 'backup', 0777, true);
        }
        if ($zip->open($file_path, \ZipArchive::CREATE | \ZipArchive::OVERWRITE) === true) {
            $rootPath = realpath(FCPATH);
            $files = new \RecursiveIteratorIterator(
                new \RecursiveDirectoryIterator($rootPath),
                \RecursiveIteratorIterator::LEAVES_ONLY
            );
            foreach ($files as $name => $file) {
                if (!$file->isDir()) {
                    $filePath = $file->getRealPath();
                    $relativePath = substr($filePath, strlen($rootPath) + 1);
                    $zip->addFile($filePath, $relativePath);
                }
            }
            $zip->close();
        }
        return $this->response->download($file_path, null)->setFileName($file_name);
    }
}
