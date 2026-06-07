<?php

namespace App\Controllers;

/**
 * AdminController
 * Base controller untuk semua halaman admin yang memerlukan autentikasi dan privilege.
 * Autentikasi dan cek privilege ditangani oleh Filter (AuthFilter & PrivilegeFilter),
 * bukan di sini — karena return value initController() diabaikan oleh CI4.
 */
class AdminController extends BaseController
{
    protected $pk;
    protected $table;

    public function delete()
    {
        if ($this->request->isAJAX()) {
            $this->vars['status']  = 'warning';
            $this->vars['message'] = 'not_selected';
            $ids = explode(',', $this->request->getPost($this->pk));

            if (count($ids) > 0) {
                $model = model('App\Models\M_helper');
                if ($model->softDelete($ids, $this->table)) {
                    $this->vars = [
                        'status'  => 'success',
                        'message' => 'deleted',
                        'id'      => $ids
                    ];
                } else {
                    $this->vars = [
                        'status'  => 'error',
                        'message' => 'not_deleted'
                    ];
                }
            }

            return $this->response
                ->setContentType('application/json')
                ->setJSON($this->vars);
        }
    }

    public function restore()
    {
        if ($this->request->isAJAX()) {
            $this->vars['status']  = 'warning';
            $this->vars['message'] = 'not_selected';
            $ids = explode(',', $this->request->getPost($this->pk));

            if (count($ids) > 0) {
                $model = model('App\Models\M_helper');
                if ($model->restore($ids, $this->table)) {
                    $this->vars = [
                        'status'  => 'success',
                        'message' => 'restored',
                        'id'      => $ids
                    ];
                } else {
                    $this->vars = [
                        'status'  => 'error',
                        'message' => 'not_restored'
                    ];
                }
            }

            return $this->response
                ->setContentType('application/json')
                ->setJSON($this->vars);
        }
    }

    public function findId()
    {
        if ($this->request->isAJAX()) {
            $id    = _toInteger($this->request->getPost('id'));
            $query = _isNaturalNumber($id)
                ? model('App\Models\M_helper')->rowObject($this->pk, $id, $this->table)
                : [];

            return $this->response
                ->setContentType('application/json')
                ->setJSON($query);
        }
    }

    /**
     * Alias find_id untuk kompatibilitas dengan frontend JavaScript (form_builder.js).
     */
    public function find_id()
    {
        return $this->findId();
    }

    public function upload()
    {
        if ($this->request->isAJAX()) {
            $id = _toInteger($this->request->getPost('id'));

            if (_isNaturalNumber($id)) {
                $query     = model('App\Models\M_helper')->rowObject($this->pk, $id, $this->table);
                $file_name = $query->photo ?? '';
                $file      = $this->request->getFile('file');

                if ($file && $file->isValid() && !$file->hasMoved()) {
                    $newName = $file->getRandomName();
                    $file->move(FCPATH . 'media_library/users/photo/', $newName);

                    if (is_file(FCPATH . 'media_library/users/photo/' . $file_name)) {
                        @unlink(FCPATH . 'media_library/users/photo/' . $file_name);
                    }

                    model('App\Models\M_helper')->updateData($id, $this->table, ['photo' => $newName]);
                    $this->imageResize(FCPATH . 'media_library/users/photo', $newName);

                    $this->vars['status']  = 'success';
                    $this->vars['message'] = 'uploaded';
                } else {
                    $this->vars['status']  = 'error';
                    $this->vars['message'] = $file ? $file->getErrorString() : 'No file uploaded';
                }
            }

            return $this->response
                ->setContentType('application/json')
                ->setJSON($this->vars);
        }
    }

    private function imageResize(string $path, string $fileName): void
    {
        $general = session('media');
        $width   = ($general !== null && isset($general['user_photo_width']) && (int) $general['user_photo_width'] > 0)
            ? (int) $general['user_photo_width']
            : 250;

        $image = \Config\Services::image()
            ->withFile($path . '/' . $fileName)
            ->resist(false, false)
            ->resize($width, 0, true);
        $image->save($path . '/' . $fileName);
    }
}
