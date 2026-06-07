<?php

namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;

/**
 * Filter Privilege
 * Mengecek apakah user memiliki hak akses ke halaman berdasarkan segment URL.
 * Menggantikan cek privilege yang sebelumnya salah ditempatkan di initController().
 */
class PrivilegeFilter implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        $userPrivileges = session('user_privileges');
        $segment1       = service('uri')->getSegment(1);

        // Jika user tidak punya privilege untuk segment ini, redirect ke beranda
        if (!in_array($segment1, $userPrivileges ?? [])) {
            return redirect()->to(base_url());
        }
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // Tidak ada aksi setelah response
    }
}
