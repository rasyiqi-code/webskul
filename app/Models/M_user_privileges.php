<?php

namespace App\Models;

use App\Models\BaseModel;

class M_user_privileges extends BaseModel
{
    protected $table = 'user_privileges';

    public function get_user_privileges($user_group_id, $is_super_admin, $is_admin, $is_employee, $is_student)
    {
        $user_privileges = ['dashboard', 'change_password'];

        if (filter_var($is_super_admin, FILTER_VALIDATE_BOOLEAN)) {
            return ['*'];
        }

        if (filter_var($is_admin, FILTER_VALIDATE_BOOLEAN)) {
            array_push($user_privileges, 'profile');
            $query = $this->db->table($this->table . ' x1')
                ->select('x2.module_url')
                ->join('modules x2', 'x1.module_id = x2.id', 'LEFT')
                ->where('x1.user_group_id', $user_group_id)
                ->where('x1.is_deleted', 'false')
                ->get();
            foreach ($query->getResult() as $row) {
                if (!empty($row->module_url)) {
                    array_push($user_privileges, $row->module_url);
                }
            }
        }

        if (filter_var($is_employee, FILTER_VALIDATE_BOOLEAN)) {
            array_push($user_privileges, 'employee_profile', 'posts', 'teacher');
        }

        if (filter_var($is_student, FILTER_VALIDATE_BOOLEAN)) {
            array_push($user_privileges, 'student_profile', 'scholarships', 'achievements', 'posts', 'student_presence');
        }

        return $user_privileges;
    }

    public function getWhere(string $keyword = '', string $returnType = 'count', int $limit = 0, int $offset = 0)
    {
        $builder = $this->db->table($this->table . ' x1');
        $builder->select('x1.id, x1.user_group_id, x2.user_group, x1.module_id, x3.module_name, x1.is_deleted');
        $builder->join('user_groups x2', 'x1.user_group_id = x2.id', 'LEFT');
        $builder->join('modules x3', 'x1.module_id = x3.id', 'LEFT');
        if (!empty($keyword)) {
            $builder->groupStart();
            $builder->like('x2.user_group', $keyword);
            $builder->orLike('x3.module_name', $keyword);
            $builder->groupEnd();
        }
        if ($returnType === 'count') return $builder->countAllResults();
        if ($limit > 0) $builder->limit($limit, $offset);
        return $builder->get();
    }
}
