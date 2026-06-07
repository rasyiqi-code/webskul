<?php

namespace App\Models;

use CodeIgniter\Model;

class M_exam_schedule_details extends Model
{
    protected $table = 'admission_exam_schedules';
    protected $primaryKey = 'id';

    public function getWhere(string $keyword = '', int $subjectSettingDetailId = 0, string $returnType = 'count', int $limit = 0, int $offset = 0)
    {
        $builder = $this->db->table($this->table . ' x1');
        $builder->select('x1.id, x2.room_name, x2.room_capacity, x3.option_name AS building_name, x1.exam_date, x1.exam_start_time, x1.exam_end_time, x1.is_deleted');
        $builder->join('rooms x2', 'x1.room_id = x2.id', 'LEFT');
        $builder->join('options x3', 'x2.building_id = x3.id', 'LEFT');
        $builder->where('x1.subject_setting_detail_id', $subjectSettingDetailId);
        if (!empty($keyword)) {
            $builder->groupStart();
            $builder->like('x1.exam_date', $keyword);
            $builder->orLike('x1.exam_start_time', $keyword);
            $builder->orLike('x1.exam_end_time', $keyword);
            $builder->orLike('x2.room_name', $keyword);
            $builder->orLike('x2.room_capacity', $keyword);
            $builder->orLike('x3.option_name', $keyword);
            $builder->groupEnd();
        }
        if ($returnType === 'count') return $builder->countAllResults();
        if ($limit > 0) $builder->limit($limit, $offset);
        return $builder->get();
    }

    public function getTitle(int $id)
    {
        $fields = [
            'x1.id', 'x1.exam_date', 'x1.exam_start_time', 'x1.exam_end_time',
            'x2.id AS room_id', 'x2.room_name', 'x2.room_capacity',
            'x3.id AS building_id', 'x3.option_name AS building_name',
            'x5.id AS subject_id', 'x5.subject_name',
            'x7.id AS academic_year_id', 'x7.academic_year',
            'x8.id AS admission_type_id', 'x8.option_name AS admission_type'
        ];
        if (__session('major_count') > 0) {
            $fields[] = 'x9.id AS major_id';
            $fields[] = "COALESCE(x9.major_name, '-') AS major_name";
        }
        $builder = $this->db->table('admission_exam_schedules x1');
        $builder->select(implode(', ', $fields));
        $builder->join('rooms x2', 'x1.room_id = x2.id');
        $builder->join('options x3', 'x2.building_id = x3.id');
        $builder->join('admission_subject_setting_details x4', 'x1.subject_setting_detail_id = x4.id');
        $builder->join('subjects x5', 'x4.subject_id = x5.id');
        $builder->join('admission_subject_settings x6', 'x4.subject_setting_id = x6.id');
        $builder->join('academic_years x7', 'x6.academic_year_id = x7.id');
        $builder->join('options x8', 'x6.admission_type_id = x8.id');
        if (__session('major_count') > 0) {
            $builder->join('majors x9', 'x6.major_id = x9.id', 'LEFT');
        }
        $builder->where('x1.id', $id);
        return $builder->get()->getRow();
    }

    public function getStudentExamSchedules(int $studentId = 0)
    {
        return $this->db->table('admission_exam_attendances x1')
            ->select('x2.exam_date, x2.exam_start_time, x2.exam_end_time, x6.subject_name, x4.option_name AS building_name, x3.room_name')
            ->join('admission_exam_schedules x2', 'x1.exam_schedule_id = x2.id', 'LEFT')
            ->join('rooms x3', 'x2.room_id = x3.id', 'LEFT')
            ->join('options x4', 'x3.building_id = x4.id', 'LEFT')
            ->join('admission_subject_setting_details x5', 'x2.subject_setting_detail_id = x5.id', 'LEFT')
            ->join('subjects x6', 'x5.subject_id = x6.id', 'LEFT')
            ->where('x1.student_id', _toInteger($studentId))
            ->where('x1.is_deleted', 'false')
            ->where('x2.is_deleted', 'false')
            ->where('x3.is_deleted', 'false')
            ->where('x4.is_deleted', 'false')
            ->where('x5.is_deleted', 'false')
            ->where('x6.is_deleted', 'false')
            ->orderBy('x2.exam_date', 'ASC')
            ->orderBy('x2.exam_start_time', 'ASC')
            ->get();
    }
}
