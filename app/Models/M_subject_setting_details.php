<?php

namespace App\Models;

use CodeIgniter\Model;

class M_subject_setting_details extends Model
{
    protected $table = 'admission_subject_setting_details';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'subject_setting_id', 'subject_id', 'visibility', 'is_deleted'
    ];

    public function getWhere(string $keyword = '', int $subjectSettingId = 0, string $returnType = 'count', int $limit = 0, int $offset = 0)
    {
        $builder = $this->db->table($this->table . ' x1');
        $builder->select('x1.id, x2.subject_name, x1.visibility, x1.is_deleted');
        $builder->join('subjects x2', 'x1.subject_id = x2.id', 'LEFT');
        $builder->where('x1.subject_setting_id', $subjectSettingId);
        if (!empty($keyword)) {
            $builder->groupStart();
            $builder->like('x2.subject_name', $keyword);
            $builder->groupEnd();
        }
        if ($returnType === 'count') return $builder->countAllResults();
        if ($limit > 0) $builder->limit($limit, $offset);
        return $builder->get();
    }
}
