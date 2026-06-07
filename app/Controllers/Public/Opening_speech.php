<?php

namespace App\Controllers\Public;

use App\Controllers\PublicController;

class Opening_speech extends PublicController
{
    public function index()
    {
        $data['page_title'] = 'Sambutan Kepala Sekolah';
        $data['content'] = 'themes/' . theme_folder() . '/opening-speech';
        return view('themes/' . theme_folder() . '/index', $data);
    }
}
