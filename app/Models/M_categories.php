<?php

namespace App\Models;

use CodeIgniter\Model;

class M_categories extends Model
{
    protected $table = 'categories';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'category_name', 'category_description', 'category_slug',
        'category_type', 'is_deleted'
    ];

    public function getCategories(string $categoryType = 'post', int $limit = 0)
    {
        $builder = $this->db->table($this->table);
        $builder->select('id, category_name, category_description, category_slug');
        $builder->where('category_type', $categoryType);
        $builder->where('is_deleted', 'false');
        $builder->orderBy('category_name', 'ASC');
        if ($limit > 0) $builder->limit($limit);
        return $builder->get();
    }
}
