<?php

namespace App\Models;

use CodeIgniter\Model;

class M_achievements extends Model
{
    protected $table = 'achievements';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'student_id', 'achievement_description', 'achievement_type',
        'achievement_level', 'achievement_year', 'achievement_organizer',
        'is_deleted'
    ];

    public function getWhere(string $keyword = '', string $returnType = 'count', int $limit = 0, int $offset = 0)
    {
        $builder = $this->db->table($this->table);
        $builder->select('id, achievement_description, achievement_type, achievement_level, achievement_year, achievement_organizer, is_deleted');
        if (filter_var(__session('is_student'), FILTER_VALIDATE_BOOLEAN)) {
            $builder->where('student_id', __session('user_id'));
        }
        if (!empty($keyword)) {
            $builder->groupStart();
            $builder->like('achievement_description', $keyword);
            $builder->orLike('achievement_type', $keyword);
            $builder->orLike('achievement_level', $keyword);
            $builder->orLike('achievement_year', $keyword);
            $builder->orLike('achievement_organizer', $keyword);
            $builder->groupEnd();
        }
        if ($returnType === 'count') return $builder->countAllResults();
        if ($limit > 0) $builder->limit($limit, $offset);
        return $builder->get();
    }

    public function getByStudentId(int $studentId = 0)
    {
        return $this->db->table($this->table)
            ->select('id, achievement_description, achievement_type, achievement_level, achievement_year, achievement_organizer, is_deleted')
            ->where('student_id', $studentId)
            ->where('is_deleted', 'false')
            ->get();
    }
}
