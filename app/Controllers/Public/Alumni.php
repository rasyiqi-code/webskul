<?php

namespace App\Controllers\Public;

use App\Controllers\PublicController;

class Alumni extends PublicController
{
    public function index()
    {
        $data['page_title'] = 'Pendaftaran Alumni';
        $data['content'] = 'themes/' . theme_folder() . '/alumni-form';
        return view('themes/' . theme_folder() . '/index', $data);
    }

    public function save()
    {
        if (!$this->request->isAJAX()) {
            return $this->response->setStatusCode(404);
        }

        if (__captchaActivated()) {
            $score = get_recapture_score($this->request->getPost('g-recaptcha-response'));
            if ($score < 0.9) {
                return $this->response->setContentType('application/json')->setJSON([
                    'status' => 'recaptcha_error',
                    'message' => 'Recaptcha Error!'
                ]);
            }
        }

        if ($this->validation()) {
            $dataset = $this->dataset();
            $has_uploaded = false;

            if (!empty($_FILES['photo']['name'])) {
                $photo = $this->upload_file();
                if ($photo['status'] == 'success') {
                    $has_uploaded = true;
                    $dataset['photo'] = $photo['file_name'];
                } else {
                    return $this->response->setContentType('application/json')->setJSON([
                        'status' => $photo['status'],
                        'message' => $photo['message']
                    ]);
                }
            }

            $helper = model('App\Models\M_helper');
            $query = $helper->insertData('users', $dataset);
            $status = $query ? 'success' : 'error';
            $message = $query ? 'Data Anda berhasil disimpan.' : 'Terjadi kesalahan dalam menyimpan data';

            if (!$query && $has_uploaded) {
                @unlink(FCPATH . 'media_library/users/photo/' . $photo['file_name']);
            }
        } else {
            $status = 'error';
            $message = $this->validation->getErrors();
        }

        return $this->response->setContentType('application/json')->setJSON([
            'status' => $status,
            'message' => $message
        ]);
    }

    private function dataset()
    {
        return [
            'is_alumni' => 'unverified',
            'nis' => $this->request->getPost('nis'),
            'full_name' => $this->request->getPost('full_name'),
            'gender' => $this->request->getPost('gender'),
            'birth_place' => $this->request->getPost('birth_place'),
            'birth_date' => $this->request->getPost('birth_date'),
            'start_date' => $this->request->getPost('start_date') . '-07-01',
            'end_date' => $this->request->getPost('end_date') . '-06-20',
            'street_address' => $this->request->getPost('street_address'),
            'email' => $this->request->getPost('email') ?: null,
            'phone' => $this->request->getPost('phone'),
            'mobile_phone' => $this->request->getPost('mobile_phone'),
        ];
    }

    private function validation()
    {
        $this->validation->setRules([
            'full_name' => ['label' => 'Nama Lengkap', 'rules' => 'trim|required'],
            'gender' => ['label' => 'Jenis Kelamin', 'rules' => 'trim|required'],
            'birth_place' => ['label' => 'Tempat Lahir', 'rules' => 'trim|required'],
            'birth_date' => ['label' => 'Tanggal Lahir', 'rules' => 'trim|required|valid_date[Y-m-d]'],
            'start_date' => ['label' => 'Tahun Masuk', 'rules' => 'trim|required|min_length[4]|max_length[4]|numeric'],
            'end_date' => ['label' => 'Tahun Lulus', 'rules' => 'trim|required|min_length[4]|max_length[4]|numeric'],
            'nis' => ['label' => 'NIS', 'rules' => 'trim|permit_empty'],
            'street_address' => ['label' => 'Alamat Jalan', 'rules' => 'trim|required'],
            'email' => ['label' => 'Email', 'rules' => 'trim|required|valid_email'],
            'phone' => ['label' => 'Nomor Telepon', 'rules' => 'trim|permit_empty'],
            'mobile_phone' => ['label' => 'Nomor Handphone', 'rules' => 'trim|required'],
        ]);
        return $this->validation->withRequest($this->request)->run();
    }

    public function nis_exists($nis = null, $id = 0)
    {
        $m_users = model('App\Models\M_users');
        if ($m_users->nis_exists($nis, $id)) {
            $this->validation->setRule('nis', 'NIS', 'is_unique[users.nis]');
            return false;
        }
        return true;
    }

    public function email_exists($email = '', $id = 0)
    {
        $m_users = model('App\Models\M_users');
        if ($m_users->email_exists($email, $id)) {
            $this->validation->setRule('email', 'Email', 'is_unique[users.email]');
            return false;
        }
        return true;
    }

    private function upload_file()
    {
        $file = $this->request->getFile('photo');
        $result = ['status' => 'error', 'message' => '', 'file_name' => ''];

        if ($file && $file->isValid() && !$file->hasMoved()) {
            $allowed = ['jpg', 'jpeg', 'png'];
            $ext = strtolower($file->getExtension());
            if (in_array($ext, $allowed) && $file->getSize() <= 524288) {
                $newName = $file->getRandomName();
                $file->move(FCPATH . 'media_library/users/photo', $newName);
                @chmod(FCPATH . 'media_library/users/photo/' . $newName, 0777);
                $this->image_resize(FCPATH . 'media_library/users/photo/', $newName);
                $result['status'] = 'success';
                $result['message'] = 'uploaded';
                $result['file_name'] = $newName;
            } else {
                $result['message'] = 'File harus JPG/JPEG/PNG dan maksimal 512 KB.';
            }
        } else {
            $result['message'] = 'Tidak ada file yang diupload.';
        }
        return $result;
    }

    private function image_resize($path, $file_name)
    {
        $image = \Config\Services::image()
            ->withFile($path . '/' . $file_name)
            ->fit(session('media')['user_photo_width'] ?? 250, session('media')['user_photo_height'] ?? 600, 'center');
        $image->save($path . '/' . $file_name);
    }
}
