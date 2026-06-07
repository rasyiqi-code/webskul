<?php

namespace App\Controllers\Public;

use App\Controllers\PublicController;
use CodeIgniter\Exceptions\PageNotFoundException;

class Download extends PublicController
{
    public static $per_page = 20;

    public function index($slug = null)
    {
        $slug = $slug ?: service('uri')->getSegment(2);
        if (!alpha_dash($slug)) {
            throw PageNotFoundException::forPageNotFound();
        }

        $m_files = model('App\Models\M_files');
        $total_rows = $m_files->getFiles($slug)->getNumRows();

        $data['page_title'] = str_replace('-', ' ', $slug);
        $data['total_page'] = ceil($total_rows / self::$per_page);
        $data['query'] = $m_files->getFiles($slug, self::$per_page, 0);
        $data['content'] = 'themes/' . theme_folder() . '/loop-files';
        return view('themes/' . theme_folder() . '/index', $data);
    }

    public function get_files()
    {
        if (!$this->request->isAJAX()) {
            return $this->response->setStatusCode(404);
        }

        $slug = $this->request->getPost('slug');
        $page_number = _toInteger($this->request->getPost('page_number'));
        $offset = ($page_number - 1) * self::$per_page;

        $rows = [];
        if (alpha_dash($slug)) {
            $m_files = model('App\Models\M_files');
            $query = $m_files->getFiles($slug, self::$per_page, $offset);
            $rows = $query->getResult();
        }

        return $this->response->setContentType('application/json')->setJSON([
            'rows' => $rows
        ]);
    }

    public function force_download($id)
    {
        $id = _toInteger($id);
        if (!_isNaturalNumber($id)) {
            throw PageNotFoundException::forPageNotFound();
        }

        $helper = model('App\Models\M_helper');
        $query = $helper->rowObject('id', $id, 'files');

        if (!$query) {
            throw PageNotFoundException::forPageNotFound();
        }

        if ($query->file_visibility == 'private' && !session('active')) {
            throw PageNotFoundException::forPageNotFound();
        }

        $m_files = model('App\Models\M_files');
        $m_files->set_file_counter($id);

        $path = FCPATH . 'media_library/files/' . $query->file_name;
        $name = url_title(strtolower($query->file_title), '-') . $query->file_ext;

        return $this->response->download($path, null)->setFileName($name);
    }
}
