<?php

namespace App\Controllers\Appearance;

class Themes extends \App\Controllers\AdminController
{
    protected $pk = 'id';
    protected $table = 'themes';

    public function __construct()
    {
        $this->m_themes = model('App\Models\M_themes');
    }

    public function index()
    {
        $this->vars['title'] = 'Tema';
        $this->vars['appearance'] = $this->vars['themes'] = true;
        $this->vars['content'] = 'appearance/themes';
        return view('backend/index', $this->vars);
    }

    public function pagination()
    {
        if ($this->request->isAJAX()) {
            $keyword = trim($this->request->getPost('keyword'));
            $page_number = _toInteger($this->request->getPost('page_number'));
            $limit = _toInteger($this->request->getPost('per_page'));
            $offset = ($page_number * $limit);
            $query = $this->m_themes->get_where($keyword, 'rows', $limit, $offset);
            $total_rows = $this->m_themes->get_where($keyword);
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
                if ($this->vars['status'] == 'success' && $dataset['is_active'] == 'true') {
                    $this->m_themes->deactivate_themes($id);
                }
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
            'theme_name' => $this->request->getPost('theme_name'),
            'theme_folder' => trim(strtolower($this->request->getPost('theme_folder'))),
            'theme_author' => $this->request->getPost('theme_author'),
            'is_active' => $this->request->getPost('is_active')
        ];
    }

    private function validation()
    {
        $this->validation->setRules([
            'theme_name' => ['label' => 'Theme Name', 'rules' => 'trim|required'],
            'theme_folder' => ['label' => 'Theme Folder', 'rules' => 'trim|required'],
            'theme_author' => ['label' => 'Author', 'rules' => 'trim|required'],
        ]);
        return $this->validation->withRequest($this->request)->run();
    }

    public function upload()
    {
        if ($this->request->isAJAX()) {
            $id = _toInteger($this->request->getPost('id'));
            if (_isNaturalNumber($id)) {
                $query = model('App\Models\M_helper')->rowObject($this->pk, $id, $this->table);
                $file = $this->request->getFile('file');
                if ($file && $file->isValid() && !$file->hasMoved()) {
                    $newName = $file->getRandomName();
                    $file->move(ROOTPATH . 'views/themes/', $newName);
                    if ($query->theme_folder == pathinfo($newName, PATHINFO_FILENAME)) {
                        $zip = new \ZipArchive();
                        if ($zip->open(ROOTPATH . 'views/themes/' . $newName)) {
                            $zip->extractTo(ROOTPATH . 'views/themes/');
                            $zip->close();
                            $this->chmod_themes(ROOTPATH . 'views/themes/' . $query->theme_folder);
                            $this->vars['status'] = 'success';
                            $this->vars['message'] = 'extracted';
                        } else {
                            $this->vars['status'] = 'error';
                            $this->vars['message'] = 'not_extracted';
                        }
                    } else {
                        $this->vars['status'] = 'warning';
                        $this->vars['message'] = 'Nama file yang diupload tidak sama dengan "' . $query->theme_folder . '"';
                    }
                    @chmod(ROOTPATH . 'views/themes/' . $newName, 0777);
                    @unlink(ROOTPATH . 'views/themes/' . $newName);
                } else {
                    $this->vars['status'] = 'error';
                    $this->vars['message'] = $file ? $file->getErrorString() : 'No file uploaded';
                }
            } else {
                $this->vars['status'] = 'error';
                $this->vars['message'] = 'ID belum didefinisikan dan/atau ID bukan merupakan tipe data yang benar.';
            }
            return $this->response->setContentType('application/json')->setJSON($this->vars);
        }
    }

    private function chmod_themes($path)
    {
        @chmod($path, 0777);
        $dir = new \DirectoryIterator($path);
        foreach ($dir as $item) {
            @chmod($item->getPathname(), 0777);
            if ($item->isDir() && !$item->isDot()) {
                $this->chmod_themes($item->getPathname());
            }
        }
    }
}
