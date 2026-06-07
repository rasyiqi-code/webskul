<?php

namespace App\Controllers;

class Dashboard extends AdminController
{
    public function initController($request, $response, $logger)
    {
        parent::initController($request, $response, $logger);
    }

    public function index()
    {
        // Bersihkan session expired hanya saat memuat halaman dashboard utama
        model('App\Models\M_helper')->clearExpiredSession();

        $data['title'] = 'Dashboard';
        $data['dashboard'] = true;
        $data['widget_box'] = model('App\Models\M_dashboard')->widgetBox();
        $data['last_active'] = model('App\Models\M_users')->getLastLogin();
        $data['recent_comments'] = model('App\Models\M_post_comments')->getRecentComments(5);
        $data['content'] = 'dashboard';
        return view('backend/index', $data);
    }

    public function sidebarCollapse()
    {
        $collapse = session('sidebar_collapse') ? false : true;
        session()->set('sidebar_collapse', $collapse);
    }

    /**
     * Alias snake_case untuk kompatibilitas dengan JS (helper.js) yang
     * memanggil `dashboard/sidebar_collapse` melalui auto-routing.
     */
    public function sidebar_collapse()
    {
        return $this->sidebarCollapse();
    }

    public function printSessions()
    {
        if (!filter_var(session('is_super_admin'), FILTER_VALIDATE_BOOLEAN)) {
            return redirect()->to(base_url());
        }
        dd(session()->get());
    }

    /**
     * Alias snake_case untuk kompatibilitas auto-routing CI3-style.
     */
    public function print_sessions()
    {
        return $this->printSessions();
    }
}
