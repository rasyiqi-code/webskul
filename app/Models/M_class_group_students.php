<?php

namespace App\Models;

use CodeIgniter\Model;

class M_class_group_students extends Model
{
    protected $table = 'class_group_students';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'student_id', 'class_group_setting_id', 'is_class_manager', 'is_deleted'
    ];

    public function getWhere(string $keyword = '', string $returnType = 'count', int $limit = 0, int $offset = 0)
    {
        $builder = $this->db->table($this->table . ' x1');
        $builder->select("
            x1.id
            , x2.academic_year
            , CONCAT(x3.class_group, IF((x4.major_short_name <> ''), CONCAT(' ',x4.major_short_name),''),IF((x3.sub_class_group <> ''),CONCAT(' - ',x3.sub_class_group),'')) AS class_group
            , COALESCE(x5.nis, '') AS nis
            , COALESCE(x5.nisn, '') AS nisn
            , x5.full_name
            , x5.gender
            , COALESCE(x5.birth_place, '') AS birth_place
            , COALESCE(x5.birth_date, '') AS birth_date
            , x1.is_deleted
        ");
        $builder->join('academic_years x2', 'x1.academic_year_id = x2.id', 'LEFT');
        $builder->join('class_groups x3', 'x1.class_group_id = x3.id', 'LEFT');
        $builder->join('majors x4', 'x3.major_id = x4.id', 'LEFT');
        $builder->join('users x5', 'x1.student_id = x5.id', 'LEFT');
        $builder->where('x5.is_student', 'true');
        $builder->where('x5.is_alumni', 'false');
        if (!empty($keyword)) {
            $builder->groupStart();
            $builder->like('x2.academic_year', $keyword);
            $builder->orLike("CONCAT(x3.class_group, IF((x4.major_short_name <> ''), CONCAT(' ',x4.major_short_name),''),IF((x3.sub_class_group <> ''),CONCAT(' - ',x3.sub_class_group),''))", $keyword);
            $builder->orLike('x5.nis', $keyword);
            $builder->orLike('x5.nisn', $keyword);
            $builder->orLike('x5.full_name', $keyword);
            $builder->orLike('x5.gender', $keyword);
            $builder->orLike('x5.birth_place', $keyword);
            $builder->orLike('x5.birth_date', $keyword);
            $builder->groupEnd();
        }
        if ($returnType === 'count') return $builder->countAllResults();
        if ($limit > 0) $builder->limit($limit, $offset);
        return $builder->get();
    }

    public function getStudents(int $academicYearId, $classGroupId)
    {
        $studentStatusId = get_option_id('student_status', 'aktif');
        $data = [];

        if ($classGroupId === 'unset') {
            $studentIds = $this->db->table('class_group_students')
                ->select('student_id')
                ->groupBy('student_id')
                ->getCompiledSelect();
            $query = $this->db->table('users')
                ->select('id, nis, full_name')
                ->where('id NOT IN(' . $studentIds . ')', null, false)
                ->where('is_student', 'true')
                ->where('is_alumni', 'false')
                ->where('student_status_id', _toInteger($studentStatusId))
                ->get();
        } elseif ($classGroupId === 'show_all') {
            $query = $this->db->table('users')
                ->select('id, nis, full_name')
                ->where('is_student', 'true')
                ->where('is_alumni', 'false')
                ->where('student_status_id', _toInteger($studentStatusId))
                ->get();
        } else {
            $classGroupSetting = $this->db->table('class_group_settings')
                ->select('id')
                ->where('academic_year_id', $academicYearId)
                ->where('class_group_id', $classGroupId)
                ->get()
                ->getRow();
            if ($classGroupSetting) {
                $classGroupSettingId = $classGroupSetting->id;
                $query = $this->db->table($this->table . ' x1')
                    ->select('x2.id, x2.nis, x2.full_name')
                    ->join('users x2', 'x1.student_id = x2.id', 'LEFT')
                    ->where('x1.class_group_setting_id', $classGroupSettingId)
                    ->where('x1.is_deleted', 'false')
                    ->where('x2.is_deleted', 'false')
                    ->where('x2.is_alumni', 'false')
                    ->where('x2.is_student', 'true')
                    ->get();
            }
        }

        if (isset($query)) {
            foreach ($query->getResult() as $row) {
                $data[] = [
                    'id' => $row->id,
                    'nis' => $row->nis,
                    'full_name' => $row->full_name
                ];
            }
        }
        return $data;
    }

    public function setClassGroupStudents(array $studentIds, int $academicYearId, int $classGroupId)
    {
        $studentStatusId = get_option_id('student_status', 'aktif');
        $classGroupSettingId = 0;
        $query = $this->db->table('class_group_settings')
            ->select('id')
            ->where('academic_year_id', $academicYearId)
            ->where('class_group_id', $classGroupId)
            ->get()
            ->getRow();
        if ($query) $classGroupSettingId = $query->id;
        $success = 0;
        if ($classGroupSettingId > 0) {
            foreach ($studentIds as $studentId) {
                $inserted = $this->db->table($this->table)->insert([
                    'student_id' => $studentId,
                    'class_group_setting_id' => $classGroupSettingId,
                    'created_by' => (int)__session('user_id')
                ]);
                if ($inserted) {
                    $this->db->table('users')
                        ->where('id', $studentId)
                        ->update(['student_status_id' => $studentStatusId]);
                    $success++;
                }
            }
        }
        return $success > 0;
    }

    public function deletePermanently(array $ids, int $academicYearId, int $classGroupId)
    {
        $classGroupSettingId = 0;
        $query = $this->db->table('class_group_settings')
            ->select('id')
            ->where('academic_year_id', $academicYearId)
            ->where('class_group_id', $classGroupId)
            ->get()
            ->getRow();
        if ($query) $classGroupSettingId = $query->id;
        return $this->db->table($this->table)
            ->where('class_group_setting_id', $classGroupSettingId)
            ->whereIn('student_id', $ids)
            ->delete();
    }
}
