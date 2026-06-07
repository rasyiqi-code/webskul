<?php

namespace App\Controllers;

use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Psr\Log\LoggerInterface;

class Lost_password extends BaseController
{
    public function initController(RequestInterface $request, ResponseInterface $response, LoggerInterface $logger)
    {
        parent::initController($request, $response, $logger);
    }

    public function index()
    {
        return view('users/lost-password');
    }

    public function process()
    {
        if ($this->request->isAJAX()) {
            if ($this->validation()) {
                $email = $this->request->getPost('email');
                $m_users = model('App\Models\M_users');
                $query = $m_users->get_user_by_email($email);
                if (null == $query) {
                    $this->vars['status'] = 'warning';
                    $this->vars['message'] = 'Email anda tidak terdaftar pada database kami';
                } else {
                    $forgot_password_key = sha1($email . uniqid(mt_rand(), true));
                    $school_profile = session('school_profile');
                    $mail_server = session('mail_server');
                    $message = "Dear " . $query['full_name'];
                    $message .= "<br><br>";
                    $message .= "Silahkan klik tautan berikut untuk melakukan perubahan kata sandi Anda.";
                    $message .= "<br>";
                    $message .= "<a href=" . base_url() . 'reset-password/' . $forgot_password_key . ">" . base_url() . 'reset-password/' . $forgot_password_key . "</a>";
                    $message .= "<br><br>";
                    $message .= "Abaikan email ini jika Anda tidak mengajukan perubahan kata sandi ini.";
                    $message .= "<br><br>";
                    $message .= "Terima Kasih.";
                    $message .= "<br><br>";
                    $message .= "Admin";
                    $message .= "<br>";
                    $message .= $school_profile['school_name'];

                    $emailService = \Config\Services::email();
                    $config = [
                        'protocol' => 'smtp',
                        'SMTPHost' => $mail_server['smtp_host'] ?? '',
                        'SMTPPort' => $mail_server['smtp_port'] ?? 587,
                        'SMTPUser' => $mail_server['smtp_user'] ?? '',
                        'SMTPPass' => $mail_server['smtp_pass'] ?? '',
                        'SMTPCrypto' => 'ssl',
                        'mailType' => 'html',
                        'SMTPTimeout' => '4',
                        'charset' => 'iso-8859-1',
                        'wordWrap' => true,
                        'CRLF' => "\r\n",
                        'newline' => "\r\n"
                    ];
                    $emailService->initialize($config);
                    $emailService->setFrom($school_profile['email'], $school_profile['school_name']);
                    $emailService->setTo($query['email']);
                    $emailService->setSubject('Lupa Kata Sandi');
                    $emailService->setMessage($message);

                    if ($emailService->send()) {
                        $update = $m_users->set_forgot_password_key($query['email'], $forgot_password_key);
                        if ($update) {
                            $this->vars['status'] = 'success';
                            $this->vars['message'] = 'Tautan untuk mengubah kata sandi sudah kami kirimkan melalui email. Jika email tidak ditemukan, silahkan periksa pada folder spam.';
                        } else {
                            $this->vars['status'] = 'warning';
                            $this->vars['message'] = 'Terjadi kesalahan dalam proses ubah kata sandi. Silahkan hubungi operator website untuk konfirmasi.';
                        }
                    } else {
                        $this->vars['status'] = 'warning';
                        $this->vars['message'] = 'Tautan untuk mengubah kata sandi tidak terkirim. Silahkan kirim email ke ' . $school_profile['email'];
                    }
                }
            } else {
                $this->vars['status'] = 'error';
                $this->vars['message'] = $this->validation->getErrors();
            }
            return $this->response->setContentType('application/json')->setJSON($this->vars);
        }
    }

    private function validation()
    {
        $this->validation = \Config\Services::validation();
        $this->validation->setRules([
            'email' => ['label' => 'Email', 'rules' => 'trim|required|valid_email'],
        ]);
        return $this->validation->withRequest($this->request)->run();
    }
}
