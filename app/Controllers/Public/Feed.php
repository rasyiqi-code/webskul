<?php

namespace App\Controllers\Public;

use App\Controllers\PublicController;

class Feed extends PublicController
{
    public function index()
    {
        $m_posts = model('App\Models\Public\M_posts');
        $school_profile = session('school_profile');
        $general = session('general');

        $data['feed_name'] = $school_profile['website'] ?? '';
        $data['encoding'] = 'utf-8';
        $data['feed_url'] = base_url('feed');
        $data['page_description'] = $general['meta_description'] ?? '';
        $data['page_language'] = 'en-en';
        $data['creator_email'] = $school_profile['email'] ?? '';
        $data['posts'] = $m_posts->feed();

        $this->response->setHeader('Content-Type', 'text/xml; charset=utf-8');
        return view('feed', $data);
    }
}
