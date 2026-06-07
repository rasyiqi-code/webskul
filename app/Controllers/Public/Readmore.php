<?php

namespace App\Controllers\Public;

use App\Controllers\PublicController;
use CodeIgniter\Exceptions\PageNotFoundException;

class Readmore extends PublicController
{
    public function index($id, $slug = null)
    {
        $id = _toInteger($id);
        if (!_isNaturalNumber($id)) {
            throw PageNotFoundException::forPageNotFound();
        }

        $helper = model('App\Models\M_helper');
        $query = $helper->rowObject('id', $id, 'posts');

        if (!$query || filter_var($query->is_deleted, FILTER_VALIDATE_BOOLEAN)) {
            return redirect()->to(base_url());
        }
        if ($query->post_status == 'draft') {
            return redirect()->to(base_url());
        }
        if ($query->post_visibility == 'private' && !session('active')) {
            return redirect()->to(base_url());
        }

        $m_posts = model('App\Models\Public\M_posts');
        $m_posts->setPostCounter($id);

        $m_post_comments = model('App\Models\M_post_comments');
        $limit = session('reading')['comment_per_page'];
        $total_rows = $m_post_comments->getPostComments($id)->getNumRows();
        $post_comments = $m_post_comments->getPostComments($id, $limit);

        $author = $helper->rowObject('id', $query->post_author, 'users');

        $data['page_title'] = $query->post_title;
        $data['query'] = $query;
        $data['post_counter'] = $query->post_counter;
        $data['post_type'] = $query->post_type === 'page' ? 'page' : 'post';
        $data['post_author'] = $author ? $author->full_name : '';
        $data['total_page'] = ceil(_toInteger($total_rows) / _toInteger($limit));
        $data['post_comments'] = $post_comments;
        $data['content'] = 'themes/' . theme_folder() . '/single-post';

        return view('themes/' . theme_folder() . '/index', $data);
    }
}
