<?php

namespace App\Models;

use App\Models\BaseModel;

class M_tags extends BaseModel
{
    protected $table = 'tags';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'tag', 'slug', 'is_deleted'
    ];

    public function getWhere(string $keyword = '', string $returnType = 'count', int $limit = 0, int $offset = 0)
    {
        $builder = $this->db->table($this->table);
        $builder->select('id, tag, slug, is_deleted');
        if (!empty($keyword)) {
            $builder->like('tag', $keyword);
            $builder->orLike('slug', $keyword);
        }
        if ($returnType === 'count') return $builder->countAllResults();
        if ($limit > 0) $builder->limit($limit, $offset);
        return $builder->get();
    }

    public function getTags(int $limit = 0, bool $random = false)
    {
        $builder = $this->db->table($this->table);
        $builder->select('id, tag, slug');
        $builder->where('is_deleted', 'false');
        if ($limit > 0) $builder->limit($limit);
        if ($random) $builder->orderBy('id', 'RANDOM');
        return $builder->get();
    }

    public function insertTags(string $postTags)
    {
        $tags = explode(',', $postTags);
        foreach ($tags as $tag) {
            $builder = $this->db->table($this->table);
            $builder->set('tag', trim($tag));
            $builder->set('slug', slugify(trim($tag)));
            $builder->set('created_by', __session('user_id'));
            $query = $builder->getCompiledInsert();
            $query = str_replace('INSERT INTO', 'INSERT IGNORE INTO', $query);
            $this->db->query($query);
        }
    }
}
