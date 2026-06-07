<?php

namespace App\Controllers\Academic;

use App\Controllers\AdminController;

class Alumni extends AdminController
{
    public function __construct()
    {
        $this->pk = 'id';
        $this->table = 'users';
    }

    public function index()
    {
        $data['title'] = 'Alumni';
        $data['academic'] = $data['academic_references'] = $data['alumni'] = true;
        $data['content'] = 'academic/alumni';
        return view('backend/index', $data);
    }

    public function profile()
    {
        $id = _toInteger(service('uri')->getSegment(4));
        if (_isNaturalNumber($id)) {
            $m_students = model('App\Models\M_students');
            $data['student'] = $m_students->profile($id);
            $data['scholarships'] = model('App\Models\M_scholarships')->get_by_student_id($id);
            $data['achievements'] = model('App\Models\M_achievements')->get_by_student_id($id);
            $data['title'] = 'Profil Alumni';
            $data['photo'] = base_url('media_library/images/no-image.png');
            $photo_name = $data['student']->photo;
            $photo = 'media_library/users/photo/' . $photo_name;
            if ($photo_name && file_exists($_SERVER['DOCUMENT_ROOT'] . '/' . $photo)) {
                $data['photo'] = base_url($photo);
            }
            $data['academic'] = $data['academic_references'] = $data['alumni'] = true;
            $data['content'] = 'academic/student_profile';
            return view('backend/index', $data);
        }
        throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
    }

    public function pagination()
    {
        if ($this->request->isAJAX()) {
            $keyword = trim($this->request->getPost('keyword'));
            $page_number = _toInteger($this->request->getPost('page_number'));
            $limit = _toInteger($this->request->getPost('per_page'));
            $offset = ($page_number * $limit);
            $m_alumni = model('App\Models\M_alumni');
            $query = $m_alumni->get_where($keyword, 'rows', $limit, $offset);
            $total_rows = $m_alumni->get_where($keyword);
            $total_page = $limit > 0 ? ceil(_toInteger($total_rows) / _toInteger($limit)) : 1;
            $this->vars['total_page'] = _toInteger($total_page);
            $this->vars['total_rows'] = _toInteger($total_rows);
            $this->vars['rows'] = $query->getResult();
            return $this->response->setContentType('application/json')->setJSON($this->vars);
        }
    }

    public function save()
    {
        if ($this->request->isAJAX()) {
            $id = _toInteger($this->request->getPost('id'));
            if ($this->validation($id)) {
                $dataset = $this->dataset();
                if (_isNaturalNumber($id)) {
                    if ($dataset['is_alumni'] === 'false') {
                        $student_status_id = get_option_id('student_status', 'aktif');
                        if (_isNaturalNumber($student_status_id)) {
                            $dataset['student_status_id'] = $student_status_id;
                        }
                    } else if ($dataset['is_alumni'] == 'unverified') {
                        $dataset['is_student'] = 'false';
                        $dataset['is_prospective_student'] = 'false';
                    }
                    $query = model('App\Models\M_helper')->updateData($id, $this->table, $dataset);
                    if ($query) model('App\Models\M_users')->activate_account($dataset['email']);
                    $this->vars['status'] = $query ? 'success' : 'error';
                    $this->vars['message'] = $query ? 'updated' : 'not_updated';
                } else {
                    $this->vars['status'] = 'error';
                    $this->vars['message'] = 'not_updated';
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
            'is_alumni' => $this->request->getPost('is_alumni'),
            'nis' => strip_tags($this->request->getPost('nis')),
            'full_name' => $this->request->getPost('full_name'),
            'street_address' => $this->request->getPost('street_address'),
            'rt' => $this->request->getPost('rt'),
            'rw' => $this->request->getPost('rw'),
            'sub_village' => $this->request->getPost('sub_village'),
            'village' => $this->request->getPost('village'),
            'sub_district' => $this->request->getPost('sub_district'),
            'district' => $this->request->getPost('district'),
            'postal_code' => $this->request->getPost('postal_code'),
            'phone' => $this->request->getPost('phone'),
            'mobile_phone' => $this->request->getPost('mobile_phone'),
            'email' => $this->request->getPost('email') ?? null,
            'start_date' => $this->request->getPost('start_date'),
            'end_date' => $this->request->getPost('end_date'),
            'reason' => $this->request->getPost('reason'),
            'birth_place' => $this->request->getPost('birth_place'),
            'birth_date' => $this->request->getPost('birth_date'),
        ];
    }

    private function validation($id = 0)
    {
        $this->validation->setRules([
            'full_name' => ['label' => 'Nama Lengkap', 'rules' => 'trim|required'],
            'nis' => ['label' => 'NIS', 'rules' => 'trim|alpha_numeric_space'],
            'email' => ['label' => 'Email', 'rules' => 'trim|required|valid_email'],
        ]);
        return $this->validation->withRequest($this->request)->run();
    }

    public function alumni_reports()
    {
        if ($this->request->isAJAX()) {
            $query = model('App\Models\M_alumni')->alumni_reports();
            return $this->response->setContentType('application/json')->setJSON($query->getResult());
        }
    }
}
