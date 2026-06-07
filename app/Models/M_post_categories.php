<?php

namespace App\Models;

use App\Models\BaseModel;

class M_post_categories extends BaseModel
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

    public function save($row): bool
    {
        $dataset = is_array($row) ? $row : (array) $row;
        $query = $this->db->table($this->table)->insert($dataset);
        return (bool) $query;
    }

    /**
     * Insert kategori baru dan kembalikan insert_id (untuk kompatibilitas CI3).
     * Method ini dipanggil oleh Blog/Post_categories::save() yang mengharapkan
     * nilai integer insert_id, bukan boolean.
     */
    public function insertCategory(array $dataset): int
    {
        $query = $this->db->table($this->table)->insert($dataset);
        return $query ? (int) $this->db->insertID() : 0;
    }
}
