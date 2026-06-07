<?php

namespace App\Controllers\Media;

class Files extends \App\Controllers\AdminController
{
    protected $pk = 'id';
    protected $table = 'files';

    public function __construct()
    {
        $this->m_files = model('App\Models\M_files');
        $this->m_file_categories = model('App\Models\M_file_categories');
    }

    public function index()
    {
        $this->vars['title'] = 'File';
        $this->vars['media'] = $this->vars['files'] = true;
        $this->vars['file_category_dropdown'] = json_encode($this->m_file_categories->dropdown());
        $this->vars['content'] = 'media/files';
        return view('backend/index', $this->vars);
    }

    public function pagination()
    {
        if ($this->request->isAJAX()) {
            $keyword = trim($this->request->getPost('keyword'));
            $page_number = _toInteger($this->request->getPost('page_number'));
            $limit = _toInteger($this->request->getPost('per_page'));
            $offset = ($page_number * $limit);
            $query = $this->m_files->get_where($keyword, 'rows', $limit, $offset);
            $total_rows = $this->m_files->get_where($keyword);
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
            'file_title' => $this->request->getPost('file_title'),
            'file_category_id' => _toInteger($this->request->getPost('file_category_id')),
            'file_visibility' => $this->request->getPost('file_visibility')
        ];
    }

    private function validation()
    {
        $this->validation->setRules([
            'file_title' => ['label' => 'URL', 'rules' => 'trim|required'],
            'file_category_id' => ['label' => 'Keterangan', 'rules' => 'trim|required|is_natural_no_zero'],
            'file_visibility' => ['label' => 'Target', 'rules' => 'trim|required'],
        ]);
        return $this->validation->withRequest($this->request)->run();
    }

    public function upload()
    {
        if ($this->request->isAJAX()) {
            $id = _toInteger($this->request->getPost('id'));
            if (_isNaturalNumber($id)) {
                $query = model('App\Models\M_helper')->rowObject($this->pk, $id, $this->table);
                $file_name = $query->file_name;
                $file = $this->request->getFile('file');
                if ($file && $file->isValid() && !$file->hasMoved()) {
                    $newName = $file->getRandomName();
                    $file->move(FCPATH . 'media_library/files/', $newName);
                    $dataset = [
                        'file_name' => $newName,
                        'file_type' => $file->getMimeType(),
                        'file_path' => FCPATH . 'media_library/files/',
                        'file_ext' => $file->getExtension(),
                        'file_size' => $file->getSize()
                    ];
                    $query = model('App\Models\M_helper')->updateData($id, $this->table, $dataset);
                    if ($query) {
                        @chmod(FCPATH . 'media_library/files/' . $file_name, 0775);
                        @unlink(FCPATH . 'media_library/files/' . $file_name);
                    }
                    $this->vars['status'] = 'success';
                    $this->vars['message'] = 'uploaded';
                    $this->vars['full_path'] = FCPATH . 'media_library/files/' . $newName;
                } else {
                    $this->vars['status'] = 'error';
                    $this->vars['message'] = $file ? $file->getErrorString() : 'No file uploaded';
                }
            }
            return $this->response->setContentType('application/json')->setJSON($this->vars);
        }
    }
}
