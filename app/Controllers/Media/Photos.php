<?php

namespace App\Controllers\Media;

class Photos extends \App\Controllers\AdminController
{
    protected $pk = 'id';
    protected $table = 'photos';

    public function __construct()
    {
        $this->m_photos = model('App\Models\M_photos');
    }

    public function index()
    {
        $this->vars['title'] = 'Photo';
        $this->vars['media'] = $this->vars['albums'] = true;
        $this->vars['content'] = 'media/photos';
        return view('backend/index', $this->vars);
    }

    public function pagination()
    {
        if ($this->request->isAJAX()) {
            $keyword = trim($this->request->getPost('keyword'));
            $page_number = _toInteger($this->request->getPost('page_number'));
            $limit = _toInteger($this->request->getPost('per_page'));
            $offset = ($page_number * $limit);
            $query = $this->m_photos->get_where($keyword, 'rows', $limit, $offset);
            $total_rows = $this->m_photos->get_where($keyword);
            $total_page = $limit > 0 ? ceil(_toInteger($total_rows) / _toInteger($limit)) : 1;
            $this->vars['total_page'] = _toInteger($total_page);
            $this->vars['total_rows'] = _toInteger($total_rows);
            $this->vars['rows'] = is_array($query) || $query instanceof \CodeIgniter\Database\ResultInterface ? $query->getResult() : $query;
            return $this->response->setContentType('application/json')->setJSON($this->vars);
        }
    }

    public function delete()
    {
        if ($this->request->isAJAX()) {
            $ids = explode(',', $this->request->getPost($this->pk));
            $success = 0;
            foreach ($ids as $id) {
                $query = model('App\Models\M_helper')->rowObject($this->pk, $id, $this->table);
                if ($this->m_photos->delete_permanently($id)) {
                    @chmod(FCPATH . 'media_library/albums/large/' . $query->photo_name, 0777);
                    @chmod(FCPATH . 'media_library/albums/medium/' . $query->photo_name, 0777);
                    @chmod(FCPATH . 'media_library/albums/thumbnail/' . $query->photo_name, 0777);
                    @unlink(FCPATH . 'media_library/albums/large/' . $query->photo_name);
                    @unlink(FCPATH . 'media_library/albums/medium/' . $query->photo_name);
                    @unlink(FCPATH . 'media_library/albums/thumbnail/' . $query->photo_name);
                    $success++;
                }
            }
            $this->vars = [
                'action' => 'delete_permanently',
                'status' => 'info',
                'message' => $success . ' record deleted successfully.'
            ];
            return $this->response->setContentType('application/json')->setJSON($this->vars);
        }
    }
}
