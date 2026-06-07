<?php

namespace App\Models;

use CodeIgniter\Model;

class M_subjects extends Model
{
    protected $table = 'subjects';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'subject_name', 'is_deleted'
    ];

    public function getWhere(string $keyword = '', string $returnType = 'count', int $limit = 0, int $offset = 0)
    {
        $builder = $this->db->table($this->table);
        $builder->select('id, subject_name, is_deleted');
        if (!empty($keyword)) $builder->like('subject_name', $keyword);
        if ($returnType === 'count') return $builder->countAllResults();
        if ($limit > 0) $builder->limit($limit, $offset);
        return $builder->get();
    }

    public function dropdown()
    {
        $query = $this->db->table($this->table)
            ->select('id, subject_name')
            ->where('is_deleted', 'false')
            ->get();
        $data = [];
        foreach ($query->getResult() as $row) {
            $data[$row->id] = $row->subject_name;
        }
        return $data;
    }

    public function getSubjects()
    {
        $query = $this->db->table($this->table)
            ->select('id, subject_name')
            ->where('is_deleted', 'false')
            ->get();
        $data = [];
        foreach ($query->getResult() as $row) {
            $data[] = [
                'id' => $row->id,
                'subject_name' => $row->subject_name
            ];
        }
        return $data;
    }
}
