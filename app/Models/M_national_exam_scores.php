<?php

namespace App\Models;

use App\Models\BaseModel;

class M_national_exam_scores extends BaseModel
{
    protected $table = 'national_exam_scores';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'student_id', 'subject_id', 'score', 'is_deleted'
    ];

    public function getWhere(string $keyword = '', string $returnType = 'count', int $limit = 0, int $offset = 0)
    {
        $builder = $this->db->table($this->table . ' x1');
        $builder->select("
            x1.id, x3.nis, x3.full_name, x2.subject_name, x1.score
        ");
        $builder->join('subjects x2', 'x1.subject_id = x2.id', 'LEFT');
        $builder->join('users x3', 'x1.student_id = x3.id', 'LEFT');
        if (!empty($keyword)) {
            $builder->groupStart();
            $builder->like('x3.nis', $keyword);
            $builder->orLike('x3.full_name', $keyword);
            $builder->orLike('x2.subject_name', $keyword);
            $builder->groupEnd();
        }
        if ($returnType === 'count') return $builder->countAllResults();
        if ($limit > 0) $builder->limit($limit, $offset);
        return $builder->get();
    }

    public function getStudentScores(int $studentId)
    {
        return $this->db->table($this->table . ' x1')
            ->select("x1.*, x2.subject_name")
            ->join('subjects x2', 'x1.subject_id = x2.id', 'LEFT')
            ->where('x1.student_id', $studentId)
            ->where('x1.is_deleted', 'false')
            ->get();
    }
}
