<?php

namespace App\Controllers;

use App\Libraries\Token;

/**
 * PublicController
 * Base controller untuk semua halaman publik.
 * Mode maintenance ditangani oleh MaintenanceFilter agar redirect berjalan benar.
 * Cache halaman tetap diatur di sini karena cachePage() hanya set header — tidak redirect.
 */
class PublicController extends BaseController
{
    public function initController($request, $response, $logger)
    {
        parent::initController($request, $response, $logger);

        // Set CSRF token untuk form publik
        $token = new Token();
        session()->set('csrf_token', $token->get_token());

        // Cache halaman jika diaktifkan di pengaturan
        $general = session('general');
        if ($general !== null && isset($general['site_cache'])) {
            if (
                filter_var($general['site_cache'], FILTER_VALIDATE_BOOLEAN) &&
                isset($general['site_cache_time']) &&
                (int) $general['site_cache_time'] > 0
            ) {
                $this->cachePage($general['site_cache_time']);
            }
        }
    }
}
