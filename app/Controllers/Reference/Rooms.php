<?php

namespace App\Controllers\Reference;

class Rooms extends \App\Controllers\AdminController
{
    protected $pk = 'id';
    protected $table = 'rooms';

    public function __construct()
    {
        $this->m_rooms = model('App\Models\M_rooms');
    }

    public function index()
    {
        $this->vars['title'] = 'Ruangan';
        $this->vars['reference'] = $this->vars['rooms'] = true;
        $this->vars['content'] = 'reference/rooms';
        return view('backend/index', $this->vars);
    }

    public function pagination()
    {
        if ($this->request->isAJAX()) {
            $keyword = trim($this->request->getPost('keyword'));
            $page_number = _toInteger($this->request->getPost('page_number'));
            $limit = _toInteger($this->request->getPost('per_page'));
            $offset = ($page_number * $limit);
            $query = $this->m_rooms->get_where($keyword, 'rows', $limit, $offset);
            $total_rows = $this->m_rooms->get_where($keyword);
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
            'building_id' => _toInteger($this->request->getPost('building_id')),
            'room_name' => $this->request->getPost('room_name'),
            'room_capacity' => $this->request->getPost('room_capacity')
        ];
    }

    private function validation()
    {
        $this->validation->setRules([
            'building_id' => ['label' => 'Nama Gedung', 'rules' => 'trim|is_natural_no_zero|required'],
            'room_name' => ['label' => 'Nama Ruangan', 'rules' => 'trim|required'],
            'room_capacity' => ['label' => 'Kapasitas Ruangan', 'rules' => 'trim|is_natural_no_zero|required|max_length[3]'],
        ]);
        return $this->validation->withRequest($this->request)->run();
    }
}
