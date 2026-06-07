<?php

namespace App\Models;

use App\Models\BaseModel;

class M_image_sliders extends BaseModel
{
    protected $table = 'image_sliders';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'caption', 'image', 'is_deleted'
    ];

    public function getWhere(string $keyword = '', string $returnType = 'count', int $limit = 0, int $offset = 0)
    {
        $builder = $this->db->table($this->table);
        $builder->select('id, caption, image, is_deleted');
        if (!empty($keyword)) $builder->like('caption', $keyword);
        if ($returnType === 'count') return $builder->countAllResults();
        if ($limit > 0) $builder->limit($limit, $offset);
        return $builder->get();
    }

    public function getImageSliders(int $limit = 0)
    {
        $builder = $this->db->table($this->table);
        $builder->select('id, caption, image');
        $builder->where('is_deleted', 'false');
        if ($limit > 0) $builder->limit($limit);
        return $builder->get();
    }
}
