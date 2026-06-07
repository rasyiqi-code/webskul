<?php

namespace App\Models;

use App\Models\BaseModel;

class M_users extends BaseModel
{
    protected $table = 'users';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'email', 'password', 'full_name', 'user_group_id', 'is_super_admin',
        'is_admin', 'is_employee', 'is_prospective_student', 'is_student',
        'is_alumni', 'employment_type_id', 'last_active', 'is_deleted'
    ];

    public function verify($email)
    {
        return $this->select('id, email, password, full_name, is_super_admin, is_admin, is_employee, is_prospective_student, is_student, is_alumni, user_group_id, employment_type_id')
            ->where('email', $email)
            ->where('is_deleted', 'false')
            ->get()
            ->getResultArray();
    }

    public function get_user_by_email($email)
    {
        $row = $this->select('id, email, full_name, user_group_id')
            ->where('email', $email)
            ->where('is_deleted', 'false')
            ->get()
            ->getRowArray();
        return $row ?: null;
    }

    public function lastActive($id)
    {
        return $this->update($id, ['last_active' => date('Y-m-d H:i:s')]);
    }

    public function getAttempts($ip_address)
    {
        return $this->db->table('login_attempts')
            ->select('counter, updated_at')
            ->where('ip_address', $ip_address)
            ->get()
            ->getRow();
    }

    public function increaseLoginAttempts($ip_address)
    {
        $builder = $this->db->table('login_attempts');
        $query = $builder->where('ip_address', $ip_address)->get()->getRow();
        if ($query) {
            $builder->where('ip_address', $ip_address)
                ->update([
                    'counter' => (int)$query->counter + 1,
                    'updated_at' => date('Y-m-d H:i:s')
                ]);
        } else {
            $builder->insert([
                'ip_address' => $ip_address,
                'counter' => 1,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ]);
        }
    }

    public function resetAttempts($ip_address)
    {
        return $this->db->table('login_attempts')
            ->where('ip_address', $ip_address)
            ->delete();
    }

    public function getLastLogin()
    {
        return $this->select('full_name, last_active')
            ->where('is_deleted', 'false')
            ->where('last_active IS NOT NULL')
            ->orderBy('last_active', 'DESC')
            ->limit(10)
            ->get()
            ->getResult();
    }

    /**
     * CI3-style get_where dengan filter kategori user.
     * Pemakaian: $this->m_users->get_where('admin|employee|student', $keyword, 'rows', $limit, $offset)
     */
    public function getWhere(string $userGroup = 'admin', string $keyword = '', string $returnType = 'count', int $limit = 0, int $offset = 0)
    {
        $builder = $this->db->table($this->table . ' x1');
        $builder->select('
            x1.id, x1.email, x1.full_name, x1.user_group_id, x2.user_group,
            x1.is_super_admin, x1.is_admin, x1.is_employee, x1.is_student, x1.is_deleted, x1.last_active
        ');
        $builder->join('user_groups x2', 'x1.user_group_id = x2.id', 'LEFT');

        if ($userGroup === 'admin') {
            $builder->where('x1.is_admin', 'true');
        } elseif ($userGroup === 'employee') {
            $builder->where('x1.is_employee', 'true');
        } elseif ($userGroup === 'student') {
            $builder->where('x1.is_student', 'true');
        } elseif ($userGroup === 'teacher') {
            $builder->where('x1.is_employee', 'true');
            $builder->where('x1.employment_type_id IS NOT NULL');
        }

        if (!empty($keyword)) {
            $builder->groupStart();
            $builder->like('x1.email', $keyword);
            $builder->orLike('x1.full_name', $keyword);
            $builder->groupEnd();
        }

        $builder->where('x1.is_deleted', 'false');

        if ($returnType === 'count') return $builder->countAllResults();
        if ($limit > 0) $builder->limit($limit, $offset);
        return $builder->get();
    }

    /**
     * CI3-style dropdown untuk pilih user.
     * Pemakaian: $this->m_users->dropdown('teacher')
     */
    public function dropdown(string $userGroup = 'admin')
    {
        $data = [];
        $builder = $this->db->table($this->table . ' x1');
        $builder->select('x1.id, x1.full_name');
        if ($userGroup === 'admin') {
            $builder->where('x1.is_admin', 'true');
        } elseif ($userGroup === 'employee') {
            $builder->where('x1.is_employee', 'true');
        } elseif ($userGroup === 'student') {
            $builder->where('x1.is_student', 'true');
        } elseif ($userGroup === 'teacher') {
            $builder->where('x1.is_employee', 'true');
            $builder->where('x1.employment_type_id IS NOT NULL');
        }
        $builder->where('x1.is_deleted', 'false');
        $builder->orderBy('x1.full_name', 'ASC');
        $query = $builder->get();
        foreach ($query->getResult() as $row) {
            $data[$row->id] = $row->full_name;
        }
        return $data;
    }
}
