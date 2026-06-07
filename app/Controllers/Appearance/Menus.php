<?php

namespace App\Controllers\Appearance;

class Menus extends \App\Controllers\AdminController
{
    protected $pk = 'id';
    protected $table = 'menus';

    public function __construct()
    {
        $this->m_menus = model('App\Models\M_menus');
    }

    public function index()
    {
        $this->vars['title'] = 'Menu';
        $this->vars['appearance'] = $this->vars['menus'] = true;
        $this->vars['content'] = 'appearance/menus';
        return view('backend/index', $this->vars);
    }

    public function get_menus()
    {
        if ($this->request->isAJAX()) {
            $query = $this->m_menus->get_menus();
            $this->vars['rows'] = is_array($query) || $query instanceof \CodeIgniter\Database\ResultInterface ? $query->getResult() : $query;
            return $this->response->setContentType('application/json')->setJSON($this->vars);
        }
    }

    public function get_pages()
    {
        if ($this->request->isAJAX()) {
            $m_pages = model('App\Models\M_pages');
            $query = $m_pages->get_pages();
            $this->vars['rows'] = is_array($query) || $query instanceof \CodeIgniter\Database\ResultInterface ? $query->getResult() : $query;
            return $this->response->setContentType('application/json')->setJSON($this->vars);
        }
    }

    public function get_post_categories()
    {
        if ($this->request->isAJAX()) {
            $m_categories = model('App\Models\M_categories');
            $query = $m_categories->getCategories('post');
            $this->vars['rows'] = is_array($query) || $query instanceof \CodeIgniter\Database\ResultInterface ? $query->getResult() : $query;
            return $this->response->setContentType('application/json')->setJSON($this->vars);
        }
    }

    public function get_file_categories()
    {
        if ($this->request->isAJAX()) {
            $m_categories = model('App\Models\M_categories');
            $query = $m_categories->getCategories('file');
            $this->vars['rows'] = is_array($query) || $query instanceof \CodeIgniter\Database\ResultInterface ? $query->getResult() : $query;
            return $this->response->setContentType('application/json')->setJSON($this->vars);
        }
    }

    public function delete_permanently()
    {
        if ($this->request->isAJAX()) {
            $id = _toInteger($this->request->getPost('id'));
            if (_isNaturalNumber($id)) {
                $m_helper = model('App\Models\M_helper');
                $is_exists = $m_helper->isExists('menu_parent_id', $id, $this->table);
                if (!$is_exists) {
                    $m_helper->deletePermanently($this->pk, $id, $this->table);
                    $this->vars['status'] = $m_helper->deletePermanently($this->pk, $id, $this->table) ? 'success' : 'error';
                    $this->vars['message'] = $this->vars['status'] == 'success' ? 'deleted' : 'not_deleted';
                } else {
                    $this->vars['status'] = 'warning';
                    $this->vars['message'] = '"Parent menu" tidak dapat dihapus. Silahkan hapus terlebih dahulu "child menu"!';
                }
            } else {
                $this->vars['status'] = 'error';
                $this->vars['message'] = 'Not initialize id OR id not a number';
            }
            return $this->response->setContentType('application/json')->setJSON($this->vars);
        }
    }

    public function truncate_table()
    {
        if ($this->request->isAJAX()) {
            $m_helper = model('App\Models\M_helper');
            $this->vars['status'] = $m_helper->truncate($this->table) ? 'success' : 'error';
            $this->vars['message'] = $this->vars['status'] == 'success' ? 'deleted' : 'not_deleted';
            return $this->response->setContentType('application/json')->setJSON($this->vars);
        }
    }

    public function nested_menus()
    {
        if ($this->request->isAJAX()) {
            $query = $this->m_menus->nested_menus();
            return $this->response->setContentType('application/json')->setJSON($query);
        }
    }

    public function save_menu_position()
    {
        if ($this->request->isAJAX()) {
            if (null !== $this->request->getPost('menus')) {
                $menus = json_decode($this->request->getPost('menus'), true);
                $this->m_menus->save_menu_position(0, $menus);
            }
            $this->vars['status'] = 'success';
            $this->vars['message'] = 'Your data have been saved.';
            return $this->response->setContentType('application/json')->setJSON($this->vars);
        }
    }

    public function save_links()
    {
        if ($this->request->isAJAX()) {
            $db = db_connect();
            $db->table($this->table)
                ->set('menu_url', ($this->request->getPost('menu_url') && $this->request->getPost('menu_url') != '#') ? prep_url($this->request->getPost('menu_url')) : '#')
                ->set('menu_title', $this->request->getPost('menu_title'))
                ->set('menu_target', $this->request->getPost('menu_target'))
                ->set('menu_type', 'links')
                ->set('created_by', session('user_id'))
                ->insert();
            $this->vars['status'] = 'success';
            $this->vars['message'] = 'created';
            return $this->response->setContentType('application/json')->setJSON($this->vars);
        }
    }

    public function save_pages()
    {
        if ($this->request->isAJAX()) {
            $ids = explode(',', $this->request->getPost('ids'));
            $helper = model('App\Models\M_helper');
            $db = db_connect();
            foreach ($ids as $id) {
                $query = $helper->rowObject('id', $id, 'posts');
                $db->table($this->table)
                    ->set('menu_title', $query->post_title)
                    ->set('menu_url', 'read/' . $id . '/' . $query->post_slug)
                    ->set('menu_type', 'pages')
                    ->set('menu_target', '_self')
                    ->set('created_by', session('user_id'))
                    ->insert();
            }
            $this->vars['status'] = 'success';
            $this->vars['message'] = 'created';
            return $this->response->setContentType('application/json')->setJSON($this->vars);
        }
    }

    public function save_post_categories()
    {
        if ($this->request->isAJAX()) {
            $ids = explode(',', $this->request->getPost('ids'));
            $helper = model('App\Models\M_helper');
            $db = db_connect();
            foreach ($ids as $id) {
                $query = $helper->rowObject('id', $id, 'categories');
                $db->table($this->table)
                    ->set('menu_title', $query->category_name)
                    ->set('menu_url', 'kategori/' . $query->category_slug)
                    ->set('menu_type', 'post_categories')
                    ->set('menu_target', '_self')
                    ->set('created_by', session('user_id'))
                    ->insert();
            }
            $this->vars['status'] = 'success';
            $this->vars['message'] = 'created';
            return $this->response->setContentType('application/json')->setJSON($this->vars);
        }
    }

    public function save_file_categories()
    {
        if ($this->request->isAJAX()) {
            $ids = explode(',', $this->request->getPost('ids'));
            $helper = model('App\Models\M_helper');
            $db = db_connect();
            foreach ($ids as $id) {
                $query = $helper->rowObject('id', $id, 'categories');
                $db->table($this->table)
                    ->set('menu_title', $query->category_name)
                    ->set('menu_url', 'download/' . $query->category_slug)
                    ->set('menu_type', 'file_categories')
                    ->set('menu_target', '_self')
                    ->set('created_by', session('user_id'))
                    ->insert();
            }
            $this->vars['status'] = 'success';
            $this->vars['message'] = 'created';
            return $this->response->setContentType('application/json')->setJSON($this->vars);
        }
    }

    public function save_modules()
    {
        if ($this->request->isAJAX()) {
            $modules = explode(',', $this->request->getPost('modules'));
            $db = db_connect();
            foreach ($modules as $module) {
                $db->table($this->table)
                    ->set('menu_title', modules($module))
                    ->set('menu_url', $module)
                    ->set('menu_type', 'modules')
                    ->set('menu_target', '_self')
                    ->set('created_by', session('user_id'))
                    ->insert();
            }
            $this->vars['status'] = 'success';
            $this->vars['message'] = 'created';
            return $this->response->setContentType('application/json')->setJSON($this->vars);
        }
    }

    public function save()
    {
        if ($this->request->isAJAX()) {
            $id = _toInteger($this->request->getPost('id'));
            if ($this->validation()) {
                $dataset = $this->dataset();
                $this->vars['status'] = model('App\Models\M_helper')->updateData($id, $this->table, $dataset) ? 'success' : 'error';
                $this->vars['message'] = $this->vars['status'] == 'success' ? 'updated' : 'not_updated';
            } else {
                $this->vars['status'] = 'error';
                $this->vars['message'] = $this->validation->getErrors();
            }
            return $this->response->setContentType('application/json')->setJSON($this->vars);
        }
    }

    private function dataset()
    {
        $data = [];
        $data['menu_title'] = $this->request->getPost('menu_title');
        $data['menu_url'] = $this->request->getPost('menu_url');
        $data['menu_target'] = $this->request->getPost('menu_target');
        $is_deleted = $this->request->getPost('is_deleted');
        $data['is_deleted'] = $is_deleted;
        if (filter_var((string)$is_deleted, FILTER_VALIDATE_BOOLEAN)) {
            $data['deleted_by'] = session('user_id');
            $data['deleted_at'] = date('Y-m-d H:i:s');
        } else {
            $data['restored_by'] = session('user_id');
            $data['restored_at'] = date('Y-m-d H:i:s');
        }
        return $data;
    }

    private function validation()
    {
        $this->validation->setRules([
            'menu_title' => ['label' => 'Judul Menu', 'rules' => 'trim|required'],
            'menu_url' => ['label' => 'URL', 'rules' => 'trim|required'],
            'menu_target' => ['label' => 'Target', 'rules' => 'trim|required'],
            'is_deleted' => ['label' => 'Aktif?', 'rules' => 'trim|required|in_list[true,false]'],
        ]);
        return $this->validation->withRequest($this->request)->run();
    }
}
