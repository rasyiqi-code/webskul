<?php

namespace App\Controllers\Public;

use App\Controllers\PublicController;

class Alumni_directory extends PublicController
{
    public static $per_page = 10;

    public function index()
    {
        $m_alumni = model('App\Models\M_alumni');
        $total_rows = $m_alumni->getAlumni()->getNumRows();

        $data['page_title'] = 'Direktori Alumni';
        $data['total_page'] = ceil($total_rows / self::$per_page);
        $data['query'] = $m_alumni->getAlumni(self::$per_page);
        $data['content'] = 'themes/' . theme_folder() . '/loop-alumni';
        return view('themes/' . theme_folder() . '/index', $data);
    }

    public function get_alumni()
    {
        if (!$this->request->isAJAX()) {
            return $this->response->setStatusCode(404);
        }

        $page_number = _toInteger($this->request->getPost('page_number'));
        $offset = ($page_number - 1) * self::$per_page;

        $m_alumni = model('App\Models\M_alumni');
        $query = $m_alumni->getAlumni(self::$per_page, $offset);

        $rows = [];
        foreach ($query->getResult() as $row) {
            $photo = 'no-image.png';
            if ($row->photo && file_exists(FCPATH . 'media_library/users/photo/' . $row->photo)) {
                $photo = $row->photo;
            }
            $rows[] = [
                'nis' => $row->nis,
                'full_name' => $row->full_name,
                'gender' => $row->gender,
                'birth_place' => $row->birth_place,
                'birth_date' => indo_date($row->birth_date),
                'year_in' => $row->year_in,
                'year_out' => $row->year_out,
                'photo' => base_url('media_library/users/photo/' . $photo),
            ];
        }

        return $this->response->setContentType('application/json')->setJSON([
            'rows' => $rows
        ]);
    }
}
