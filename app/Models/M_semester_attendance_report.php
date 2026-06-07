<?php

namespace App\Models;

use CodeIgniter\Model;

class M_semester_attendance_report extends Model
{
    protected $table = 'meeting_attendences';
    protected $primaryKey = 'id';

    public function getReport(int $academicYearId, int $classGroupId, string $semester)
    {
        return $this->db->query("
            SELECT x4.nis, x4.full_name,
                SUM(CASE WHEN x1.presence = 'present' THEN 1 ELSE 0 END) AS present,
                SUM(CASE WHEN x1.presence = 'absent' THEN 1 ELSE 0 END) AS absent,
                SUM(CASE WHEN x1.presence = 'sick' THEN 1 ELSE 0 END) AS sick,
                SUM(CASE WHEN x1.presence = 'permit' THEN 1 ELSE 0 END) AS permit,
                COUNT(*) AS total
            FROM meeting_attendences x1
            LEFT JOIN class_meetings x2 ON x1.class_meeting_id = x2.id
            LEFT JOIN course_classes x3 ON x2.course_class_id = x3.id
            LEFT JOIN users x4 ON x1.student_id = x4.id
            WHERE x3.academic_year_id = ?
                AND x3.class_group_id = ?
                AND x3.semester = ?
                AND x1.is_deleted = 'false'
            GROUP BY x4.nis, x4.full_name
            ORDER BY x4.full_name ASC
        ", [$academicYearId, $classGroupId, $semester]);
    }
}
