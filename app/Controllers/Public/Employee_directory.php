<?php

namespace App\Controllers\Public;

use App\Controllers\PublicController;

class Employee_directory extends PublicController
{
    public static $per_page = 20;

    public function index()
    {
        $m_employees = model('App\Models\M_employees');
        $total_rows = $m_employees->getEmployees()->getNumRows();

        $data['page_title'] = 'Direktori Guru dan Tenaga Kependidikan';
        $data['total_page'] = ceil($total_rows / self::$per_page);
        $data['query'] = $m_employees->getEmployees(self::$per_page);
        $data['content'] = 'themes/' . theme_folder() . '/loop-employees';
        return view('themes/' . theme_folder() . '/index', $data);
    }

    public function get_employees()
    {
        if (!$this->request->isAJAX()) {
            return $this->response->setStatusCode(404);
        }

        $page_number = _toInteger($this->request->getPost('page_number'));
        $offset = ($page_number - 1) * self::$per_page;

        $m_employees = model('App\Models\M_employees');
        $query = $m_employees->getEmployees(self::$per_page, $offset);

        $rows = [];
        foreach ($query->getResult() as $row) {
            $photo = 'no-image.png';
            if ($row->photo && file_exists(FCPATH . 'media_library/users/photo/' . $row->photo)) {
                $photo = $row->photo;
            }
            $rows[] = [
                'nik' => $row->nik,
                'full_name' => $row->full_name,
                'gender' => $row->gender,
                'birth_place' => $row->birth_place,
                'birth_date' => indo_date($row->birth_date),
                'photo' => base_url('media_library/users/photo/' . $photo),
                'employment_type' => $row->employment_type,
            ];
        }

        return $this->response->setContentType('application/json')->setJSON([
            'rows' => $rows
        ]);
    }
}
