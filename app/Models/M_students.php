<?php

namespace App\Models;

use CodeIgniter\Model;

class M_students extends Model
{
    protected $table = 'users';
    protected $primaryKey = 'id';

    public function getWhere(string $keyword = '', string $returnType = 'count', int $limit = 0, int $offset = 0)
    {
        $builder = $this->db->table($this->table . ' x1');
        $builder->select("
            x1.id
            , COALESCE(x1.nis, '') nis
            , x1.full_name
            , x2.option_name AS student_status
            , x1.gender
            , COALESCE(x1.birth_place, '') birth_place
            , x1.birth_date
            , x1.photo
            , x1.email
            , x1.is_deleted
        ");
        $builder->join('options x2', 'x1.student_status_id = x2.id', 'LEFT');
        $builder->where('x1.is_student', 'true');
        $builder->where('x1.is_alumni', 'false');
        if (!empty($keyword)) {
            $builder->groupStart();
            $builder->like('x1.nis', $keyword);
            $builder->orLike('x2.option_name', $keyword);
            $builder->orLike('x1.full_name', $keyword);
            $builder->orLike('x1.gender', $keyword);
            $builder->orLike('x1.birth_place', $keyword);
            $builder->orLike('x1.birth_date', $keyword);
            $builder->groupEnd();
        }
        if ($returnType === 'count') return $builder->countAllResults();
        if ($limit > 0) $builder->limit($limit, $offset);
        return $builder->get();
    }

    public function chartByStudentStatus(int $academicYearId)
    {
        $studentIds = $this->db->table('class_group_students x1')
            ->select('x1.student_id')
            ->join('class_group_settings x2', 'x1.class_group_setting_id = x2.id', 'LEFT')
            ->where('x2.academic_year_id', _toInteger($academicYearId))
            ->groupBy('x1.student_id')
            ->getCompiledSelect();

        $builder = $this->db->table('users x1');
        $builder->select('x2.option_name AS labels, COUNT(*) AS data');
        $builder->join('options x2', 'x1.student_status_id = x2.id', 'LEFT');
        $builder->where('x1.is_student', 'true');
        $builder->where('x1.is_alumni', 'false');
        $builder->where('x1.id IN (' . $studentIds . ')', null, false);
        $builder->groupBy('x2.option_name');
        $builder->orderBy('x2.option_name', 'ASC');
        return $builder->get();
    }

    public function chartByClassGroups(int $academicYearId)
    {
        return $this->db->query("
            SELECT CONCAT(x3.class_group, IF((x4.major_short_name <> ''), CONCAT(' ', x4.major_short_name),''),IF((x3.sub_class_group <> ''),CONCAT(' - ',x3.sub_class_group),'')) AS labels
                , COUNT(*) AS data
            FROM class_group_students x1
            LEFT JOIN class_group_settings x2 ON x1.class_group_setting_id = x2.id
            LEFT JOIN class_groups x3 ON x2.class_group_id = x3.id
            LEFT JOIN majors x4 ON x3.major_id = x4.id
            WHERE x2.academic_year_id = ?
            GROUP BY 1
        ", [_toInteger($academicYearId)]);
    }

    public function chartByEndDate()
    {
        return $this->db->query("
            SELECT LEFT(x1.end_date, 4) AS labels
                , COUNT(*) AS data
            FROM users x1
            WHERE x1.is_alumni='true'
            AND x1.is_deleted='false'
            GROUP BY 1
            ORDER BY 1 ASC
        ");
    }

    public function setAsAlumni(array $ids, string $endDate)
    {
        $studentStatusId = get_option_id('student_status', 'lulus');
        $dataset = [
            'is_alumni' => 'true',
            'end_date' => $endDate . '-05-01'
        ];
        if ($studentStatusId > 0) {
            $dataset['student_status_id'] = $studentStatusId;
        }
        return $this->db->table($this->table)
            ->whereIn('id', $ids)
            ->update($dataset);
    }

    public function studentQuery(): string
    {
        return "
            SELECT x1.*
                , x2.major_name
                , x3.major_name AS first_choice
                , x4.major_name AS second_choice
                , CASE WHEN x1.selection_result IS NULL THEN 'Belum Diseleksi'
                    WHEN x1.selection_result = 'approved' THEN 'Diterima'
                    WHEN x1.selection_result = 'unapproved' THEN 'Tidak Diterima'
                    WHEN x1.selection_result > 0 THEN (SELECT major_name FROM majors WHERE id = x1.selection_result)
                    ELSE '-'
                    END AS selection_result
                , x5.phase_name
                , x6.option_name AS admission_type
                , IF(x1.is_transfer = 'true', 'Pindahan', 'Baru') AS is_transfer
                , IF(x1.re_registration = 'true', 'Ya', 'Tidak') AS re_registration
                , IF(x1.gender = 'M', 'Laki-laki', 'Perempuan') AS gender
                , x7.option_name AS religion
                , x8.option_name AS special_need
                , x9.option_name AS residence
                , x10.option_name AS transportation
                , x11.option_name AS father_education
                , x12.option_name AS father_employment
                , x13.option_name AS father_monthly_income
                , x14.option_name AS father_special_need
                , x15.option_name AS mother_education
                , x16.option_name AS mother_employment
                , x17.option_name AS mother_monthly_income
                , x18.option_name AS mother_special_need
                , x19.option_name AS guardian_education
                , x20.option_name AS guardian_employment
                , x21.option_name AS guardian_monthly_income
                , x22.option_name AS student_status
                , x23.option_name AS student_employment
            FROM users x1
            LEFT JOIN majors x2 ON x1.major_id = x2.id
            LEFT JOIN majors x3 ON x1.first_choice_id = x3.id
            LEFT JOIN majors x4 ON x1.second_choice_id = x4.id
            LEFT JOIN admission_phases x5 ON x1.admission_phase_id = x5.id
            LEFT JOIN options x6 ON x1.admission_type_id = x6.id
            LEFT JOIN options x7 ON x1.religion_id = x7.id
            LEFT JOIN options x8 ON x1.special_need_id = x8.id
            LEFT JOIN options x9 ON x1.residence_id = x9.id
            LEFT JOIN options x10 ON x1.transportation_id = x10.id
            LEFT JOIN options x11 ON x1.father_education_id = x11.id
            LEFT JOIN options x12 ON x1.father_employment_id = x12.id
            LEFT JOIN options x13 ON x1.father_monthly_income_id = x13.id
            LEFT JOIN options x14 ON x1.father_special_need_id = x14.id
            LEFT JOIN options x15 ON x1.mother_education_id = x15.id
            LEFT JOIN options x16 ON x1.mother_employment_id = x16.id
            LEFT JOIN options x17 ON x1.mother_monthly_income_id = x17.id
            LEFT JOIN options x18 ON x1.mother_special_need_id = x18.id
            LEFT JOIN options x19 ON x1.guardian_education_id = x19.id
            LEFT JOIN options x20 ON x1.guardian_employment_id = x20.id
            LEFT JOIN options x21 ON x1.guardian_monthly_income_id = x21.id
            LEFT JOIN options x22 ON x1.student_status_id = x22.id
            LEFT JOIN options x23 ON x1.employment_id = x23.id
            WHERE (is_student = 'true' OR is_prospective_student = 'true' OR is_alumni = 'true')
        ";
    }

    public function studentReports()
    {
        $query = $this->studentQuery();
        $query .= " AND x1.is_student='true' AND x1.is_alumni='false' ORDER BY x1.nis, x1.full_name ASC";
        return $this->db->query($query);
    }

    public function profile(int $id)
    {
        $query = $this->studentQuery() . ' AND x1.id = ?';
        return $this->db->query($query, [_toInteger($id)])->getRow();
    }
}
