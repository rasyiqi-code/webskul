<?php

namespace App\Models;

use CodeIgniter\Model;

class M_exam_schedules extends Model
{
    protected $table = 'admission_exam_schedules';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'subject_setting_detail_id', 'room_id', 'exam_date',
        'exam_start_time', 'exam_end_time', 'is_deleted'
    ];

    public function getWhere(string $keyword = '', string $returnType = 'count', int $limit = 0, int $offset = 0)
    {
        $builder = $this->db->table($this->table . ' x1');
        $builder->select('x1.id, x2.room_name, x2.room_capacity, x3.option_name AS building_name, x1.exam_date, x1.exam_start_time, x1.exam_end_time, x1.is_deleted');
        $builder->join('rooms x2', 'x1.room_id = x2.id', 'LEFT');
        $builder->join('options x3', 'x2.building_id = x3.id', 'LEFT');
        if (!empty($keyword)) {
            $builder->groupStart();
            $builder->like('x1.exam_date', $keyword);
            $builder->orLike('x1.exam_start_time', $keyword);
            $builder->orLike('x1.exam_end_time', $keyword);
            $builder->orLike('x2.room_name', $keyword);
            $builder->orLike('x2.room_capacity', $keyword);
            $builder->orLike('x3.option_name', $keyword);
            $builder->groupEnd();
        }
        if ($returnType === 'count') return $builder->countAllResults();
        if ($limit > 0) $builder->limit($limit, $offset);
        return $builder->get();
    }
}
