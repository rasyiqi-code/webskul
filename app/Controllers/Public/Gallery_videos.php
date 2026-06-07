<?php

namespace App\Controllers\Public;

use App\Controllers\PublicController;

class Gallery_videos extends PublicController
{
    public static $per_page = 6;

    public function index()
    {
        $m_videos = model('App\Models\M_videos');
        $total_rows = $m_videos->getVideos()->getNumRows();
        $data['page_title'] = 'Galeri Video';
        $data['total_page'] = ceil($total_rows / self::$per_page);
        $data['query'] = $m_videos->getVideos(self::$per_page);
        $data['content'] = 'themes/' . theme_folder() . '/loop-videos';
        return view('themes/' . theme_folder() . '/index', $data);
    }

    public function get_videos()
    {
        if (!$this->request->isAJAX()) {
            return $this->response->setStatusCode(404);
        }
        $m_videos = model('App\Models\M_videos');
        $page_number = _toInteger($this->request->getPost('page_number'));
        $offset = ($page_number - 1) * self::$per_page;
        $query = $m_videos->getVideos(self::$per_page, $offset);
        return $this->response->setContentType('application/json')->setJSON([
            'rows' => $query->getResult()
        ]);
    }
}
