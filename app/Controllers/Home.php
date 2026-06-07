<?php

namespace App\Controllers;

class Home extends PublicController
{
    public function index()
    {
        $data['title'] = __session('school_profile')['website'] . ' | ' . __session('school_profile')['tagline'];
        $data['content'] = 'themes/' . theme_folder() . '/home';
        return view('themes/' . theme_folder() . '/index', $data);
    }
}
