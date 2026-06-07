<?php

namespace App\Controllers\Public;

use App\Controllers\PublicController;

class Search extends PublicController
{
    public function index()
    {
        if ($this->request->getMethod() !== 'post') {
            $keyword = session('keyword');
            if ($keyword) {
                $m_posts = model('App\Models\Public\M_posts');
                $data['query'] = $m_posts->search($keyword);
                $data['page_title'] = 'Hasil pencarian dengan kata kunci "' . $keyword . '"';
            } else {
                $data['query'] = false;
                $data['page_title'] = 'Halaman Pencarian';
            }
        } else {
            $this->validation->setRules([
                'keyword' => ['label' => 'Kata Kunci Pencarian', 'rules' => 'trim|required|alpha_numeric_space|max_length[100]'],
            ]);

            if (!$this->validation->withRequest($this->request)->run()) {
                session()->remove('keyword');
                $data['query'] = false;
                $data['page_title'] = $this->validation->getErrors();
            } else {
                $keyword = trim(strip_tags($this->request->getPost('keyword')));
                session()->set('keyword', $keyword);
                $data['page_title'] = 'Hasil pencarian dengan kata kunci "' . session('keyword') . '"';
                $m_posts = model('App\Models\Public\M_posts');
                $data['query'] = $m_posts->search($keyword);
            }
        }

        $data['content'] = 'themes/' . theme_folder() . '/search-results';
        return view('themes/' . theme_folder() . '/index', $data);
    }
}
