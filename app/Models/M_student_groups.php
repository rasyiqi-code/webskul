<?php

namespace App\Models;

use CodeIgniter\Model;

class M_student_groups extends Model
{
    protected $table = 'class_group_students';
    protected $primaryKey = 'id';

    public function getWhere(string $keyword = '', string $returnType = 'count', int $limit = 0, int $offset = 0)
    {
        $builder = $this->db->table($this->table . ' x1');
        $builder->select("
            x1.id
            , x4.academic_year
            , CONCAT(x5.class_group, IF((x6.major_short_name <> ''), CONCAT(' ', x6.major_short_name),''), IF((x5.sub_class_group <> ''),CONCAT(' - ', x5.sub_class_group),'')) AS class_name
            , COALESCE(x2.nis, '-') AS nis
            , x2.full_name
            , x2.birth_place
            , x2.birth_date
            , IF(x2.gender = 'M', 'L', 'P') AS gender
            , x1.is_class_manager
            , x1.is_deleted
        ");
        $builder->join('users x2', 'x1.student_id = x2.id', 'LEFT');
        $builder->join('class_group_settings x3', 'x1.class_group_setting_id = x3.id', 'LEFT');
        $builder->join('academic_years x4', 'x3.academic_year_id = x4.id', 'LEFT');
        $builder->join('class_groups x5', 'x3.class_group_id = x5.id', 'LEFT');
        $builder->join('majors x6', 'x5.major_id = x6.id', 'LEFT');
        $builder->where('x2.is_student', 'true');
        $builder->where('x2.is_alumni', 'false');
        $builder->where('x2.is_prospective_student', 'false');
        if (!empty($keyword)) {
            $builder->like('x4.academic_year', $keyword);
            $builder->orLike('x2.nis', $keyword);
            $builder->orLike('x2.full_name', $keyword);
            $builder->orLike('x2.birth_place', $keyword);
            $builder->orLike('x2.birth_date', $keyword);
            $builder->orLike('x2.gender', $keyword);
            $builder->orLike("CONCAT(x5.class_group, IF((x6.major_short_name <> ''), CONCAT(' ', x6.major_short_name),''), IF((x5.sub_class_group <> ''), CONCAT(' - ', x5.sub_class_group),''))", $keyword);
        }
        $builder->orderBy('x4.academic_year', 'ASC');
        $builder->orderBy('x5.class_group', 'ASC');
        $builder->orderBy('x5.major_id', 'ASC');
        $builder->orderBy('x5.sub_class_group', 'ASC');
        $builder->orderBy('x2.full_name', 'ASC');
        if ($returnType === 'count') return $builder->countAllResults();
        if ($limit > 0) $builder->limit($limit, $offset);
        return $builder->get();
    }

    public function setClassPresident(int $id, array $dataset)
    {
        $classGroupSettingId = 0;
        $query = $this->db->table('class_group_students')
            ->where('id', $id)
            ->get()
            ->getRow();
        if ($query) $classGroupSettingId = $query->class_group_setting_id;
        if ($classGroupSettingId > 0) {
            $this->db->transBegin();
            if (filter_var($dataset['is_class_manager'], FILTER_VALIDATE_BOOLEAN)) {
                $this->db->table($this->table)
                    ->where('class_group_setting_id', $classGroupSettingId)
                    ->update(['is_class_manager' => 'false']);
            }
            $this->db->table($this->table)
                ->where($this->primaryKey, $id)
                ->update($dataset);
            return $this->db->transStatus();
        }
        return false;
    }
}
