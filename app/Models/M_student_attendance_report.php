<?php

namespace App\Models;

use App\Models\BaseModel;

class M_student_attendance_report extends BaseModel
{
    protected $table = 'class_group_students';
    protected $primaryKey = 'id';

    public function getReport(int $academicYearId, int $classGroupId, string $semester, int $studentId)
    {
        return $this->db->query("
            SELECT x1.*, x2.subject_name,
                (SELECT COUNT(*) FROM class_meetings WHERE course_class_id = x3.id AND is_deleted = 'false') AS total_meetings,
                (SELECT COUNT(*) FROM meeting_attendences WHERE student_id = ? AND class_meeting_id IN (SELECT id FROM class_meetings WHERE course_class_id = x3.id) AND presence = 'present' AND is_deleted = 'false') AS total_present,
                (SELECT COUNT(*) FROM meeting_attendences WHERE student_id = ? AND class_meeting_id IN (SELECT id FROM class_meetings WHERE course_class_id = x3.id) AND presence = 'absent' AND is_deleted = 'false') AS total_absent,
                (SELECT COUNT(*) FROM meeting_attendences WHERE student_id = ? AND class_meeting_id IN (SELECT id FROM class_meetings WHERE course_class_id = x3.id) AND presence = 'sick' AND is_deleted = 'false') AS total_sick,
                (SELECT COUNT(*) FROM meeting_attendences WHERE student_id = ? AND class_meeting_id IN (SELECT id FROM class_meetings WHERE course_class_id = x3.id) AND presence = 'permit' AND is_deleted = 'false') AS total_permit
            FROM class_group_students x1
            LEFT JOIN course_classes x3 ON x3.class_group_id = x2.class_group_id AND x3.academic_year_id = ? AND x3.semester = ?
            LEFT JOIN subjects x2 ON x3.subject_id = x2.id
            WHERE x1.student_id = ? AND x1.class_group_setting_id IN (SELECT id FROM class_group_settings WHERE academic_year_id = ? AND class_group_id = ?)
            GROUP BY x2.subject_name
        ", [$studentId, $studentId, $studentId, $studentId, $academicYearId, $semester, $studentId, $academicYearId, $classGroupId]);
    }

    public function getDailyAttendance(int $academicYearId, int $classGroupId, string $date)
    {
        $classGroupSettingId = $this->db->table('class_group_settings')
            ->select('id')
            ->where('academic_year_id', $academicYearId)
            ->where('class_group_id', $classGroupId)
            ->get()
            ->getRow();
        $settingId = $classGroupSettingId ? $classGroupSettingId->id : 0;
        return $this->db->table('class_group_students x1')
            ->select("x3.id, x3.nis, x3.full_name, COALESCE(x4.presence, '-') AS presence")
            ->join('users x3', 'x1.student_id = x3.id', 'LEFT')
            ->join('meeting_attendences x4', 'x1.student_id = x4.student_id AND DATE(x4.created_at) = ?', 'LEFT')
            ->where('x1.class_group_setting_id', $settingId)
            ->where('x1.is_deleted', 'false')
            ->orderBy('x3.full_name', 'ASC')
            ->get();
    }
}
