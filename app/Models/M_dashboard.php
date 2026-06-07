<?php

namespace App\Models;

use CodeIgniter\Model;

class M_dashboard extends Model
{
    public function widgetBox()
    {
        $db = db_connect();
        $data = [];

        $data['students'] = $db->table('students')
            ->where('is_deleted', 'false')
            ->where('is_alumni', 'false')
            ->countAllResults();

        $data['employees'] = $db->table('employees')
            ->where('is_deleted', 'false')
            ->countAllResults();

        $data['alumni'] = $db->table('students')
            ->where('is_deleted', 'false')
            ->where('is_alumni', 'true')
            ->countAllResults();

        $data['posts'] = $db->table('posts')
            ->where('is_deleted', 'false')
            ->countAllResults();

        $data['comments'] = $db->table('comments')
            ->where('is_deleted', 'false')
            ->countAllResults();

        $data['messages'] = $db->table('comments')
            ->where('comment_type', 'message')
            ->where('is_deleted', 'false')
            ->countAllResults();

        $data['registrants'] = $db->table('students')
            ->where('is_prospective_student', 'true')
            ->where('is_deleted', 'false')
            ->countAllResults();

        $data['subscribers'] = $db->table('subscribers')
            ->countAllResults();

        return $data;
    }
}
