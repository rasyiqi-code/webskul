<?php

namespace App\Models;

use App\Models\BaseModel;

class M_messages extends BaseModel
{
    protected $table = 'comments';
    protected $primaryKey = 'id';

    public function getWhere(string $keyword = '', string $returnType = 'count', int $limit = 0, int $offset = 0)
    {
        $builder = $this->db->table($this->table);
        $builder->select('id, comment_author, comment_email, created_at, comment_content, is_deleted');
        $builder->where('comment_type', 'message');
        if (!empty($keyword)) {
            $builder->groupStart();
            $builder->like('comment_author', $keyword);
            $builder->orLike('comment_email', $keyword);
            $builder->orLike('created_at', $keyword);
            $builder->orLike('comment_content', $keyword);
            $builder->groupEnd();
        }
        if ($returnType === 'count') return $builder->countAllResults();
        if ($limit > 0) $builder->limit($limit, $offset);
        return $builder->get();
    }

    public function reply(int $id, array $dataset)
    {
        $this->db->table($this->table)
            ->where('id', $id)
            ->update($dataset);
        $query = $this->db->table($this->table)
            ->where('id', $id)
            ->get()
            ->getRow();
        if (!$query) return false;
        $config = [
            'protocol' => 'smtp',
            'smtp_host' => __session('mail_server')['smtp_host'] ?? '',
            'smtp_port' => __session('mail_server')['smtp_port'] ?? 587,
            'smtp_user' => __session('mail_server')['smtp_user'] ?? '',
            'smtp_pass' => __session('mail_server')['smtp_pass'] ?? '',
            'smtp_crypto' => 'ssl',
            'mailtype' => 'html',
            'smtp_timeout' => '4',
            'charset' => 'iso-8859-1',
            'wordwrap' => true,
            'crlf' => "\r\n"
        ];
        $email = \Config\Services::email();
        $email->initialize($config);
        $email->setNewline("\r\n");
        $schoolProfile = __session('school_profile');
        $email->setFrom($schoolProfile['email'] ?? '', $schoolProfile['school_name'] ?? '');
        $email->setTo($query->comment_email);
        $email->setSubject($dataset['comment_subject'] ?? '');
        $email->setMessage($dataset['comment_reply'] ?? '');
        if ($email->send()) {
            return true;
        }
        return false;
    }
}
