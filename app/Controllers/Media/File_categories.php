<?php

namespace App\Controllers\Media;

class File_categories extends \App\Controllers\AdminController
{
    protected $pk = 'id';
    protected $table = 'categories';

    public function __construct()
    {
        $this->m_file_categories = model('App\Models\M_file_categories');
    }

    public function index()
    {
        $this->vars['title'] = 'Kategori File';
        $this->vars['media'] = $this->vars['file_categories'] = true;
        $this->vars['content'] = 'media/file_categories';
        return view('backend/index', $this->vars);
    }

    public function pagination()
    {
        if ($this->request->isAJAX()) {
            $keyword = trim($this->request->getPost('keyword'));
            $page_number = _toInteger($this->request->getPost('page_number'));
            $limit = _toInteger($this->request->getPost('per_page'));
            $offset = ($page_number * $limit);
            $query = $this->m_file_categories->get_where($keyword, 'rows', $limit, $offset);
            $total_rows = $this->m_file_categories->get_where($keyword);
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

    public function delete()
    {
        if ($this->request->isAJAX()) {
            $id = _toInteger($this->request->getPost('id'));
            $query = model('App\Models\M_helper')->deleteData($id, $this->table);
            $this->vars['status'] = $query ? 'success' : 'error';
            $this->vars['message'] = $query ? 'Data berhasil dihapus.' : 'Terjadi kesalahan dalam menghapus data';
            return $this->response->setContentType('application/json')->setJSON($this->vars);
        }
    }

    private function dataset()
    {
        return [
            'category_name' => $this->request->getPost('category_name'),
            'category_description' => $this->request->getPost('category_description'),
            'category_slug' => url_title($this->request->getPost('category_name'), '-', true),
            'category_type' => 'file',
        ];
    }

    private function validation()
    {
        $this->validation->setRules([
            'category_name' => ['label' => 'Nama Kategori', 'rules' => 'trim|required'],
        ]);
        return $this->validation->withRequest($this->request)->run();
    }
}
