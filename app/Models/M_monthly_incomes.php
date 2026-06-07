<?php

namespace App\Models;

use CodeIgniter\Model;

class M_monthly_incomes extends Model
{
    protected $table = 'options';
    protected $primaryKey = 'id';

    public function getIncomes()
    {
        return $this->db->table($this->table)
            ->select('id, option_name')
            ->where('option_group', 'monthly_income')
            ->where('is_deleted', 'false')
            ->orderBy('id', 'ASC')
            ->get();
    }

    public function dropdown()
    {
        $query = $this->getIncomes();
        $data = [];
        foreach ($query->getResult() as $row) {
            $data[$row->id] = $row->option_name;
        }
        return $data;
    }
}
