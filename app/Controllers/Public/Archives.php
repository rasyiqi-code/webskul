<?php

namespace App\Controllers\Public;

use App\Controllers\PublicController;
use CodeIgniter\Exceptions\PageNotFoundException;

class Archives extends PublicController
{
    public function index($year, $month)
    {
        $year = substr($year, 0, 4);
        $month = substr($month, 0, 2);

        if (!$year || !$month) {
            throw PageNotFoundException::forPageNotFound();
        }

        $m_posts = model('App\Models\Public\M_posts');
        $reading = session('reading');
        $post_per_page = $reading['post_per_page'] ?? 5;

        $total_rows = $m_posts->getPostArchives($year, $month)->getNumRows();

        $data['page_title'] = 'Arsip Bulan ' . bulan($month) . ' ' . $year;
        $data['total_page'] = ceil($total_rows / $post_per_page);
        $data['query'] = $m_posts->getPostArchives($year, $month, $post_per_page);
        $data['content'] = 'themes/' . theme_folder() . '/loop-posts';
        return view('themes/' . theme_folder() . '/index', $data);
    }

    public function get_posts()
    {
        if (!$this->request->isAJAX()) {
            return $this->response->setStatusCode(404);
        }

        $year = substr($this->request->getPost('year'), 0, 4);
        $month = substr($this->request->getPost('month'), 0, 2);
        $page_number = _toInteger($this->request->getPost('page_number'));
        $reading = session('reading');
        $post_per_page = $reading['post_per_page'] ?? 5;
        $offset = ($page_number - 1) * $post_per_page;

        $m_posts = model('App\Models\Public\M_posts');
        $query = $m_posts->getPostArchives($year, $month, $post_per_page, $offset);

        return $this->response->setContentType('application/json')->setJSON([
            'rows' => $query->getResult()
        ]);
    }
}
