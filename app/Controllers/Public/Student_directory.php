<?php

namespace App\Controllers\Public;

use App\Controllers\PublicController;

class Student_directory extends PublicController
{
    public function index()
    {
        $m_academic_years = model('App\Models\M_academic_years');
        $m_class_groups = model('App\Models\M_class_groups');

        $data['page_title'] = 'DIREKTORI PESERTA DIDIK TAHUN PELAJARAN ' . session('current_academic_year');
        $data['academic_year_dropdown'] = $m_academic_years->dropdown();
        $data['class_group_dropdown'] = $m_class_groups->dropdown();
        $data['content'] = 'themes/' . theme_folder() . '/loop-students';
        return view('themes/' . theme_folder() . '/index', $data);
    }

    public function get_students()
    {
        if (!$this->request->isAJAX()) {
            return $this->response->setStatusCode(404);
        }

        if (!$this->validation()) {
            return $this->response->setContentType('application/json')->setJSON([
                'status' => 'error',
                'message' => $this->validation->getErrors()
            ]);
        }

        $academic_year_id = _toInteger($this->request->getPost('academic_year_id'));
        $class_group_id = _toInteger($this->request->getPost('class_group_id'));

        $m_class_group_students = model('App\Models\Public\M_class_group_students');
        $query = $m_class_group_students->getStudents($academic_year_id, $class_group_id);

        $rows = [];
        foreach ($query->getResult() as $row) {
            $photo = 'no-image.png';
            if ($row->photo && file_exists(FCPATH . 'media_library/users/photo/' . $row->photo)) {
                $photo = $row->photo;
            }
            $rows[] = [
                'nis' => $row->nis,
                'full_name' => $row->full_name,
                'gender' => $row->gender == 'L' ? 'Laki-laki' : 'Perempuan',
                'birth_place' => $row->birth_place,
                'birth_date' => indo_date($row->birth_date),
                'photo' => base_url('media_library/users/photo/' . $photo),
            ];
        }

        return $this->response->setContentType('application/json')->setJSON([
            'rows' => $rows
        ]);
    }

    private function validation()
    {
        $this->validation->setRules([
            'academic_year_id' => ['label' => 'Tahun Pelajaran', 'rules' => 'trim|required|numeric'],
            'class_group_id' => ['label' => 'Kelas', 'rules' => 'trim|required|numeric'],
        ]);
        return $this->validation->withRequest($this->request)->run();
    }
}
