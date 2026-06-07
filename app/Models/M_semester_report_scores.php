<?php

namespace App\Models;

use App\Models\BaseModel;

class M_semester_report_scores extends BaseModel
{
    protected $table = 'semester_report_scores';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'student_id', 'academic_year_id', 'semester', 'course_class_id',
        'knowledge_score', 'skill_score', 'final_score',
        'knowledge_grade', 'skill_grade', 'description', 'is_deleted'
    ];

    public function getWhere(string $keyword = '', string $returnType = 'count', int $limit = 0, int $offset = 0)
    {
        $builder = $this->db->table($this->table . ' x1');
        $builder->select("
            x1.id, x3.nis, x3.full_name, x4.subject_name,
            x1.knowledge_score, x1.skill_score, x1.final_score,
            x1.knowledge_grade, x1.skill_grade, x1.description
        ");
        $builder->join('users x3', 'x1.student_id = x3.id', 'LEFT');
        $builder->join('course_classes x5', 'x1.course_class_id = x5.id', 'LEFT');
        $builder->join('subjects x4', 'x5.subject_id = x4.id', 'LEFT');
        if (!empty($keyword)) {
            $builder->groupStart();
            $builder->like('x3.nis', $keyword);
            $builder->orLike('x3.full_name', $keyword);
            $builder->orLike('x4.subject_name', $keyword);
            $builder->groupEnd();
        }
        if ($returnType === 'count') return $builder->countAllResults();
        if ($limit > 0) $builder->limit($limit, $offset);
        return $builder->get();
    }

    public function getStudentScores(int $studentId, int $academicYearId, string $semester)
    {
        return $this->db->table($this->table . ' x1')
            ->select("
                x1.*, x4.subject_name
            ")
            ->join('course_classes x5', 'x1.course_class_id = x5.id', 'LEFT')
            ->join('subjects x4', 'x5.subject_id = x4.id', 'LEFT')
            ->where('x1.student_id', $studentId)
            ->where('x1.academic_year_id', $academicYearId)
            ->where('x1.semester', $semester)
            ->where('x1.is_deleted', 'false')
            ->get();
    }
}
