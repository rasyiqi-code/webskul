<?php

namespace App\Models;

use CodeIgniter\Model;

class M_exam_attendances extends Model
{
    protected $table = 'admission_exam_attendances';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'student_id', 'exam_schedule_id', 'presence', 'is_deleted'
    ];

    public function getAttendanceLists(int $examScheduleId)
    {
        return $this->db->table($this->table . ' x1')
            ->select('x1.id, x2.registration_number, x2.full_name, x1.presence')
            ->join('users x2', 'x1.student_id = x2.id', 'LEFT')
            ->where('x1.exam_schedule_id', _toInteger($examScheduleId))
            ->where('x2.re_registration', 'true')
            ->where('x1.is_deleted', 'false')
            ->where('x2.is_deleted', 'false')
            ->orderBy('x2.registration_number', 'ASC')
            ->get();
    }

    public function saveAttendanceLists(array $ids, int $examScheduleId)
    {
        $counter = 0;
        foreach ($ids as $id) {
            $query = $this->db->table($this->table)->insert([
                'student_id' => _toInteger($id),
                'exam_schedule_id' => _toInteger($examScheduleId),
                'created_by' => (int)__session('user_id')
            ]);
            if ($query) $counter++;
        }
        return _isNaturalNumber($counter);
    }

    public function deleteAttendanceLists(array $ids)
    {
        return $this->db->table($this->table)
            ->whereIn('id', $ids)
            ->delete();
    }

    public function saveAttendance(array $presences)
    {
        $count = 0;
        foreach ($presences as $row) {
            $query = $this->db->table($this->table)
                ->where('id', _toInteger($row['id']))
                ->update([
                    'updated_by' => (int)__session('user_id'),
                    'presence' => $row['presence']
                ]);
            if ($query) $count++;
        }
        return _isNaturalNumber($count);
    }

    public function getProspectiveStudents(int $subjectSettingDetailId)
    {
        $examScheduleIds = $this->db->table('admission_exam_schedules')
            ->select('id')
            ->where('subject_setting_detail_id', _toInteger($subjectSettingDetailId))
            ->where('is_deleted', 'false')
            ->getCompiledSelect();

        $studentIds = $this->db->table($this->table)
            ->select('student_id')
            ->where('exam_schedule_id IN (' . $examScheduleIds . ')', null, false)
            ->where('is_deleted', 'false')
            ->getCompiledSelect();

        return $this->db->table('admission_subject_scores x1')
            ->select('x1.student_id, x4.registration_number, x4.full_name')
            ->join('admission_subject_setting_details x2', 'x1.subject_setting_detail_id = x2.id', 'LEFT')
            ->join('admission_subject_settings x3', 'x2.subject_setting_id = x3.id', 'LEFT')
            ->join('users x4', 'x1.student_id = x4.id', 'LEFT')
            ->where('x1.is_deleted', 'false')
            ->where('x2.is_deleted', 'false')
            ->where('x3.is_deleted', 'false')
            ->where('x3.subject_type', 'exam_schedule')
            ->where('x4.re_registration', 'true')
            ->where('x1.student_id NOT IN ( ' . $studentIds . ')', null, false)
            ->groupBy(['x1.student_id', 'x4.registration_number', 'x4.full_name'])
            ->get();
    }
}
