<?php

namespace App\Models;

use App\Models\BaseModel;

class M_subject_teachers extends BaseModel
{
    protected $table = 'course_classes';
    protected $primaryKey = 'id';

    public function getSubjects(int $academicYearId, string $semester, int $classGroupId)
    {
        $query = $this->db->table($this->table . ' x1')
            ->select('x1.id, x2.subject_name, x1.employee_id')
            ->join('subjects x2', 'x1.subject_id = x2.id', 'LEFT')
            ->where('x1.academic_year_id', $academicYearId)
            ->where('x1.semester', $semester)
            ->where('x1.class_group_id', $classGroupId)
            ->get();
        $data = [];
        foreach ($query->getResult() as $row) {
            $data[] = [
                'id' => $row->id,
                'subject_name' => $row->subject_name,
                'employee_id' => $row->employee_id
            ];
        }
        return $data;
    }

    public function save(array $courseClasses)
    {
        $counter = 0;
        foreach ($courseClasses as $row) {
            $dataset = [
                'updated_by' => __session('user_id'),
                'employee_id' => $row['employee_id']
            ];
            $query = $this->db->table($this->table)
                ->where('id', $row['id'])
                ->update($dataset);
            if ($query) $counter++;
        }
        return $counter > 0;
    }
}
