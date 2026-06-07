<?php

namespace App\Models;

use App\Models\BaseModel;

class M_modules extends BaseModel
{
    protected $table = 'modules';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'module_name', 'module_description', 'module_url', 'is_deleted'
    ];

    public function getWhere(string $keyword = '', string $returnType = 'count', int $limit = 0, int $offset = 0)
    {
        $builder = $this->db->table($this->table);
        $builder->select('id, module_name, module_description, module_url, is_deleted');
        if (!empty($keyword)) {
            $builder->like('module_name', $keyword);
            $builder->orLike('module_description', $keyword);
            $builder->orLike('module_url', $keyword);
        }
        if ($returnType === 'count') return $builder->countAllResults();
        if ($limit > 0) $builder->limit($limit, $offset);
        return $builder->get();
    }

    public function dropdown()
    {
        $query = $this->db->table($this->table)
            ->select('id, module_name')
            ->where('is_deleted', 'false')
            ->get();
        $data = [];
        foreach ($query->getResult() as $row) {
            $data[$row->id] = $row->module_name;
        }
        return $data;
    }
}
