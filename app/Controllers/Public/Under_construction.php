<?php

namespace App\Controllers\Public;

use App\Controllers\BaseController;

class Under_construction extends BaseController
{
    public function index()
    {
        $general = session('general');
        if ($general !== null && isset($general['site_maintenance'])) {
            if (!filter_var($general['site_maintenance'], FILTER_VALIDATE_BOOLEAN)) {
                return redirect()->to(base_url());
            }
        }
        return view('under_construction');
    }
}
