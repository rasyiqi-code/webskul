<?php

namespace App\Models;

use App\Models\BaseModel;

class M_employees_data extends BaseModel
{
    protected $table = 'employees_data';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'user_id', 'nip', 'front_title', 'back_title', 'birth_place',
        'birth_date', 'gender', 'religion_id', 'marital_status',
        'phone', 'address', 'employment_status', 'last_education',
        'graduation_year', 'position', 'is_deleted'
    ];

    public function getWhere(string $keyword = '', string $returnType = 'count', int $limit = 0, int $offset = 0)
    {
        $builder = $this->db->table($this->table . ' x1');
        $builder->select("
            x1.id, x2.nik, x2.full_name, x1.nip, x1.front_title,
            x1.back_title, x1.birth_place, x1.birth_date,
            x1.phone, x1.position, x1.is_deleted
        ");
        $builder->join('users x2', 'x1.user_id = x2.id', 'LEFT');
        $builder->where('x2.is_employee', 'true');
        if (!empty($keyword)) {
            $builder->groupStart();
            $builder->like('x2.nik', $keyword);
            $builder->orLike('x2.full_name', $keyword);
            $builder->orLike('x1.nip', $keyword);
            $builder->orLike('x1.position', $keyword);
            $builder->groupEnd();
        }
        if ($returnType === 'count') return $builder->countAllResults();
        if ($limit > 0) $builder->limit($limit, $offset);
        return $builder->get();
    }

    public function dropdown()
    {
        $query = $this->db->table($this->table . ' x1')
            ->select('x1.id, x2.full_name, x1.nip')
            ->join('users x2', 'x1.user_id = x2.id', 'LEFT')
            ->where('x1.is_deleted', 'false')
            ->orderBy('x2.full_name', 'ASC')
            ->get();
        $data = [];
        foreach ($query->getResult() as $row) {
            $data[$row->id] = ($row->nip ? $row->nip . ' - ' : '') . $row->full_name;
        }
        return $data;
    }

    public function getByUserId(int $userId)
    {
        return $this->db->table($this->table)
            ->where('user_id', $userId)
            ->where('is_deleted', 'false')
            ->get()
            ->getRow();
    }
}
