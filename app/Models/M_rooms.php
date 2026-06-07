<?php

namespace App\Models;

use App\Models\BaseModel;

class M_rooms extends BaseModel
{
    protected $table = 'rooms';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'building_id', 'room_name', 'room_capacity', 'is_class_room', 'is_deleted'
    ];

    public function getWhere(string $keyword = '', string $returnType = 'count', int $limit = 0, int $offset = 0)
    {
        $builder = $this->db->table($this->table);
        $builder->select('id, building_id, room_name, room_capacity, is_class_room, is_deleted');
        if (!empty($keyword)) {
            $builder->groupStart();
            $builder->like('room_name', $keyword);
            $builder->orLike('room_capacity', $keyword);
            $builder->groupEnd();
        }
        $builder->orderBy('id', 'ASC');
        if ($returnType === 'count') return $builder->countAllResults();
        if ($limit > 0) $builder->limit($limit, $offset);
        return $builder->get();
    }

    public function dropdown()
    {
        $data = [];
        $query = $this->db->table($this->table)
            ->select('id, room_name')
            ->where('is_deleted', 'false')
            ->orderBy('room_name', 'ASC')
            ->get();
        foreach ($query->getResult() as $row) {
            $data[$row->id] = $row->room_name;
        }
        return $data;
    }
}
