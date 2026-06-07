<?php

namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;

/**
 * Filter Maintenance
 * Mengalihkan pengunjung ke halaman under-construction jika situs dalam mode pemeliharaan.
 * Menggantikan logika redirect yang sebelumnya salah ditempatkan di PublicController::initController().
 */
class MaintenanceFilter implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        $general  = session('general');
        $segment1 = service('uri')->getSegment(1);

        // Lewati cek jika tidak ada setting, sudah di halaman under-construction, atau halaman login
        $excludedSegments = ['under-construction', 'login', 'logout'];
        if (in_array($segment1, $excludedSegments)) {
            return;
        }

        if ($general === null || !isset($general['site_maintenance'])) {
            return;
        }

        $isMaintenanceMode = filter_var($general['site_maintenance'], FILTER_VALIDATE_BOOLEAN);
        $endDate           = $general['site_maintenance_end_date'] ?? null;

        if ($isMaintenanceMode && $endDate !== null && $endDate >= date('Y-m-d')) {
            return redirect()->to(site_url('under-construction'));
        }
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // Tidak ada aksi setelah response
    }
}
