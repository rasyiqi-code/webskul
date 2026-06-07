<?php

namespace App\Models;

use App\Models\BaseModel;

class M_file_categories extends BaseModel
{
    protected $table = 'categories';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'category_name', 'category_description', 'category_slug',
        'category_type', 'is_deleted'
    ];

    public function getWhere(string $keyword = '', string $returnType = 'count', int $limit = 0, int $offset = 0)
    {
        $builder = $this->db->table($this->table);
        $builder->where('category_type', 'file');
        if (!empty($keyword)) {
            $builder->groupStart();
            $builder->like('category_name', $keyword);
            $builder->orLike('category_description', $keyword);
            $builder->orLike('category_slug', $keyword);
            $builder->groupEnd();
        }
        if ($returnType === 'count') return $builder->countAllResults();
        if ($limit > 0) $builder->limit($limit, $offset);
        return $builder->get();
    }

    public function dropdown()
    {
        $data = [];
        $query = $this->db->table($this->table)
            ->select('id, category_name')
            ->where('category_type', 'file')
            ->where('is_deleted', 'false')
            ->orderBy('id', 'ASC')
            ->get();
        foreach ($query->getResult() as $row) {
            $data[$row->id] = $row->category_name;
        }
        return $data;
    }
}
