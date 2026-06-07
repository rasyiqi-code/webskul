<?php

namespace App\Models;

use CodeIgniter\Model;

class M_verification_subject_scores extends Model
{
    protected $table = 'admission_subject_scores';
    protected $primaryKey = 'id';

    public function getWhere(string $keyword = '', string $returnType = 'count', int $limit = 0, int $offset = 0)
    {
        $builder = $this->db->table($this->table . ' x1');
        $builder->select('
            x1.id
            , x5.registration_number
            , LEFT(x5.created_at, 10) AS created_at
            , x5.full_name
            , x3.subject_name
            , x4.subject_type
            , x1.score
            , x1.is_deleted
        ');
        $builder->join('admission_subject_setting_details x2', 'x1.subject_setting_detail_id = x2.id', 'LEFT');
        $builder->join('subjects x3', 'x2.subject_id = x3.id', 'LEFT');
        $builder->join('admission_subject_settings x4', 'x2.subject_setting_id = x4.id', 'LEFT');
        $builder->join('users x5', 'x1.student_id = x5.id', 'LEFT');
        if (!empty($keyword)) {
            $builder->like('x5.registration_number', $keyword);
            $builder->orLike('x5.created_at', $keyword);
            $builder->orLike('x5.full_name', $keyword);
            $builder->orLike('x3.subject_name', $keyword);
            $builder->orLike('x4.subject_type', $keyword);
            $builder->orLike('x1.score', $keyword);
        }
        $builder->orderBy('x5.registration_number', 'ASC');
        if ($returnType === 'count') return $builder->countAllResults();
        if ($limit > 0) $builder->limit($limit, $offset);
        return $builder->get();
    }

    public function getSubjectScores(string $registrationNumber, string $birthDate)
    {
        $builder = $this->db->table($this->table . ' x1');
        $builder->select('x3.subject_name, x4.subject_type, x1.score');
        $builder->join('admission_subject_setting_details x2', 'x1.subject_setting_detail_id = x2.id', 'LEFT');
        $builder->join('subjects x3', 'x2.subject_id = x3.id', 'LEFT');
        $builder->join('admission_subject_settings x4', 'x2.subject_setting_id = x4.id', 'LEFT');
        $builder->join('users x5', 'x1.student_id = x5.id', 'LEFT');
        $builder->where('x5.registration_number', $registrationNumber);
        $builder->where('x5.birth_date', $birthDate);
        $builder->where('x1.is_deleted', 'false');
        $builder->where('x2.is_deleted', 'false');
        $builder->where('x3.is_deleted', 'false');
        $builder->where('x4.is_deleted', 'false');
        $builder->where('x5.is_deleted', 'false');
        $query = $builder->get();
        $data = [];
        foreach ($query->getResult() as $row) {
            $data[] = [
                'subject_name' => $row->subject_name,
                'subject_type' => $row->subject_type,
                'score' => $row->score
            ];
        }
        return $data;
    }

    public function studentSubjectScores(int $studentId = 0)
    {
        return $this->db->table($this->table . ' x1')
            ->select('
                x1.id
                , x5.registration_number
                , LEFT(x5.created_at, 10) AS created_at
                , x5.full_name
                , x3.subject_name
                , x4.subject_type
                , x1.score
                , x1.is_deleted
            ')
            ->join('admission_subject_setting_details x2', 'x1.subject_setting_detail_id = x2.id', 'LEFT')
            ->join('subjects x3', 'x2.subject_id = x3.id', 'LEFT')
            ->join('admission_subject_settings x4', 'x2.subject_setting_id = x4.id', 'LEFT')
            ->join('users x5', 'x1.student_id = x5.id', 'LEFT')
            ->where('x5.id', $studentId)
            ->orderBy('x4.subject_type', 'DESC')
            ->get();
    }
}
