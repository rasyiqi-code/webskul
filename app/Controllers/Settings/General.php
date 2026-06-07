<?php

namespace App\Controllers\Settings;

class General extends \App\Controllers\AdminController
{
    protected $pk = 'id';
    protected $table = 'settings';

    public function __construct()
    {
        $this->m_settings = model('App\Models\M_settings');
    }

    public function index()
    {
        $this->vars['title'] = 'Pengaturan Umum';
        $this->vars['settings'] = $this->vars['general_settings'] = true;
        $this->vars['timezone_list'] = json_encode(timezone_list());
        $this->vars['content'] = 'settings/general';
        return view('backend/index', $this->vars);
    }

    public function pagination()
    {
        if ($this->request->isAJAX()) {
            $keyword = trim($this->request->getPost('keyword'));
            $page_number = _toInteger($this->request->getPost('page_number'));
            $limit = _toInteger($this->request->getPost('per_page'));
            $offset = ($page_number * $limit);
            $query = $this->m_settings->get_where($keyword, 'general', 'rows', $limit, $offset);
            $total_rows = $this->m_settings->get_where($keyword, 'general');
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
                        $this->image_resize(FCPATH . 'media_library/images', $newName, $query->setting_variable);
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

    private function image_resize($path, $file_name, $setting_variable = 'favicon')
    {
        $settings = [
            'favicon_width' => 32,
            'favicon_height' => 32,
            'header_width' => session('header_width'),
            'header_height' => session('header_height')
        ];
        $image = \Config\Services::image()
            ->withFile($path . '/' . $file_name)
            ->resize((int)$settings[$setting_variable . '_width'], 0, true);
        $image->save($path . '/' . $file_name);
    }
}
