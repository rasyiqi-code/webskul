<?php

namespace App\Models;

use App\Models\BaseModel;

class M_subscribers extends BaseModel
{
    protected $table = 'subscribers';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'email'
    ];

    public function getWhere(string $keyword = '', string $returnType = 'count', int $limit = 0, int $offset = 0)
    {
        $builder = $this->db->table($this->table);
        $builder->select('id, email');
        if (!empty($keyword)) $builder->like('email', $keyword);
        if ($returnType === 'count') return $builder->countAllResults();
        if ($limit > 0) $builder->limit($limit, $offset);
        return $builder->get();
    }
}
