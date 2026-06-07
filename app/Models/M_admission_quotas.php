<?php

namespace App\Models;

use App\Models\BaseModel;

class M_admission_quotas extends BaseModel
{
    protected $table = 'admission_quotas';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'academic_year_id', 'admission_type_id', 'major_id', 'quota', 'is_deleted'
    ];

    public function getWhere(string $keyword = '', string $returnType = 'count', int $limit = 0, int $offset = 0)
    {
        $builder = $this->db->table($this->table . ' x1');
        $builder->select("
            x1.id
            , x2.academic_year
            , x3.option_name AS admission_type
            , COALESCE(x4.major_name, '-') AS major_name
            , x1.quota
            , x1.is_deleted
        ");
        $builder->join('academic_years x2', 'x1.academic_year_id = x2.id', 'LEFT');
        $builder->join('options x3', 'x1.admission_type_id = x3.id', 'LEFT');
        $builder->join('majors x4', 'x1.major_id = x4.id', 'LEFT');
        if (!empty($keyword)) {
            $builder->like('x2.academic_year', $keyword);
            $builder->orLike('x3.option_name', $keyword);
            $builder->orLike('x4.major_name', $keyword);
            $builder->orLike('x1.quota', $keyword);
        }
        if ($returnType === 'count') return $builder->countAllResults();
        if ($limit > 0) $builder->limit($limit, $offset);
        return $builder->get();
    }
}
