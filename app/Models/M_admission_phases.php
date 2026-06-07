<?php

namespace App\Models;

use App\Models\BaseModel;

class M_admission_phases extends BaseModel
{
    protected $table = 'admission_phases';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'academic_year_id', 'phase_name', 'phase_start_date',
        'phase_end_date', 'is_deleted'
    ];

    public function getWhere(string $keyword = '', string $returnType = 'count', int $limit = 0, int $offset = 0)
    {
        $builder = $this->db->table($this->table . ' x1');
        $builder->select('x1.id, x1.phase_name, x2.academic_year, x1.phase_start_date, x1.phase_end_date, x1.is_deleted');
        $builder->join('academic_years x2', 'x1.academic_year_id = x2.id', 'LEFT');
        if (!empty($keyword)) {
            $builder->like('x1.phase_name', $keyword);
            $builder->orLike('x2.academic_year', $keyword);
            $builder->orLike('x1.phase_start_date', $keyword);
            $builder->orLike('x1.phase_end_date', $keyword);
        }
        if ($returnType === 'count') return $builder->countAllResults();
        if ($limit > 0) $builder->limit($limit, $offset);
        return $builder->get();
    }

    public function getCurrentPhase()
    {
        $row = $this->db->table($this->table)
            ->select('id, phase_name, phase_start_date, phase_end_date')
            ->where('CURDATE() >= phase_start_date')
            ->where('CURDATE() <= phase_end_date')
            ->where('is_deleted', 'false')
            ->orderBy('phase_start_date', 'DESC')
            ->limit(1)
            ->get()
            ->getRow();
        if ($row) {
            return [
                'id' => $row->id,
                'phase_name' => $row->phase_name,
                'phase_start_date' => $row->phase_start_date,
                'phase_end_date' => $row->phase_end_date
            ];
        }
        return [];
    }

    public function dropdown()
    {
        $query = $this->db->table($this->table)
            ->select('id, phase_name')
            ->where('is_deleted', 'false')
            ->orderBy('phase_name', 'DESC')
            ->get();
        $data = [];
        foreach ($query->getResult() as $row) {
            $data[$row->id] = $row->phase_name;
        }
        return $data;
    }
}
