<?php

namespace App\Models;

use CodeIgniter\Model;

class M_admission_types extends Model
{
    protected $table = 'options';
    protected $primaryKey = 'id';

    public function getTypes()
    {
        return $this->db->table($this->table)
            ->select('id, option_name')
            ->where('option_group', 'admission_type')
            ->where('is_deleted', 'false')
            ->orderBy('id', 'ASC')
            ->get();
    }

    public function dropdown()
    {
        $query = $this->getTypes();
        $data = [];
        foreach ($query->getResult() as $row) {
            $data[$row->id] = $row->option_name;
        }
        return $data;
    }

    public function getTypeId(string $typeName)
    {
        $row = $this->db->table($this->table)
            ->select('id')
            ->where('option_group', 'admission_type')
            ->where('LOWER(option_name)', strtolower($typeName))
            ->limit(1)
            ->get()
            ->getRow();
        return $row ? (int)$row->id : 0;
    }
}
