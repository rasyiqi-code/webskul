<?php

namespace App\Models;

use CodeIgniter\Model;

class M_scholarships extends Model
{
    protected $table = 'scholarships';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'student_id', 'scholarship_type', 'scholarship_description',
        'scholarship_start_year', 'scholarship_end_year', 'is_deleted'
    ];

    public function getWhere(string $keyword = '', string $returnType = 'count', int $limit = 0, int $offset = 0)
    {
        $builder = $this->db->table($this->table);
        $builder->select('id, scholarship_type, scholarship_description, scholarship_start_year, scholarship_end_year, is_deleted');
        if (filter_var(__session('is_student'), FILTER_VALIDATE_BOOLEAN)) {
            $builder->where('student_id', __session('user_id'));
        }
        if (!empty($keyword)) {
            $builder->groupStart();
            $builder->like('scholarship_type', $keyword);
            $builder->orLike('scholarship_description', $keyword);
            $builder->orLike('scholarship_start_year', $keyword);
            $builder->orLike('scholarship_end_year', $keyword);
            $builder->groupEnd();
        }
        if ($returnType === 'count') return $builder->countAllResults();
        if ($limit > 0) $builder->limit($limit, $offset);
        return $builder->get();
    }

    public function getByStudentId(int $studentId = 0)
    {
        return $this->db->table($this->table)
            ->select('id, scholarship_type, scholarship_description, scholarship_start_year, scholarship_end_year, is_deleted')
            ->where('student_id', $studentId)
            ->where('is_deleted', 'false')
            ->get();
    }
}
