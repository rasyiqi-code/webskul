<?php

namespace App\Models;

use App\Models\BaseModel;

class M_course_classes extends BaseModel
{
    protected $table = 'course_classes';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'academic_year_id', 'semester', 'class_group_id',
        'subject_id', 'employee_id', 'is_deleted'
    ];

    public function getSubjects(bool $copyData, int $academicYearId, string $semester, int $classGroupId)
    {
        $data = [];
        if ($copyData) {
            $query = $this->db->table($this->table . ' x1')
                ->select('x2.id, x2.subject_name')
                ->join('subjects x2', 'x1.subject_id = x2.id', 'LEFT')
                ->where('x1.academic_year_id', _toInteger($academicYearId))
                ->where('x1.semester', $semester)
                ->where('x1.class_group_id', _toInteger($classGroupId))
                ->where('x1.is_deleted', 'false')
                ->where('x2.is_deleted', 'false')
                ->get();
        } else {
            $query = $this->db->table('subjects')
                ->select('id, subject_name')
                ->where('is_deleted', 'false')
                ->get();
        }
        foreach ($query->getResult() as $row) {
            $data[] = [
                'id' => $row->id,
                'subject_name' => $row->subject_name
            ];
        }
        return $data;
    }

    public function getCourseClasses(int $academicYearId, string $semester, int $classGroupId)
    {
        $query = $this->db->table($this->table . ' x1')
            ->select("x1.id, x2.subject_name, COALESCE(x3.full_name, '[unset]') AS full_name, x1.is_deleted")
            ->join('subjects x2', 'x1.subject_id = x2.id', 'LEFT')
            ->join('users x3', 'x1.employee_id = x3.id', 'LEFT')
            ->where('x1.academic_year_id', _toInteger($academicYearId))
            ->where('x1.semester', $semester)
            ->where('x1.class_group_id', _toInteger($classGroupId))
            ->get();
        $data = [];
        foreach ($query->getResult() as $row) {
            $data[] = [
                'id' => $row->id,
                'subject_name' => $row->subject_name,
                'full_name' => $row->full_name,
                'is_deleted' => $row->is_deleted
            ];
        }
        return $data;
    }

    public function saveSubjects(array $ids, int $academicYearId, string $semester, int $classGroupId)
    {
        foreach ($ids as $subjectId) {
            $count = $this->db->table($this->table)
                ->where('academic_year_id', _toInteger($academicYearId))
                ->where('semester', $semester)
                ->where('class_group_id', _toInteger($classGroupId))
                ->where('subject_id', _toInteger($subjectId))
                ->countAllResults();
            if ($count === 0) {
                $builder = $this->db->table($this->table);
                $builder->set('academic_year_id', _toInteger($academicYearId));
                $builder->set('semester', $semester);
                $builder->set('class_group_id', _toInteger($classGroupId));
                $builder->set('subject_id', _toInteger($subjectId));
                $builder->set('created_by', __session('user_id'));
                $queryString = $builder->getCompiledInsert();
                $queryString = str_replace('INSERT INTO', 'INSERT IGNORE INTO', $queryString);
                $this->db->query($queryString);
            }
        }
        return true;
    }

    public function changeDeletedStatus(array $courseClassIds, int $academicYearId, string $semester, int $classGroupId, string $isDeleted = 'true')
    {
        $success = false;
        foreach ($courseClassIds as $courseClassId) {
            $subject = $this->db->table('course_classes')
                ->where('id', $courseClassId)
                ->get()
                ->getRow();
            if (!$subject) continue;
            $subjectId = $subject->subject_id;
            $classMeeting = $this->db->table('class_meetings')
                ->select('id')
                ->where('course_class_id', _toInteger($courseClassId))
                ->get()
                ->getRow();
            $classMeetingId = $classMeeting ? $classMeeting->id : 0;
            $this->db->table($this->table)
                ->set('is_deleted', $isDeleted)
                ->where('academic_year_id', _toInteger($academicYearId))
                ->where('semester', $semester)
                ->where('class_group_id', _toInteger($classGroupId))
                ->where('subject_id', _toInteger($subjectId))
                ->update();
            if ($courseClassId > 0) {
                $success = true;
                $this->db->table('class_meetings')
                    ->set('is_deleted', $isDeleted)
                    ->where('course_class_id', _toInteger($courseClassId))
                    ->update();
                if ($classMeetingId > 0) {
                    $this->db->table('meeting_attendences')
                        ->set('is_deleted', $isDeleted)
                        ->where('class_meeting_id', _toInteger($classMeetingId))
                        ->update();
                }
            }
        }
        return $success;
    }

    public function getCourseClassesById(int $id)
    {
        return $this->db->table($this->table . ' x1')
            ->select("
                x1.id
                , x2.academic_year
                , IF(x1.semester = 'odd', 'Ganjil', 'Genap') AS semester
                , CONCAT(x3.class_group, IF((x4.major_short_name <> ''), CONCAT(' ',x4.major_short_name),''),IF((x3.sub_class_group <> ''),CONCAT(' - ',x3.sub_class_group),'')) class_group
                , x5.subject_name
                , x6.full_name
            ")
            ->join('academic_years x2', 'x1.academic_year_id = x2.id', 'LEFT')
            ->join('class_groups x3', 'x1.class_group_id = x3.id', 'LEFT')
            ->join('majors x4', 'x3.major_id = x4.id', 'LEFT')
            ->join('subjects x5', 'x1.subject_id = x5.id', 'LEFT')
            ->join('users x6', 'x1.employee_id = x6.id', 'LEFT')
            ->where('x1.id', $id)
            ->get()
            ->getRow();
    }

    public function findId(int $academicYearId, string $semester, int $classGroupId, int $subjectId, int $employeeId)
    {
        $row = $this->db->table('course_classes')
            ->select('id')
            ->where('academic_year_id', _toInteger($academicYearId))
            ->where('semester', $semester)
            ->where('class_group_id', _toInteger($classGroupId))
            ->where('subject_id', (int)$subjectId)
            ->where('employee_id', (int)$employeeId)
            ->get()
            ->getRow();
        return $row ? (int)$row->id : 0;
    }
}
