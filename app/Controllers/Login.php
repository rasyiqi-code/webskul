<?php

namespace App\Controllers;

use App\Libraries\Auth;
use App\Libraries\Token;

/**
 * Login Controller
 * Cek apakah user sudah login dilakukan di method index(),
 * bukan di initController() karena return value initController() diabaikan CI4.
 */
class Login extends BaseController
{
    public function index()
    {
        // Jika sudah login, langsung redirect ke dashboard
        $auth = new Auth();
        if ($auth->loggedIn()) {
            return redirect()->to('dashboard');
        }

        $data['page_title'] = 'Login to Our Site';
        $data['ip_banned']  = $auth->ipBanned(get_ip_address());
        $data['login_info'] = $data['ip_banned']
            ? 'The login page has been blocked for 10 minutes'
            : 'Enter your username and password to log on';

        return view('users/login', $data);
    }

    public function verify()
    {
        if ($this->request->isAJAX()) {
            $validation = $this->validateRequest();
            if ($validation) {
                $email      = $this->request->getPost('email');
                $password   = $this->request->getPost('password');
                $ip_address = get_ip_address();
                $auth       = new Auth();
                $verify     = $auth->verify($email, $password, $ip_address) ? 'success' : 'error';

                $this->vars['status']    = $verify;
                $this->vars['message']  = $verify === 'success' ? 'logged_in' : 'not_logged_in';
                $this->vars['ip_banned'] = $auth->ipBanned($ip_address);
            } else {
                $this->vars['status']    = 'error';
                $this->vars['message']  = $this->validator->getErrors();
                $this->vars['ip_banned'] = false;
            }

            return $this->response
                ->setContentType('application/json')
                ->setJSON($this->vars);
        }
    }

    private function validateRequest(): bool
    {
        $this->validation->setRules([
            'email'    => 'trim|required|valid_email',
            'password' => 'trim|required',
        ]);

        return $this->validation->withRequest($this->request)->run();
    }
}
