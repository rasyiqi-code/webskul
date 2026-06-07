<?php

namespace App\Models;

use CodeIgniter\Model;

class M_user_privileges extends Model
{
    protected $table = 'user_privileges';

    public function get_user_privileges($user_group_id, $is_super_admin, $is_admin, $is_employee, $is_student)
    {
        if (filter_var($is_super_admin, FILTER_VALIDATE_BOOLEAN)) {
            return ['*'];
        }
        if (filter_var($is_admin, FILTER_VALIDATE_BOOLEAN)) {
            $result = $this->select('module_name')
                ->where('user_group_id', $user_group_id)
                ->get()
                ->getResult();
            return array_column($result, 'module_name');
        }
        if (filter_var($is_employee, FILTER_VALIDATE_BOOLEAN)) {
            $result = $this->select('module_name')
                ->where('user_group_id', $user_group_id)
                ->get()
                ->getResult();
            return array_column($result, 'module_name');
        }
        if (filter_var($is_student, FILTER_VALIDATE_BOOLEAN)) {
            return ['profile', 'change-password', 'student-presence', 'scholarships', 'achievements'];
        }
        return [];
    }
}
