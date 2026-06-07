<?php

namespace App\Models;

use App\Models\BaseModel;

class M_settings extends BaseModel
{
    protected $table = 'settings';
    protected $primaryKey = 'id';
    protected $returnType = 'object';
    protected $allowedFields = [
        'setting_group', 'setting_variable', 'setting_value',
        'setting_default_value', 'setting_access_group', 'setting_description'
    ];

    public function getWhere(string $keyword = '', string $setting_group = 'general', string $returnType = 'count', int $limit = 0, int $offset = 0)
    {
        $builder = $this->db->table($this->table);
        $builder->select('id, setting_variable, COALESCE(`setting_value`, `setting_default_value`) AS setting_value, setting_description, is_deleted');
        $builder->where('setting_group', $setting_group);
        if (!empty($keyword)) {
            $builder->groupStart();
            $builder->like('setting_description', $keyword);
            $builder->orLike('setting_value', $keyword);
            $builder->groupEnd();
        }
        if ($returnType === 'count') return $builder->countAllResults();
        if ($limit > 0) $builder->limit($limit, $offset);
        return $builder->get();
    }

    public function getSetting(string $variable)
    {
        $row = $this->db->table($this->table)
            ->select('COALESCE(`setting_value`, `setting_default_value`) AS setting_value')
            ->where('setting_variable', $variable)
            ->get()
            ->getRow();
        return $row ? $row->setting_value : null;
    }

    public function getGroupSettings(string $group)
    {
        $rows = $this->db->table($this->table)
            ->select('setting_variable, COALESCE(`setting_value`, `setting_default_value`) AS setting_value')
            ->where('setting_group', $group)
            ->get()
            ->getResult();
        $data = [];
        foreach ($rows as $row) {
            $data[$row->setting_variable] = $row->setting_value;
        }
        return $data;
    }
}
