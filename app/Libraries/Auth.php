<?php

namespace App\Libraries;

use App\Models\M_users;
use App\Models\M_user_privileges;
use App\Models\M_students;

/**
 * Library Auth
 * Menangani verifikasi login, pengecekan status login, dan pembatasan IP.
 * Method menggunakan konvensi camelCase sesuai standar CI4/PSR.
 */
class Auth
{
    /**
     * Verifikasi kredensial login user.
     */
    public function verify(string $email, string $password, string $ip_address): bool
    {
        if ($this->ipBanned($ip_address)) {
            return false;
        }

        $m_users = model('App\Models\M_users');
        $query   = $m_users->verify($email);

        if ($query && count($query) === 1) {
            $data = $query[0];
            if (password_verify($password, $data['password'])) {
                $m_user_privileges = model('App\Models\M_user_privileges');
                $session_data      = [
                    'user_id'                => $data['id'],
                    'email'                  => $data['email'],
                    'full_name'              => $data['full_name'],
                    'is_super_admin'         => filter_var($data['is_super_admin'], FILTER_VALIDATE_BOOLEAN),
                    'is_admin'               => filter_var($data['is_admin'], FILTER_VALIDATE_BOOLEAN),
                    'is_employee'            => filter_var($data['is_employee'], FILTER_VALIDATE_BOOLEAN),
                    'is_prospective_student' => filter_var($data['is_prospective_student'], FILTER_VALIDATE_BOOLEAN),
                    'is_student'             => filter_var($data['is_student'], FILTER_VALIDATE_BOOLEAN),
                    'is_alumni'              => filter_var($data['is_alumni'], FILTER_VALIDATE_BOOLEAN),
                    'active'                 => true,
                    'user_privileges'        => $m_user_privileges->get_user_privileges(
                        $data['user_group_id'],
                        $data['is_super_admin'],
                        $data['is_admin'],
                        $data['is_employee'],
                        $data['is_student']
                    ),
                ];

                // Tambahkan info tipe kepegawaian jika user adalah pegawai
                if (filter_var($data['is_employee'], FILTER_VALIDATE_BOOLEAN)) {
                    $employment_type_id = $data['employment_type_id'];
                    $helper             = model('App\Models\M_helper');
                    $employment_type    = $helper->rowObject('id', $employment_type_id, 'options');
                    if ($employment_type) {
                        $session_data['employment_type'] = $employment_type->option_name;
                        if (strpos(strtolower($employment_type->option_name), 'guru') !== false) {
                            $session_data['is_teacher'] = true;
                        }
                    }
                }

                session()->set($session_data);
                $this->updateLastActive($data['id']);
                $this->resetAttempts($ip_address);

                return true;
            }

            $this->increaseLoginAttempts($ip_address);
            return false;
        }

        $this->increaseLoginAttempts($ip_address);
        return false;
    }

    /**
     * Cek apakah user sudah login.
     */
    public function loggedIn(): bool
    {
        return (bool) session('active');
    }

    /**
     * Cek apakah IP address sedang dibanned karena terlalu banyak percobaan login.
     */
    public function ipBanned(string $ip_address): bool
    {
        $max_attempts = 3;
        $banned_time  = 600; // 10 menit dalam detik

        $query = model('App\Models\M_users')->getAttempts($ip_address);
        if (is_object($query) && isset($query->counter) && $query->counter >= $max_attempts) {
            $time_diff = time() - strtotime($query->updated_at);
            if ($time_diff >= $banned_time) {
                $this->resetAttempts($ip_address);
                return false;
            }
            return true;
        }

        return false;
    }

    private function updateLastActive(int $id): void
    {
        model('App\Models\M_users')->lastActive($id);
    }

    private function increaseLoginAttempts(string $ip_address): void
    {
        model('App\Models\M_users')->increaseLoginAttempts($ip_address);
    }

    private function resetAttempts(string $ip_address): void
    {
        model('App\Models\M_users')->resetAttempts($ip_address);
    }
}
