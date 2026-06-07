<?php

namespace App\Models;

use App\Models\BaseModel;

class M_quotes extends BaseModel
{
    protected $table = 'quotes';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'quote', 'quote_by', 'is_deleted'
    ];

    public function getWhere(string $keyword = '', string $returnType = 'count', int $limit = 0, int $offset = 0)
    {
        $builder = $this->db->table($this->table);
        if (!empty($keyword)) {
            $builder->like('quote', $keyword);
            $builder->orLike('quote_by', $keyword);
        }
        if ($returnType === 'count') return $builder->countAllResults();
        if ($limit > 0) $builder->limit($limit, $offset);
        return $builder->get();
    }

    public function getQuotes(int $limit = 0)
    {
        $builder = $this->db->table($this->table);
        $builder->select('id, quote, quote_by');
        $builder->where('is_deleted', 'false');
        $builder->orderBy('created_at', 'DESC');
        if ($limit > 0) $builder->limit($limit);
        return $builder->get();
    }
}
