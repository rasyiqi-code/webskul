<?php

namespace App\Controllers\Plugins;

class Banners extends \App\Controllers\AdminController
{
    protected $pk = 'id';
    protected $table = 'links';

    public function __construct()
    {
        $this->m_banners = model('App\Models\M_banners');
    }

    public function index()
    {
        $this->vars['title'] = 'Iklan';
        $this->vars['plugins'] = $this->vars['banners'] = true;
        $this->vars['content'] = 'plugins/banners';
        return view('backend/index', $this->vars);
    }

    public function pagination()
    {
        if ($this->request->isAJAX()) {
            $keyword = trim($this->request->getPost('keyword'));
            $page_number = _toInteger($this->request->getPost('page_number'));
            $limit = _toInteger($this->request->getPost('per_page'));
            $offset = ($page_number * $limit);
            $query = $this->m_banners->get_where($keyword, 'rows', $limit, $offset);
            $total_rows = $this->m_banners->get_where($keyword);
            $total_page = $limit > 0 ? ceil(_toInteger($total_rows) / _toInteger($limit)) : 1;
            $this->vars['total_page'] = _toInteger($total_page);
            $this->vars['total_rows'] = _toInteger($total_rows);
            $this->vars['rows'] = is_array($query) || $query instanceof \CodeIgniter\Database\ResultInterface ? $query->getResult() : $query;
            return $this->response->setContentType('application/json')->setJSON($this->vars);
        }
    }

    public function save()
    {
        if ($this->request->isAJAX()) {
            $id = _toInteger($this->request->getPost('id'));
            if ($this->validation()) {
                $dataset = $this->dataset();
                $query = model('App\Models\M_helper')->upsert($id, $this->table, $dataset);
                $this->vars['status'] = $query ? 'success' : 'error';
                $this->vars['message'] = $query ? 'Data Anda berhasil disimpan.' : 'Terjadi kesalahan dalam menyimpan data';
            } else {
                $this->vars['status'] = 'error';
                $this->vars['message'] = $this->validation->getErrors();
            }
            return $this->response->setContentType('application/json')->setJSON($this->vars);
        }
    }

    private function dataset()
    {
        return [
            'link_type' => 'banner',
            'link_url' => prep_url($this->request->getPost('link_url')),
            'link_title' => $this->request->getPost('link_title'),
            'link_target' => $this->request->getPost('link_target')
        ];
    }

    private function validation()
    {
        $this->validation->setRules([
            'link_url' => ['label' => 'URL', 'rules' => 'trim|required|valid_url'],
            'link_title' => ['label' => 'Keterangan', 'rules' => 'trim|required'],
            'link_target' => ['label' => 'Target', 'rules' => 'trim|required'],
        ]);
        return $this->validation->withRequest($this->request)->run();
    }

    public function upload()
    {
        if ($this->request->isAJAX()) {
            $id = _toInteger($this->request->getPost('id'));
            if (_isNaturalNumber($id)) {
                $query = model('App\Models\M_helper')->rowObject($this->pk, $id, $this->table);
                $file_name = $query->link_image;
                $file = $this->request->getFile('file');
                if ($file && $file->isValid() && !$file->hasMoved()) {
                    $newName = $file->getRandomName();
                    $file->move(FCPATH . 'media_library/banners/', $newName);
                    $update = model('App\Models\M_helper')->updateData($id, $this->table, ['link_image' => $newName]);
                    if ($update) {
                        @chmod(FCPATH . 'media_library/banners/' . $newName, 0777);
                        @chmod(FCPATH . 'media_library/banners/' . $file_name, 0777);
                        @unlink(FCPATH . 'media_library/banners/' . $file_name);
                        $this->image_resize(FCPATH . 'media_library/banners', $newName);
                    }
                    $this->vars['status'] = 'success';
                    $this->vars['message'] = 'uploaded';
                } else {
                    $this->vars['status'] = 'error';
                    $this->vars['message'] = $file ? $file->getErrorString() : 'No file uploaded';
                }
            }
            return $this->response->setContentType('application/json')->setJSON($this->vars);
        }
    }

    private function image_resize($path, $file_name)
    {
        $media = session('media');
        $image = \Config\Services::image()
            ->withFile($path . '/' . $file_name)
            ->resize((int)($media['banner_width'] ?? 468), 0, true);
        $image->save($path . '/' . $file_name);
    }
}
