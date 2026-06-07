<?php

namespace App\Models;

use CodeIgniter\Model;

class M_users extends Model
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
}
