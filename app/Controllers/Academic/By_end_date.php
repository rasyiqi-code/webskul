<?php

namespace App\Controllers\Academic;

use App\Controllers\AdminController;

class By_end_date extends AdminController
{
    public function __construct()
    {
        $this->pk = 'id';
        $this->table = 'students';
    }

    public function index()
    {
        $data['title'] = 'GRAFIK PESERTA DIDIK BERDASARKAN TAHUN LULUS';
        $data['academic'] = $data['academic_chart'] = $data['by_end_date'] = true;
        $labels = [];
        $data_values = [];
        $query = model('App\Models\M_students')->chart_by_end_date();
        foreach ($query->getResult() as $row) {
            array_push($labels, $row->labels);
            array_push($data_values, $row->data);
        }
        $data['labels'] = json_encode($labels);
        $data['data'] = json_encode($data_values);
        $data['content'] = 'academic/by_end_date';
        return view('backend/index', $data);
    }
}
