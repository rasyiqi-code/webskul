<?php

namespace App\Controllers\Public;

use App\Controllers\PublicController;
use App\Libraries\Token;

class Pollings extends PublicController
{
    public function index()
    {
        $m_pollings = model('App\Models\M_pollings');
        $query = get_active_question();
        $label_arr = [];
        $value_arr = [];
        $question_text = 'Tidak ada jajak pendapat aktif';
        if ($query && isset($query->id)) {
            $results = $m_pollings->getPollings($query->id);
            foreach ($results->getResult() as $row) {
                $label_arr[] = $row->labels;
                $value_arr[] = $row->data;
            }
            $question_text = $query->question;
        }

        $data['labels'] = json_encode($label_arr, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_AMP | JSON_HEX_QUOT | JSON_THROW_ON_ERROR | JSON_UNESCAPED_SLASHES);
        $data['data'] = json_encode($value_arr, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_AMP | JSON_HEX_QUOT | JSON_THROW_ON_ERROR | JSON_UNESCAPED_SLASHES);
        $data['question'] = $question_text;
        $data['page_title'] = 'Hasil Jajak Pendapat';
        $data['content'] = 'themes/' . theme_folder() . '/polling-results';
        return view('themes/' . theme_folder() . '/index', $data);
    }

    public function save()
    {
        if (!$this->request->isAJAX()) {
            return $this->response->setStatusCode(404);
        }

        $token = new Token();
        $csrf_token = $this->request->getPost('csrf_token');

        if (!$csrf_token || !$token->is_valid_token($csrf_token)) {
            return $this->response->setContentType('application/json')->setJSON([
                'status' => 'token_error',
                'csrf_token' => $token->get_token(),
            ]);
        }

        if ($this->validation()) {
            $answer_id = _toInteger($this->request->getPost('answer_id'));
            $m_pollings = model('App\Models\M_pollings');
            $save = $m_pollings->insert($answer_id);
            $status = $save ? 'success' : 'info';
            $message = $save ? 'Terima kasih sudah mengikuti polling kami.' : 'Anda sudah mengikuti polling kami hari ini.';
        } else {
            $status = 'error';
            $message = $this->validation->getErrors();
        }

        return $this->response->setContentType('application/json')->setJSON([
            'status' => $status,
            'message' => $message,
            'csrf_token' => $token->get_token(),
        ]);
    }

    private function validation()
    {
        $this->validation->setRules([
            'answer_id' => ['label' => 'Jawaban', 'rules' => 'trim|required|is_natural_no_zero'],
        ]);
        return $this->validation->withRequest($this->request)->run();
    }
}
