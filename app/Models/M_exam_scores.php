<?php

namespace App\Models;

use App\Models\BaseModel;

class M_exam_scores extends BaseModel
{
    protected $table = 'admission_exam_scores';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'student_id', 'exam_schedule_id', 'score', 'is_deleted'
    ];

    public function getWhere(string $keyword = '', string $returnType = 'count', int $limit = 0, int $offset = 0)
    {
        $builder = $this->db->table($this->table . ' x1');
        $builder->select("
            x1.id, x4.registration_number, x4.full_name,
            x5.subject_name, x1.score, x1.is_deleted
        ");
        $builder->join('admission_exam_schedules x2', 'x1.exam_schedule_id = x2.id', 'LEFT');
        $builder->join('admission_subject_setting_details x3', 'x2.subject_setting_detail_id = x3.id', 'LEFT');
        $builder->join('subjects x5', 'x3.subject_id = x5.id', 'LEFT');
        $builder->join('users x4', 'x1.student_id = x4.id', 'LEFT');
        if (!empty($keyword)) {
            $builder->groupStart();
            $builder->like('x4.registration_number', $keyword);
            $builder->orLike('x4.full_name', $keyword);
            $builder->orLike('x5.subject_name', $keyword);
            $builder->groupEnd();
        }
        if ($returnType === 'count') return $builder->countAllResults();
        if ($limit > 0) $builder->limit($limit, $offset);
        return $builder->get();
    }

    public function getStudentScores(int $studentId)
    {
        return $this->db->table($this->table . ' x1')
            ->select("x1.*, x5.subject_name")
            ->join('admission_exam_schedules x2', 'x1.exam_schedule_id = x2.id', 'LEFT')
            ->join('admission_subject_setting_details x3', 'x2.subject_setting_detail_id = x3.id', 'LEFT')
            ->join('subjects x5', 'x3.subject_id = x5.id', 'LEFT')
            ->where('x1.student_id', $studentId)
            ->where('x1.is_deleted', 'false')
            ->get();
    }
}
