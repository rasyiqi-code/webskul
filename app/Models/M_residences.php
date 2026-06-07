<?php

namespace App\Models;

use CodeIgniter\Model;

class M_residences extends Model
{
    protected $table = 'options';
    protected $primaryKey = 'id';

    public function getResidences()
    {
        return $this->db->table($this->table)
            ->select('id, option_name')
            ->where('option_group', 'residence')
            ->where('is_deleted', 'false')
            ->orderBy('id', 'ASC')
            ->get();
    }

    public function dropdown()
    {
        $query = $this->getResidences();
        $data = [];
        foreach ($query->getResult() as $row) {
            $data[$row->id] = $row->option_name;
        }
        return $data;
    }
}
