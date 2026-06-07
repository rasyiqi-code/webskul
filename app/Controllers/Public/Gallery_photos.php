<?php

namespace App\Controllers\Public;

use App\Controllers\PublicController;

class Gallery_photos extends PublicController
{
    public static $per_page = 6;

    public function index()
    {
        $m_albums = model('App\Models\M_albums');
        $total_rows = $m_albums->getAlbums()->getNumRows();
        $data['page_title'] = 'Galeri Foto';
        $data['total_page'] = ceil($total_rows / self::$per_page);
        $data['query'] = $m_albums->getAlbums(self::$per_page);
        $data['content'] = 'themes/' . theme_folder() . '/loop-albums';
        return view('themes/' . theme_folder() . '/index', $data);
    }

    public function get_albums()
    {
        if (!$this->request->isAJAX()) {
            return $this->response->setStatusCode(404);
        }
        $m_albums = model('App\Models\M_albums');
        $page_number = _toInteger($this->request->getPost('page_number'));
        $offset = ($page_number - 1) * self::$per_page;
        $query = $m_albums->getAlbums(self::$per_page, $offset);
        return $this->response->setContentType('application/json')->setJSON([
            'rows' => $query->getResult()
        ]);
    }

    public function preview()
    {
        if (!$this->request->isAJAX()) {
            return $this->response->setStatusCode(404);
        }
        $id = _toInteger($this->request->getPost('id'));
        $items = [];
        if (_isNaturalNumber($id)) {
            $m_photos = model('App\Models\M_photos');
            $query = $m_photos->get_photos($id);
            foreach ($query->getResult() as $row) {
                $items[] = [
                    'src' => base_url('media_library/albums/large/' . $row->photo_name)
                ];
            }
        }
        return $this->response->setContentType('application/json')->setJSON([
            'count' => count($items),
            'items' => $items
        ]);
    }
}
