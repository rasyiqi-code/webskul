<?php

namespace App\Models;

use App\Models\BaseModel;

class M_subject_attendance_report extends BaseModel
{
    protected $table = 'meeting_attendences';
    protected $primaryKey = 'id';

    public function getReport(int $courseClassId, string $startDate, string $endDate)
    {
        return $this->db->table($this->table . ' x1')
            ->select("
                x3.nis, x3.full_name,
                x1.presence, x1.notes,
                x2.meeting_date, x2.meeting_time
            ")
            ->join('class_meetings x2', 'x1.class_meeting_id = x2.id', 'LEFT')
            ->join('users x3', 'x1.student_id = x3.id', 'LEFT')
            ->where('x2.course_class_id', $courseClassId)
            ->where('x2.meeting_date >=', $startDate)
            ->where('x2.meeting_date <=', $endDate)
            ->where('x1.is_deleted', 'false')
            ->orderBy('x3.full_name', 'ASC')
            ->orderBy('x2.meeting_date', 'ASC')
            ->get();
    }
}
