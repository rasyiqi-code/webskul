<?php

namespace App\Models;

use App\Models\BaseModel;

class M_user_groups extends BaseModel
{
    protected $table = 'user_groups';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'user_group', 'is_deleted'
    ];

    public function getWhere(string $keyword = '', string $returnType = 'count', int $limit = 0, int $offset = 0)
    {
        $builder = $this->db->table($this->table);
        $builder->select('id, user_group, is_deleted');
        if (!empty($keyword)) $builder->like('user_group', $keyword);
        if ($returnType === 'count') return $builder->countAllResults();
        if ($limit > 0) $builder->limit($limit, $offset);
        return $builder->get();
    }

    public function dropdown()
    {
        $query = $this->db->table($this->table)
            ->select('id, user_group')
            ->where('is_deleted', 'false')
            ->get();
        $data = [];
        foreach ($query->getResult() as $row) {
            $data[$row->id] = $row->user_group;
        }
        return $data;
    }
}
