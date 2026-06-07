<?php

namespace App\Controllers\Media;

class Albums extends \App\Controllers\AdminController
{
    protected $pk    = 'id';
    protected $table = 'albums';

    /** @var \App\Models\M_albums */
    protected $mAlbums;

    public function initController($request, $response, $logger)
    {
        parent::initController($request, $response, $logger);
        $this->mAlbums = model('App\Models\M_albums');
    }

    public function index()
    {
        $this->vars['title'] = 'Album Foto';
        $this->vars['media'] = $this->vars['albums'] = true;
        $this->vars['content'] = 'media/albums';
        return view('backend/index', $this->vars);
    }

    public function pagination()
    {
        if ($this->request->isAJAX()) {
            $keyword = trim($this->request->getPost('keyword'));
            $page_number = _toInteger($this->request->getPost('page_number'));
            $limit = _toInteger($this->request->getPost('per_page'));
            $offset = ($page_number * $limit);
            $query = $this->mAlbums->get_where($keyword, 'rows', $limit, $offset);
            $total_rows = $this->mAlbums->get_where($keyword);
            $total_page = $limit > 0 ? ceil(_toInteger($total_rows) / _toInteger($limit)) : 1;
            $this->vars['total_page'] = _toInteger($total_page);
            $this->vars['total_rows'] = _toInteger($total_rows);
            $this->vars['rows'] = is_array($query) || $query instanceof \CodeIgniter\Database\ResultInterface ? $query->getResult() : $query;
            return $this->response->setContentType('application/json')->setJSON($this->vars);
        }
    }

    public function form_upload()
    {
        $id = _toInteger(service('uri')->getSegment(4));
        if (_isNaturalNumber($id)) {
            $query = model('App\Models\M_helper')->rowObject($this->pk, $id, $this->table);
            $this->vars['title'] = $query->album_title;
            $this->vars['action'] = site_url('media/albums/images_upload/') . $id;
            $this->vars['media'] = $this->vars['albums'] = true;
            $this->vars['content'] = 'media/upload_photos';
            return view('backend/index', $this->vars);
        }
        throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
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
            'album_title' => $this->request->getPost('album_title'),
            'album_description' => $this->request->getPost('album_description'),
            'album_slug' => slugify($this->request->getPost('album_title'))
        ];
    }

    private function validation()
    {
        $this->validation->setRules([
            'album_title' => ['label' => 'Judul Album', 'rules' => 'trim|required'],
            'album_description' => ['label' => 'Keterangan', 'rules' => 'trim'],
        ]);
        return $this->validation->withRequest($this->request)->run();
    }

    public function list_images()
    {
        if ($this->request->isAJAX()) {
            $id = _toInteger($this->request->getPost('id'));
            if (_isNaturalNumber($id)) {
                $m_photos = model('App\Models\M_photos');
                $query = $m_photos->get_photos($id);
                $items = [];
                foreach ($query as $row) {
                    $items[] = [
                        'src' => base_url('media_library/albums/large/' . $row->photo_name)
                    ];
                }
                $this->vars = [
                    'count' => count($items),
                    'items' => $items
                ];
            }
            return $this->response->setContentType('application/json')->setJSON($this->vars);
        }
    }

    public function cover_upload()
    {
        if ($this->request->isAJAX()) {
            $id = _toInteger($this->request->getPost('id'));
            if (_isNaturalNumber($id)) {
                $query = model('App\Models\M_helper')->rowObject($this->pk, $id, $this->table);
                $file_name = $query->album_cover;
                $file = $this->request->getFile('file');
                if ($file && $file->isValid() && !$file->hasMoved()) {
                    $newName = $file->getRandomName();
                    $file->move(FCPATH . 'media_library/albums/', $newName);
                    $update = model('App\Models\M_helper')->updateData($id, $this->table, ['album_cover' => $newName]);
                    if ($update) {
                        $this->album_cover_resize(FCPATH . 'media_library/albums/', $newName);
                        @chmod(FCPATH . 'media_library/albums/' . $newName, 0777);
                        @chmod(FCPATH . 'media_library/albums/' . $file_name, 0777);
                        @unlink(FCPATH . 'media_library/albums/' . $file_name);
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

    private function album_cover_resize($path, $file_name)
    {
        $media = session('media');
        $image = \Config\Services::image()
            ->withFile($path . '/' . $file_name)
            ->resize((int)($media['album_cover_width'] ?? 300), (int)($media['album_cover_height'] ?? 300), false);
        $image->save($path . '/' . $file_name);
    }

    public function images_upload()
    {
        $id = _toInteger(service('uri')->getSegment(4));
        if (_isNaturalNumber($id)) {
            $file = $this->request->getFile('file');
            if ($file && $file->isValid() && !$file->hasMoved()) {
                $newName = $file->getRandomName();
                $file->move(FCPATH . 'media_library/albums/', $newName);
                $dataset = [
                    'photo_album_id' => $id,
                    'photo_name' => $newName,
                    'created_by' => session('user_id')
                ];
                model('App\Models\M_helper')->insertData('photos', $dataset);
                @chmod(FCPATH . 'media_library/albums/' . $newName, 0777);
                $this->image_resize(FCPATH . 'media_library/albums/', $newName, 'large');
                $this->image_resize(FCPATH . 'media_library/albums/', $newName, 'medium');
                $this->image_resize(FCPATH . 'media_library/albums/', $newName, 'thumbnail');
                @unlink(FCPATH . 'media_library/albums/' . $newName);
                $this->vars['status'] = 'success';
                $this->vars['message'] = 'uploaded';
            } else {
                $this->vars['status'] = 'error';
                $this->vars['message'] = $file ? $file->getErrorString() : 'No file uploaded';
            }
        }
        return $this->response->setContentType('application/json')->setJSON($this->vars);
    }

    private function image_resize($path, $file_name, $size = 'large')
    {
        $media = session('media');
        $settings = [
            'thumbnail_size_height' => $media['thumbnail_size_height'] ?? 150,
            'thumbnail_size_width' => $media['thumbnail_size_width'] ?? 150,
            'medium_size_height' => $media['medium_size_height'] ?? 300,
            'medium_size_width' => $media['medium_size_width'] ?? 300,
            'large_size_height' => $media['large_size_height'] ?? 600,
            'large_size_width' => $media['large_size_width'] ?? 600
        ];
        $image = \Config\Services::image()
            ->withFile($path . '/' . $file_name)
            ->resize((int)$settings[$size . '_size_width'], (int)$settings[$size . '_size_height'], false);
        if (!is_dir($path . '/' . $size)) {
            @mkdir($path . '/' . $size, 0777, true);
        }
        $image->save($path . '/' . $size . '/' . $file_name);
        @chmod($path . '/' . $size . '/' . $file_name, 0777);
    }
}
