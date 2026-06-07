<?php

namespace App\Controllers\Academic;

use App\Controllers\AdminController;

class Subject_teachers extends AdminController
{
    public function __construct()
    {
        $this->pk = 'id';
        $this->table = 'subject_teachers';
    }

    public function index()
    {
        $data['title'] = 'Pengaturan Guru Mata Pelajaran';
        $data['academic'] = $data['academic_settings'] = $data['subject_teachers'] = true;
        $data['academic_year_dropdown'] = model('App\Models\M_academic_years')->dropdown();
        $data['class_group_dropdown'] = model('App\Models\M_class_groups')->dropdown();
        $data['teachers'] = json_encode([0 => 'Unset'] + model('App\Models\M_users')->dropdown('teacher'));
        $data['content'] = 'academic/set_subject_teachers';
        return view('backend/index', $data);
    }

    public function get_subjects()
    {
        if ($this->request->isAJAX()) {
            $academic_year_id = _toInteger($this->request->getPost('academic_year_id'));
            $semester = $this->request->getPost('semester');
            $class_group_id = _toInteger($this->request->getPost('class_group_id'));
            $this->vars['subjects'] = model('App\Models\M_subject_teachers')->getSubjects($academic_year_id, $semester, $class_group_id);
            return $this->response->setContentType('application/json')->setJSON($this->vars);
        }
    }

    public function save()
    {
        if ($this->request->isAJAX()) {
            $course_classes = json_decode($this->request->getPost('course_classes'), true);
            $query = model('App\Models\M_subject_teachers')->save($course_classes);
            $this->vars['status'] = $query ? 'success' : 'error';
            $this->vars['message'] = $query ? 'Data Anda berhasil disimpan.' : 'Terjadi kesalahan dalam menyimpan data';
            return $this->response->setContentType('application/json')->setJSON($this->vars);
        }
    }
}
