<?php

namespace App\Models;

use App\Models\BaseModel;

class M_admission_subject_settings extends BaseModel
{
    protected $table = 'admission_subject_settings';
    protected $primaryKey = 'id';

    public function getWhere(string $keyword = '', string $subjectType = 'semester_report', string $returnType = 'count', int $limit = 0, int $offset = 0)
    {
        $fields = ['x1.id', 'x2.academic_year', 'x3.option_name AS admission_type', 'x1.is_deleted'];
        $majorCount = (int) session('major_count');
        if ($majorCount > 0) {
            $fields[] = "COALESCE(x4.major_name, '-') AS major_name";
        }
        $builder = $this->db->table($this->table . ' x1');
        $builder->select(implode(', ', $fields));
        $builder->join('academic_years x2', 'x1.academic_year_id = x2.id', 'LEFT');
        $builder->join('options x3', 'x1.admission_type_id = x3.id', 'LEFT');
        if ($majorCount > 0) {
            $builder->join('majors x4', 'x1.major_id = x4.id', 'LEFT');
        }
        $builder->where('x1.subject_type', $subjectType);
        if (!empty($keyword)) {
            $builder->groupStart();
            $builder->like('x2.academic_year', $keyword);
            $builder->orLike('x3.option_name', $keyword);
            if ($majorCount > 0) {
                $builder->orLike('x4.major_name', $keyword);
            }
            $builder->groupEnd();
        }
        if ($returnType === 'count') return $builder->countAllResults();
        if ($limit > 0) $builder->limit($limit, $offset);
        return $builder->get();
    }

    public function getSubjectSettings(int $admissionTypeId, int $majorId = 0, string $subjectType = 'semester_report', string $visibility = '')
    {
        $data = [];
        $query = $this->db->table($this->table)
            ->select('id')
            ->where('academic_year_id', session('admission_semester_id'))
            ->where('admission_type_id', $admissionTypeId)
            ->where('major_id', $majorId)
            ->where('subject_type', $subjectType)
            ->where('is_deleted', 'false')
            ->limit(1)
            ->get();
        if ($query->getNumRows() === 1) {
            $res = $query->getRow();
            $subjects = $this->db->table('admission_subject_setting_details x1')
                ->select('x1.id, x2.subject_name')
                ->join('subjects x2', 'x1.subject_id = x2.id', 'LEFT')
                ->where('x1.subject_setting_id', $res->id)
                ->where('x1.is_deleted', 'false');
            if ($visibility === 'public') {
                $subjects->where('x1.visibility', 'public');
            }
            foreach ($subjects->get()->getResult() as $subject) {
                $data[] = [
                    'subject_setting_detail_id' => $subject->id,
                    'subject_name' => $subject->subject_name,
                ];
            }
        }
        return $data;
    }
}
