<?php

namespace App\Controllers;

class Achievements extends AdminController
{
    protected $pk    = 'id';
    protected $table = 'achievements';

    /** @var \App\Models\M_achievements */
    protected $mAchievements;

    public function initController($request, $response, $logger)
    {
        parent::initController($request, $response, $logger);
        $this->mAchievements = model('App\Models\M_achievements');
    }

    public function index()
    {
        $this->vars['title'] = 'Prestasi';
        $this->vars['achievements'] = true;
        $this->vars['content'] = 'achievements';
        return view('backend/index', $this->vars);
    }

    public function pagination()
    {
        if ($this->request->isAJAX()) {
            $keyword = trim($this->request->getPost('keyword'));
            $page_number = _toInteger($this->request->getPost('page_number'));
            $limit = _toInteger($this->request->getPost('per_page'));
            $offset = ($page_number * $limit);
            $query = $this->mAchievements->get_where($keyword, 'rows', $limit, $offset);
            $total_rows = $this->mAchievements->get_where($keyword);
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
                if (_isNaturalNumber($id)) {
                    $query = model('App\Models\M_helper')->rowObject($this->pk, $id, $this->table);
                    if ($query->student_id == session('user_id')) {
                        $this->vars['status'] = model('App\Models\M_helper')->updateData($id, $this->table, $dataset) ? 'success' : 'error';
                        $this->vars['message'] = $this->vars['status'] == 'success' ? 'updated' : 'not_updated';
                    } else {
                        $this->vars['status'] = 'error';
                        $this->vars['message'] = 'forbidden';
                    }
                } else {
                    $dataset['created_by'] = session('user_id');
                    $this->vars['status'] = model('App\Models\M_helper')->insertData($this->table, $dataset) ? 'success' : 'error';
                    $this->vars['message'] = $this->vars['status'] == 'success' ? 'created' : 'not_created';
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
            'student_id' => (int)session('user_id'),
            'achievement_description' => $this->request->getPost('achievement_description'),
            'achievement_type' => $this->request->getPost('achievement_type'),
            'achievement_level' => $this->request->getPost('achievement_level'),
            'achievement_year' => $this->request->getPost('achievement_year'),
            'achievement_organizer' => $this->request->getPost('achievement_organizer')
        ];
    }

    private function validation()
    {
        $this->validation->setRules([
            'achievement_description' => ['label' => 'Nama Prestasi', 'rules' => 'trim|required'],
            'achievement_type' => ['label' => 'Jenis Prestasi', 'rules' => 'trim|required|is_natural_no_zero'],
            'achievement_level' => ['label' => 'Tingkat Prestasi', 'rules' => 'trim|required|is_natural_no_zero'],
            'achievement_year' => ['label' => 'Tahun', 'rules' => 'trim|is_natural_no_zero|required|min_length[4]|max_length[4]'],
            'achievement_organizer' => ['label' => 'Penyelenggara', 'rules' => 'trim'],
        ]);
        return $this->validation->withRequest($this->request)->run();
    }
}
