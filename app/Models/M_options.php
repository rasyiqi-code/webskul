<?php

namespace App\Models;

use CodeIgniter\Model;

class M_options extends Model
{
    protected $table = 'options';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'option_group', 'option_name', 'is_deleted'
    ];

    public function getWhere(string $keyword = '', string $optionGroup = '', string $returnType = 'count', int $limit = 0, int $offset = 0)
    {
        $builder = $this->db->table($this->table);
        $builder->select('id, option_group, option_name, is_deleted');
        $builder->where('option_group', $optionGroup);
        if (!empty($keyword)) {
            $builder->groupStart();
            $builder->like('option_name', $keyword);
            $builder->groupEnd();
        }
        $builder->orderBy('id', 'ASC');
        if ($returnType === 'count') return $builder->countAllResults();
        if ($limit > 0) $builder->limit($limit, $offset);
        return $builder->get();
    }

    public function getOptions(string $optionGroup = '')
    {
        $data = [];
        if (empty($optionGroup)) return $data;
        $query = $this->db->table($this->table)
            ->select('id, option_name')
            ->where('option_group', $optionGroup)
            ->where('is_deleted', 'false')
            ->orderBy('id', 'ASC')
            ->get();
        foreach ($query->getResult() as $row) {
            $data[$row->id] = $row->option_name;
        }
        return $data;
    }

    public function getOptionId(string $optionGroup = '', string $optionName = '')
    {
        $row = $this->db->table('options')
            ->select('id')
            ->where('option_group', $optionGroup)
            ->where('LOWER(option_name)', trim(strtolower($optionName)))
            ->limit(1)
            ->get()
            ->getRow();
        return $row ? (int)$row->id : 0;
    }
}
