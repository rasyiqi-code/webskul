<?php

namespace App\Models;

use App\Models\BaseModel;

class M_academic_years extends BaseModel
{
    protected $table = 'academic_years';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'academic_year', 'semester', 'admission_semester',
        'current_semester', 'is_deleted'
    ];

    public function getWhere(string $keyword = '', string $returnType = 'count', int $limit = 0, int $offset = 0)
    {
        $builder = $this->db->table($this->table);
        if (!empty($keyword)) $builder->like('academic_year', $keyword);
        if ($returnType === 'count') return $builder->countAllResults();
        if ($limit > 0) $builder->limit($limit, $offset);
        return $builder->get();
    }

    public function getActiveAcademicYear()
    {
        $data = [];
        $admissionSemester = $this->db->table($this->table)
            ->select('id, academic_year')
            ->where('admission_semester', 'true')
            ->where('is_deleted', 'false')
            ->orderBy('academic_year', 'DESC')
            ->limit(1)
            ->get()
            ->getRow();
        if ($admissionSemester) {
            $data['admission_semester_id'] = $admissionSemester->id;
            $data['admission_semester'] = $admissionSemester->academic_year;
            $data['admission_year'] = substr($admissionSemester->academic_year, 0, 4);
        }
        $currentSemester = $this->db->table($this->table)
            ->select('id, academic_year, semester')
            ->where('current_semester', 'true')
            ->where('is_deleted', 'false')
            ->orderBy('academic_year', 'DESC')
            ->limit(1)
            ->get()
            ->getRow();
        if ($currentSemester) {
            $data['current_academic_year_id'] = $currentSemester->id;
            $data['current_academic_year'] = $currentSemester->academic_year;
            $data['current_academic_semester'] = $currentSemester->semester;
        }
        return $data;
    }

    public function dropdown(bool $short = false)
    {
        $query = $this->db->table($this->table)
            ->select('id, academic_year')
            ->where('is_deleted', 'false')
            ->orderBy('academic_year', 'ASC')
            ->get();
        $data = [];
        foreach ($query->getResult() as $row) {
            $data[$row->id] = $short ? substr($row->academic_year, 0, 4) : $row->academic_year;
        }
        return $data;
    }

    public function deactivate(int $id = 0, string $field = 'current_semester')
    {
        $builder = $this->db->table($this->table);
        if ($id > 0) $builder->where($this->primaryKey . ' !=', $id);
        return $builder->update([$field => 'false']);
    }
}
