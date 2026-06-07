<?php

namespace App\Controllers\Academic;

use App\Controllers\AdminController;

class Class_group_students extends AdminController
{
    public function __construct()
    {
        $this->pk = 'id';
        $this->table = 'class_group_students';
    }

    public function create()
    {
        $data['title'] = 'Pengaturan Rombongan Belajar';
        $data['academic'] = $data['academic_settings'] = $data['class_group_students'] = true;
        $data['academic_year_dropdown'] = model('App\Models\M_academic_years')->dropdown();
        $data['class_group_dropdown'] = model('App\Models\M_class_groups')->dropdown();
        $data['content'] = 'academic/set_class_group_students';
        return view('backend/index', $data);
    }

    public function get_students()
    {
        if ($this->request->isAJAX()) {
            $academic_year_id = _toInteger($this->request->getPost('academic_year_id'));
            $class_group_id = $this->request->getPost('class_group_id');
            $this->vars['rows'] = model('App\Models\M_class_group_students')->getStudents($academic_year_id, $class_group_id);
            return $this->response->setContentType('application/json')->setJSON($this->vars);
        }
    }

    public function set_class_group_students()
    {
        if ($this->request->isAJAX()) {
            $student_ids = $this->request->getPost('student_ids');
            $ids = [];
            foreach (explode(',', $student_ids) as $student_id) {
                array_push($ids, trim($student_id));
            }
            $academic_year_id = _toInteger($this->request->getPost('academic_year_id'));
            $class_group_id = _toInteger($this->request->getPost('class_group_id'));
            $query = model('App\Models\M_class_group_students')->set_class_group_students($ids, $academic_year_id, $class_group_id);
            $this->vars['status'] = $query ? 'success' : 'error';
            $this->vars['message'] = $query ? 'Data sudah disipman' : 'Data tidak tersimpan. Kemungkinan terjadi duplikasi data, pengaturan wali kelas belum diatur, atau server bermasalah, silahkan periksa kembali data Anda.';
            return $this->response->setContentType('application/json')->setJSON($this->vars);
        }
    }

    public function delete_permanently()
    {
        if ($this->request->isAJAX()) {
            $student_ids = $this->request->getPost('student_ids');
            $ids = [];
            foreach (explode(',', $student_ids) as $student_id) {
                array_push($ids, trim($student_id));
            }
            $academic_year_id = _toInteger($this->request->getPost('academic_year_id'));
            $class_group_id = _toInteger($this->request->getPost('class_group_id'));
            $query = model('App\Models\M_class_group_students')->delete_permanently($ids, $academic_year_id, $class_group_id);
            $this->vars['status'] = $query ? 'success' : 'error';
            $this->vars['message'] = $query ? 'Data sudah terhapus' : 'Data tidak terhapus';
            return $this->response->setContentType('application/json')->setJSON($this->vars);
        }
    }

    public function set_as_alumni()
    {
        if ($this->request->isAJAX()) {
            $student_ids = $this->request->getPost('student_ids');
            $ids = [];
            foreach (explode(',', $student_ids) as $student_id) {
                array_push($ids, trim($student_id));
            }
            $end_date = (int)$this->request->getPost('end_date');
            $query = model('App\Models\M_students')->set_as_alumni($ids, $end_date);
            $this->vars['status'] = $query ? 'success' : 'error';
            $this->vars['message'] = $query ? 'Data sudah tersimpan' : 'Data tidak tersimpan';
            return $this->response->setContentType('application/json')->setJSON($this->vars);
        }
    }
}
