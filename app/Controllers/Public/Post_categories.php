<?php

namespace App\Controllers\Public;

use App\Controllers\PublicController;
use CodeIgniter\Exceptions\PageNotFoundException;

class Post_categories extends PublicController
{
    public function index($category_slug)
    {
        if (!alpha_dash($category_slug)) {
            throw PageNotFoundException::forPageNotFound();
        }

        $m_posts = model('App\Models\Public\M_posts');
        $reading = session('reading');
        $post_per_page = $reading['post_per_page'] ?? 5;

        $total_rows = $m_posts->getPostCategories($category_slug)->getNumRows();

        $data['page_title'] = strtoupper(str_replace('-', ' ', $category_slug));
        $data['total_page'] = ceil($total_rows / $post_per_page);
        $data['query'] = $m_posts->getPostCategories($category_slug, $post_per_page, 0);
        $data['content'] = 'themes/' . theme_folder() . '/loop-posts';
        return view('themes/' . theme_folder() . '/index', $data);
    }

    public function get_posts()
    {
        if (!$this->request->isAJAX()) {
            return $this->response->setStatusCode(404);
        }

        $category_slug = $this->request->getPost('category_slug');
        $page_number = _toInteger($this->request->getPost('page_number'));
        $reading = session('reading');
        $post_per_page = $reading['post_per_page'] ?? 5;
        $offset = ($page_number - 1) * $post_per_page;

        $rows = [];
        if (alpha_dash($category_slug)) {
            $m_posts = model('App\Models\Public\M_posts');
            $query = $m_posts->getPostCategories($category_slug, $post_per_page, $offset);
            $rows = $query->getResult();
        }

        return $this->response->setContentType('application/json')->setJSON([
            'rows' => $rows
        ]);
    }
}
