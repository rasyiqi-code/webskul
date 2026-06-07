<?php

namespace App\Controllers\Maintenance;

use App\Controllers\AdminController;

class Backup_database extends AdminController
{
    public function __construct()
    {
        $this->pk = 'id';
    }

    public function index()
    {
        if (!filter_var(session('is_super_admin'), FILTER_VALIDATE_BOOLEAN)) {
            return redirect()->to(base_url());
        }

        $file_name = 'backup-database-on-' . date('Y-m-d-H-i-s') . '.sql';
        $output = "-- CodeIgniter DB Backup\n"
                . "-- Generated: " . date('Y-m-d H:i:s') . "\n"
                . "-- Host: " . (string) $_SERVER['SERVER_NAME'] . "\n\n"
                . "SET FOREIGN_KEY_CHECKS=0;\n\n";

        $db = \Config\Database::connect();
        $tables = $db->listTables();

        foreach ($tables as $table) {
            $output .= "-- ----------------------------\n";
            $output .= "-- Table structure for `$table`\n";
            $output .= "-- ----------------------------\n";
            $output .= "DROP TABLE IF EXISTS `$table`;\n";

            $create = $db->query("SHOW CREATE TABLE `$table`")->getRowArray();
            $createStmt = $create['Create Table'] ?? null;
            if ($createStmt === null) {
                foreach ($create as $k => $v) {
                    if (stripos($k, 'create') !== false) { $createStmt = $v; break; }
                }
            }
            if ($createStmt) {
                $output .= $createStmt . ";\n\n";
            }

            $rows = $db->table($table)->get()->getResultArray();
            if (!empty($rows)) {
                $output .= "-- Dumping data for `$table`\n";
                $columns = array_keys($rows[0]);
                $colList = '`' . implode('`, `', $columns) . '`';
                foreach ($rows as $row) {
                    $vals = array_map(function ($v) use ($db) {
                        if ($v === null) return 'NULL';
                        return $db->escape($v);
                    }, $row);
                    $output .= "INSERT INTO `$table` ($colList) VALUES (" . implode(', ', $vals) . ");\n";
                }
                $output .= "\n";
            }
        }

        $output .= "SET FOREIGN_KEY_CHECKS=1;\n";

        if (!is_dir(WRITEPATH . 'backup')) {
            @mkdir(WRITEPATH . 'backup', 0777, true);
        }
        write_file(WRITEPATH . 'backup/' . $file_name, $output);
        return $this->response->download(WRITEPATH . 'backup/' . $file_name, null)->setFileName($file_name);
    }
}
