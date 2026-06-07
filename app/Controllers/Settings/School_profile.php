<?php

namespace App\Controllers\Settings;

class School_profile extends \App\Controllers\AdminController
{
    protected $pk = 'id';
    protected $table = 'settings';

    public function __construct()
    {
        $this->m_settings = model('App\Models\M_settings');
    }

    public function index()
    {
        $this->vars['title'] = 'Pengaturan Profil Sekolah';
        $this->vars['settings'] = $this->vars['school_profile_settings'] = true;
        $this->vars['content'] = 'settings/school_profile';
        return view('backend/index', $this->vars);
    }

    public function pagination()
    {
        if ($this->request->isAJAX()) {
            $keyword = trim($this->request->getPost('keyword'));
            $page_number = _toInteger($this->request->getPost('page_number'));
            $limit = _toInteger($this->request->getPost('per_page'));
            $offset = ($page_number * $limit);
            $query = $this->m_settings->get_where($keyword, 'school_profile', 'rows', $limit, $offset);
            $total_rows = $this->m_settings->get_where($keyword, 'school_profile');
            $total_page = $limit > 0 ? ceil(_toInteger($total_rows) / _toInteger($limit)) : 1;
            $this->vars['total_page'] = _toInteger($total_page);
            $this->vars['total_rows'] = _toInteger($total_rows);
            $this->vars['rows'] = $query;
            return $this->response->setContentType('application/json')->setJSON($this->vars);
        }
    }

    public function save()
    {
        if ($this->request->isAJAX()) {
            $id = _toInteger($this->request->getPost('id'));
            if ($this->validation()) {
                $dataset = $this->dataset();
                $query = model('App\Models\M_helper')->updateData($id, $this->table, $dataset);
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
            'setting_value' => $this->request->getPost('setting_value')
        ];
    }

    private function validation()
    {
        $this->validation->setRules([
            'setting_value' => ['label' => 'Value', 'rules' => 'trim|required'],
        ]);
        return $this->validation->withRequest($this->request)->run();
    }

    public function upload()
    {
        if ($this->request->isAJAX()) {
            $id = _toInteger($this->request->getPost('id'));
            if (_isNaturalNumber($id)) {
                $query = model('App\Models\M_helper')->rowObject($this->pk, $id, $this->table);
                $file_name = $query->setting_value;
                $file = $this->request->getFile('file');
                if ($file && $file->isValid() && !$file->hasMoved()) {
                    $newName = $file->getRandomName();
                    $file->move(FCPATH . 'media_library/images/', $newName);
                    $update = model('App\Models\M_helper')->updateData($id, $this->table, ['setting_value' => $newName]);
                    if ($update) {
                        @chmod(FCPATH . 'media_library/images/' . $newName, 0777);
                        @chmod(FCPATH . 'media_library/images/' . $file_name, 0777);
                        @unlink(FCPATH . 'media_library/images/' . $file_name);
                        if ($query->setting_variable != 'headmaster_photo') {
                            $this->image_resize(FCPATH . 'media_library/images', $newName, $query->setting_variable);
                        }
                    }
                    $this->vars['status'] = 'success';
                    $this->vars['message'] = 'uploaded';
                } else {
                    $this->vars['status'] = 'error';
                    $this->vars['message'] = $file ? $file->getErrorString() : 'No file uploaded';
                }
            } else {
                $this->vars['status'] = 'error';
                $this->vars['message'] = 'ID bukan merupakan tipe angka yang valid !';
            }
            return $this->response->setContentType('application/json')->setJSON($this->vars);
        }
    }

    private function image_resize($path, $file_name, $setting_variable = 'headmaster_photo')
    {
        $media = session('media');
        $settings = [
            'user_photo_height' => $media['user_photo_height'] ?? 344,
            'user_photo_width' => $media['user_photo_width'] ?? 252,
            'logo_height' => $media['logo_height'] ?? 120,
            'logo_width' => $media['logo_width'] ?? 120
        ];
        $image = \Config\Services::image()
            ->withFile($path . '/' . $file_name)
            ->resize((int)$settings[$setting_variable . '_width'], 0, true);
        if ($setting_variable != 'headmaster_photo') {
            $image->resize((int)$settings[$setting_variable . '_width'], _toInteger($settings[$setting_variable . '_height']), true);
        }
        $image->save($path . '/' . $file_name);
    }
}
