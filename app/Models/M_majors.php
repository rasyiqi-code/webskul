<?php

namespace App\Models;

use CodeIgniter\Model;

class M_majors extends Model
{
    protected $table = 'majors';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'major_name', 'major_short_name', 'is_active', 'is_deleted'
    ];

    public function getWhere(string $keyword = '', string $returnType = 'count', int $limit = 0, int $offset = 0)
    {
        $builder = $this->db->table($this->table);
        $builder->select('id, major_name, major_short_name, is_active, is_deleted');
        if (!empty($keyword)) {
            $builder->like('major_name', $keyword);
            $builder->orLike('major_short_name', $keyword);
        }
        if ($returnType === 'count') return $builder->countAllResults();
        if ($limit > 0) $builder->limit($limit, $offset);
        return $builder->get();
    }

    public function majorCount(): bool
    {
        $count = $this->db->table($this->table)
            ->where('is_deleted', 'false')
            ->countAllResults();
        return $count > 0;
    }

    public function dropdown()
    {
        $query = $this->db->table($this->table)
            ->select('id, major_name')
            ->where('is_deleted', 'false')
            ->where('is_active', 'true')
            ->get();
        $data = [];
        foreach ($query->getResult() as $row) {
            $data[$row->id] = $row->major_name;
        }
        return $data;
    }
}
