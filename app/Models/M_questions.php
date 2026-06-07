<?php

namespace App\Models;

use CodeIgniter\Model;

class M_questions extends Model
{
    protected $table = 'questions';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'question', 'is_active', 'is_deleted'
    ];

    public function getWhere(string $keyword = '', string $returnType = 'count', int $limit = 0, int $offset = 0)
    {
        $builder = $this->db->table($this->table);
        if (!empty($keyword)) $builder->like('question', $keyword);
        if ($returnType === 'count') return $builder->countAllResults();
        if ($limit > 0) $builder->limit($limit, $offset);
        return $builder->get();
    }

    public function dropdown()
    {
        $query = $this->db->table($this->table)
            ->select('id, question')
            ->get();
        $data = [];
        foreach ($query->getResult() as $row) {
            $data[$row->id] = $row->question;
        }
        return $data;
    }

    public function getActiveQuestion()
    {
        return $this->db->table($this->table)
            ->select('id, question')
            ->where('is_active', 'true')
            ->where('is_deleted', 'false')
            ->limit(1)
            ->get()
            ->getRow();
    }
}
