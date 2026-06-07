<?php

namespace App\Controllers\Academic;

use App\Controllers\AdminController;

class By_student_status extends AdminController
{
    public function __construct()
    {
        $this->pk = 'id';
        $this->table = 'students';
    }

    public function index()
    {
        $data['title'] = 'GRAFIK PESERTA DIDIK BERDASARKAN STATUS PESERTA DIDIK';
        $data['academic'] = $data['academic_chart'] = $data['by_student_status'] = true;
        $data['academic_year_dropdown'] = model('App\Models\M_academic_years')->dropdown();
        $data['content'] = 'academic/by_student_status';
        return view('backend/index', $data);
    }

    public function generate_chart()
    {
        if ($this->request->isAJAX()) {
            $academic_year_id = _toInteger($this->request->getPost('academic_year_id'));
            $query = model('App\Models\M_students')->chart_by_student_status($academic_year_id);
            $this->vars['title'] = 'GRAFIK PESERTA DIDIK BERDASARKAN STATUS PESERTA DIDIK TAHUN PELAJARAN';
            $this->vars['labels'] = [];
            $this->vars['data'] = [];
            foreach ($query->getResult() as $row) {
                array_push($this->vars['labels'], $row->labels);
                array_push($this->vars['data'], $row->data);
            }
            return $this->response->setContentType('application/json')->setJSON($this->vars);
        }
    }
}
