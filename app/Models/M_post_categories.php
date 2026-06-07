<?php

namespace App\Models;

use CodeIgniter\Model;

class M_post_categories extends Model
{
    protected $table = 'categories';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'category_name', 'category_description', 'category_slug',
        'category_type', 'is_deleted'
    ];

    public function getWhere(string $keyword = '', string $returnType = 'count', int $limit = 0, int $offset = 0)
    {
        $builder = $this->db->table($this->table);
        $builder->where('category_type', 'post');
        if (!empty($keyword)) {
            $builder->groupStart();
            $builder->like('category_name', $keyword);
            $builder->orLike('category_description', $keyword);
            $builder->orLike('category_slug', $keyword);
            $builder->groupEnd();
        }
        if ($returnType === 'count') return $builder->countAllResults();
        if ($limit > 0) $builder->limit($limit, $offset);
        return $builder->get();
    }

    public function getPostCategories(int $limit = 0)
    {
        $builder = $this->db->table($this->table);
        $builder->where('category_type', 'post');
        $builder->where('is_deleted', 'false');
        if ($limit > 0) $builder->limit($limit);
        return $builder->get();
    }

    public function rowArray()
    {
        $query = $this->getPostCategories();
        $data = [];
        foreach ($query->getResult() as $row) {
            $data[$row->id] = $row->category_name;
        }
        return $data;
    }

    public function save(array $dataset)
    {
        $query = $this->db->table($this->table)->insert($dataset);
        return $query ? $this->db->insertID() : 0;
    }
}
