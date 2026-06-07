<?php

namespace App\Models;

use App\Models\BaseModel;

class M_alumni extends BaseModel
{
    protected $table = 'users';
    protected $primaryKey = 'id';

    public function getWhere(string $keyword = '', string $returnType = 'count', int $limit = 0, int $offset = 0)
    {
        $builder = $this->db->table($this->table);
        $builder->select("
            *
            , COALESCE(LEFT(start_date, 4), '-') year_in
            , COALESCE(LEFT(end_date, 4), '-') year_out
        ");
        $builder->whereIn('is_alumni', ['true', 'unverified']);
        if (!empty($keyword)) {
            $builder->groupStart();
            $builder->like('nis', $keyword);
            $builder->orLike('full_name', $keyword);
            $builder->orLike('gender', $keyword);
            $builder->orLike('street_address', $keyword);
            $builder->orLike('start_date', $keyword);
            $builder->orLike('end_date', $keyword);
            $builder->groupEnd();
        }
        if ($returnType === 'count') return $builder->countAllResults();
        if ($limit > 0) $builder->limit($limit, $offset);
        return $builder->get();
    }

    public function getAlumni(int $limit = 0, int $offset = 0)
    {
        $builder = $this->db->table($this->table);
        $builder->select("
            *
            , IF(gender = 'M', 'Laki-laki', 'Perempuan') AS gender
            , COALESCE(LEFT(start_date, 4), '-') AS year_in
            , COALESCE(LEFT(end_date, 4), '-') AS year_out
        ");
        $builder->where('is_deleted', 'false');
        $builder->where('is_alumni', 'true');
        $builder->orderBy('end_date', 'ASC');
        $builder->orderBy('full_name', 'ASC');
        if ($limit > 0) $builder->limit($limit, $offset);
        return $builder->get();
    }

    public function alumniReports()
    {
        $mStudents = new M_students();
        $query = $mStudents->studentQuery();
        $query .= " AND x1.is_alumni='true' ORDER BY x1.full_name ASC";
        return $this->db->query($query);
    }
}
