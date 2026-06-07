<?php

namespace App\Models;

use CodeIgniter\Model;

class M_class_group_settings extends Model
{
    protected $table = 'class_group_settings';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'academic_year_id', 'class_group_id', 'employee_id', 'is_deleted'
    ];

    public function getWhere(string $keyword = '', string $returnType = 'count', int $limit = 0, int $offset = 0)
    {
        $builder = $this->db->table($this->table . ' x1');
        $builder->select("
            x1.id
            , x2.academic_year
            , CONCAT(x3.class_group, IF((x4.major_short_name <> ''), CONCAT(' ',x4.major_short_name),''),IF((x3.sub_class_group <> ''),CONCAT(' - ',x3.sub_class_group),'')) AS class_name
            , CONCAT(x5.nik, ' - ', x5.full_name) AS employee_name
            , COUNT(x6.class_group_setting_id) AS total
            , x1.is_deleted
        ");
        $builder->join('academic_years x2', 'x1.academic_year_id = x2.id', 'LEFT');
        $builder->join('class_groups x3', 'x1.class_group_id = x3.id', 'LEFT');
        $builder->join('majors x4', 'x3.major_id = x4.id', 'LEFT');
        $builder->join('users x5', 'x1.employee_id = x5.id', 'LEFT');
        $builder->join('class_group_students x6', 'x1.id = x6.class_group_setting_id', 'LEFT');
        if (!empty($keyword)) {
            $builder->like("x2.academic_year", $keyword);
            $builder->orLike("CONCAT(x3.class_group, IF((x4.major_short_name <> ''), CONCAT(' ',x4.major_short_name),''),IF((x3.sub_class_group <> ''),CONCAT(' - ',x3.sub_class_group),''))", $keyword);
            $builder->orLike("x5.nik", $keyword);
            $builder->orLike("x5.full_name", $keyword);
        }
        $builder->groupBy('x1.id, x1.academic_year_id, x1.class_group_id');
        $builder->orderBy('x2.academic_year', 'DESC');
        $builder->orderBy('x3.class_group', 'ASC');
        $builder->orderBy('x3.major_id', 'ASC');
        $builder->orderBy('x3.sub_class_group', 'ASC');
        if ($returnType === 'count') return $builder->countAllResults();
        if ($limit > 0) $builder->limit($limit, $offset);
        return $builder->get();
    }
}
