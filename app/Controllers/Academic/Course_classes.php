<?php

namespace App\Controllers\Academic;

use App\Controllers\AdminController;

class Course_classes extends AdminController
{
    public function __construct()
    {
        $this->pk = 'id';
        $this->table = 'course_classes';
    }

    public function index()
    {
        $data['title'] = 'Pengaturan Mata Pelajaran';
        $data['academic'] = $data['academic_settings'] = $data['course_classes'] = true;
        $data['academic_year_dropdown'] = model('App\Models\M_academic_years')->dropdown();
        $data['class_group_dropdown'] = model('App\Models\M_class_groups')->dropdown();
        $data['content'] = 'academic/set_course_classes';
        return view('backend/index', $data);
    }

    public function get_subjects()
    {
        if ($this->request->isAJAX()) {
            $copy_data = $this->request->getPost('copy_data');
            $academic_year_id = _toInteger($this->request->getPost('academic_year_id'));
            $semester = $this->request->getPost('semester');
            $class_group_id = _toInteger($this->request->getPost('class_group_id'));
            $this->vars['subjects'] = model('App\Models\M_course_classes')->getSubjects($copy_data, $academic_year_id, $semester, $class_group_id);
            return $this->response->setContentType('application/json')->setJSON($this->vars);
        }
    }

    public function get_course_classes()
    {
        if ($this->request->isAJAX()) {
            $academic_year_id = _toInteger($this->request->getPost('academic_year_id'));
            $semester = $this->request->getPost('semester');
            $class_group_id = _toInteger($this->request->getPost('class_group_id'));
            $this->vars['course_classes'] = model('App\Models\M_course_classes')->get_course_classes($academic_year_id, $semester, $class_group_id);
            return $this->response->setContentType('application/json')->setJSON($this->vars);
        }
    }

    public function save()
    {
        if ($this->request->isAJAX()) {
            $subject_ids = $this->request->getPost('subject_ids');
            $ids = [];
            foreach (explode(',', $subject_ids) as $subject_id) {
                array_push($ids, trim($subject_id));
            }
            $academic_year_id = _toInteger($this->request->getPost('academic_year_id'));
            $semester = $this->request->getPost('semester');
            $class_group_id = _toInteger($this->request->getPost('class_group_id'));
            $query = model('App\Models\M_course_classes')->save($ids, $academic_year_id, $semester, $class_group_id);
            $this->vars['status'] = $query ? 'success' : 'error';
            $this->vars['message'] = $query ? 'Data sudah disipman' : 'Data tidak tersimpan. Kemungkinan terjadi duplikasi data atau server bermasalah, silahkan periksa kembali data Anda.';
            return $this->response->setContentType('application/json')->setJSON($this->vars);
        }
    }

    public function change_deleted_status()
    {
        if ($this->request->isAJAX()) {
            $ids = $this->request->getPost('ids');
            $course_class_ids = [];
            foreach (explode(',', $ids) as $id) {
                array_push($course_class_ids, _toInteger($id));
            }
            $is_deleted = $this->request->getPost('is_deleted');
            $academic_year_id = _toInteger($this->request->getPost('academic_year_id'));
            $semester = $this->request->getPost('semester');
            $class_group_id = _toInteger($this->request->getPost('class_group_id'));
            $query = model('App\Models\M_course_classes')->change_deleted_status($course_class_ids, $academic_year_id, $semester, $class_group_id, $is_deleted);
            $this->vars['status'] = $query ? 'success' : 'error';
            $this->vars['message'] = $query ? 'Data sudah dikembalikan' : 'Data gagal dikembalikan';
            if (filter_var($is_deleted, FILTER_VALIDATE_BOOLEAN)) {
                $this->vars['message'] = $query ? 'Data sudah terhapus' : 'Data tidak terhapus';
            }
            return $this->response->setContentType('application/json')->setJSON($this->vars);
        }
    }
}
