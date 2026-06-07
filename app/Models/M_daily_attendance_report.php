<?php

namespace App\Models;

use App\Models\BaseModel;

class M_daily_attendance_report extends BaseModel
{
    protected $table = 'meeting_attendences';
    protected $primaryKey = 'id';

    public function getDailyReport(int $academicYearId, int $classGroupId, string $semester, string $date)
    {
        return $this->db->table($this->table . ' x1')
            ->select("
                x4.nis, x4.full_name, x2.subject_name,
                x1.presence, x1.notes, x1.created_at
            ")
            ->join('class_meetings x2', 'x1.class_meeting_id = x2.id', 'LEFT')
            ->join('course_classes x3', 'x2.course_class_id = x3.id', 'LEFT')
            ->join('users x4', 'x1.student_id = x4.id', 'LEFT')
            ->where('x3.academic_year_id', $academicYearId)
            ->where('x3.semester', $semester)
            ->where('x3.class_group_id', $classGroupId)
            ->where('DATE(x1.created_at)', $date)
            ->where('x1.is_deleted', 'false')
            ->orderBy('x4.full_name', 'ASC')
            ->get();
    }

    public function getMonthlyReport(int $academicYearId, int $classGroupId, string $semester, string $month, string $year)
    {
        return $this->db->table($this->table . ' x1')
            ->select("
                x4.nis, x4.full_name, x2.subject_name,
                x1.presence, x1.notes, x1.created_at
            ")
            ->join('class_meetings x2', 'x1.class_meeting_id = x2.id', 'LEFT')
            ->join('course_classes x3', 'x2.course_class_id = x3.id', 'LEFT')
            ->join('users x4', 'x1.student_id = x4.id', 'LEFT')
            ->where('x3.academic_year_id', $academicYearId)
            ->where('x3.semester', $semester)
            ->where('x3.class_group_id', $classGroupId)
            ->where('MONTH(x1.created_at)', $month)
            ->where('YEAR(x1.created_at)', $year)
            ->where('x1.is_deleted', 'false')
            ->orderBy('x4.full_name', 'ASC')
            ->get();
    }
}
