<?php

namespace App\Models;

use CodeIgniter\Model;

class M_employees extends Model
{
    protected $table = 'users';
    protected $primaryKey = 'id';

    public function getWhere(string $keyword = '', string $returnType = 'count', int $limit = 0, int $offset = 0)
    {
        $builder = $this->db->table($this->table . ' x1');
        $builder->select("
            x1.*
            , x2.option_name AS employment_type
            , IF(x1.gender = 'M', 'L', 'P') AS gender
            , COALESCE(x1.birth_place, '') birth_place
        ");
        $builder->join('options x2', 'x1.employment_type_id = x2.id', 'LEFT');
        $builder->where('x1.is_employee', 'true');
        if (!empty($keyword)) {
            $builder->groupStart();
            $builder->like('x1.nik', $keyword);
            $builder->orLike('x1.full_name', $keyword);
            $builder->orLike('x1.gender', $keyword);
            $builder->orLike('x1.birth_place', $keyword);
            $builder->orLike('x1.birth_date', $keyword);
            $builder->orLike('x1.email', $keyword);
            $builder->orLike('x2.option_name', $keyword);
            $builder->groupEnd();
        }
        if ($returnType === 'count') return $builder->countAllResults();
        if ($limit > 0) $builder->limit($limit, $offset);
        return $builder->get();
    }

    public function dropdown()
    {
        $query = $this->db->table($this->table)
            ->select('id, nik, full_name')
            ->where('is_deleted', 'false')
            ->orderBy('full_name', 'ASC')
            ->get();
        $data = [];
        foreach ($query->getResult() as $row) {
            $data[$row->id] = $row->nik . ' - ' . $row->full_name;
        }
        return $data;
    }

    public function employeeQuery(): string
    {
        return "
        SELECT x1.*
            , IF(x1.gender = 'M', 'L', 'P') AS gender
            , x2.option_name AS religion
            , x3.option_name AS employment_type
        FROM users x1
        LEFT JOIN options x2 ON x1.religion_id = x2.id
        LEFT JOIN options x3 ON x1.employment_type_id = x3.id
        WHERE is_employee = 'true'";
    }

    public function employeeReports()
    {
        $query = $this->employeeQuery();
        $query .= " ORDER BY x1.full_name ASC";
        return $this->db->query($query);
    }

    public function profile(int $id)
    {
        $query = $this->employeeQuery() . ' AND x1.id = ?';
        return $this->db->query($query, [_toInteger($id)])->getRow();
    }

    public function getEmployees(int $limit = 0, int $offset = 0)
    {
        $builder = $this->db->table($this->table . ' x1');
        $builder->select("
            x1.*
            , IF(x1.gender = 'M', 'Laki-laki', 'Perempuan') as gender
            , x2.option_name AS employment_type
        ");
        $builder->join('options x2', 'x1.employment_type_id = x2.id', 'LEFT');
        $builder->where('x1.is_deleted', 'false');
        $builder->where('x1.is_employee', 'true');
        $builder->orderBy('x1.full_name', 'ASC');
        if ($limit > 0) $builder->limit($limit, $offset);
        return $builder->get();
    }
}
