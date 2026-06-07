<?php

namespace App\Controllers;

use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Psr\Log\LoggerInterface;

class Reset_password extends BaseController
{
    protected $m_users;

    public function initController(RequestInterface $request, ResponseInterface $response, LoggerInterface $logger)
    {
        parent::initController($request, $response, $logger);
        $this->m_users = model('App\Models\M_users');
    }

    public function index()
    {
        $forgot_password_key = service('uri')->getSegment(2);
        $is_exists = model('App\Models\M_helper')->isExists('forgot_password_key', $forgot_password_key, 'users');
        if ($is_exists) {
            return view('users/reset-password', $this->vars ?? []);
        }
        throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
    }

    public function process()
    {
        if ($this->request->isAJAX()) {
            if ($this->validation()) {
                $forgot_password_key = service('uri')->getSegment(2);
                $query = model('App\Models\M_helper')->rowObject('forgot_password_key', $forgot_password_key, 'users');
                if (is_object($query)) {
                    $request_date = new \DateTime($query->forgot_password_request_date);
                    $today = new \DateTime(date('Y-m-d H:i:s'));
                    $diff = $today->diff($request_date);
                    $hours = $diff->h;
                    $hours = $hours + ($diff->days * 24);
                    if ($hours > 48) {
                        $this->m_users->remove_forgot_password_key($query->id);
                        $this->vars['status'] = 'error';
                        $this->vars['message'] = 'expired';
                    } else {
                        $query = $this->m_users->reset_password($query->id);
                        $this->vars['status'] = $query ? 'success' : 'error';
                        $this->vars['message'] = $query ? 'has_updated' : 'cannot_updated';
                    }
                } else {
                    $this->vars['status'] = 'error';
                    $this->vars['message'] = '404';
                }
            } else {
                $this->vars['status'] = 'error';
                $this->vars['message'] = $this->validation->getErrors();
            }
            return $this->response->setContentType('application/json')->setJSON($this->vars ?? []);
        }
    }

    private function validation()
    {
        $this->validation = \Config\Services::validation();
        $this->validation->setRules([
            'password' => ['label' => 'Kata Sandi', 'rules' => 'trim|required|min_length[6]'],
            'c_password' => ['label' => 'Kata Sandi', 'rules' => 'trim|matches[password]'],
        ]);
        return $this->validation->withRequest($this->request)->run();
    }
}
