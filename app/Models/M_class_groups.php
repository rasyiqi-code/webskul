<?php

namespace App\Models;

use App\Models\BaseModel;

class M_class_groups extends BaseModel
{
    protected $table = 'class_groups';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'class_group', 'major_id', 'sub_class_group', 'is_deleted'
    ];

    public function getWhere(string $keyword = '', string $returnType = 'count', int $limit = 0, int $offset = 0)
    {
        $builder = $this->db->table($this->table . ' x1');
        $builder->select("
            x1.id
            , CONCAT(x1.class_group, IF((x2.major_short_name <> ''), CONCAT(' ',x2.major_short_name),''),IF((x1.sub_class_group <> ''),CONCAT(' - ',x1.sub_class_group),'')) AS class_name
            , x1.is_deleted
        ");
        $builder->join('majors x2', 'x1.major_id = x2.id', 'LEFT');
        if (!empty($keyword)) {
            $builder->like("CONCAT(x1.class_group, IF((x2.major_short_name <> ''), CONCAT(' ',x2.major_short_name),''),IF((x1.sub_class_group <> ''),CONCAT(' - ',x1.sub_class_group),''))", $keyword);
        }
        $builder->orderBy('x1.class_group', 'ASC');
        $builder->orderBy('x1.major_id', 'ASC');
        $builder->orderBy('x1.sub_class_group', 'ASC');
        if ($returnType === 'count') return $builder->countAllResults();
        if ($limit > 0) $builder->limit($limit, $offset);
        return $builder->get();
    }

    public function dropdown()
    {
        $query = $this->db->table($this->table . ' x1')
            ->select("x1.id, CONCAT(x1.class_group, IF((x2.major_short_name <> ''), CONCAT(' ',x2.major_short_name),''),IF((x1.sub_class_group <> ''),CONCAT(' - ',x1.sub_class_group),'')) AS class_name")
            ->join('majors x2', 'x1.major_id = x2.id', 'LEFT')
            ->where('x1.is_deleted', 'false')
            ->orderBy('x1.class_group', 'ASC')
            ->orderBy('x1.major_id', 'ASC')
            ->orderBy('x1.sub_class_group', 'ASC')
            ->get();
        $data = [];
        foreach ($query->getResult() as $row) {
            $data[$row->id] = $row->class_name;
        }
        return $data;
    }
}
