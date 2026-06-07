<?php

namespace App\Models;

use CodeIgniter\Model;

class M_links extends Model
{
    protected $table = 'links';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'link_url', 'link_title', 'link_target', 'link_type',
        'link_image', 'is_deleted'
    ];

    public function getWhere(string $keyword = '', string $returnType = 'count', int $limit = 0, int $offset = 0)
    {
        $builder = $this->db->table($this->table);
        $builder->select('id, link_url, link_title, link_target, is_deleted');
        $builder->where('link_type', 'link');
        if (!empty($keyword)) {
            $builder->groupStart();
            $builder->like('link_url', $keyword);
            $builder->orLike('link_title', $keyword);
            $builder->orLike('link_target', $keyword);
            $builder->groupEnd();
        }
        if ($returnType === 'count') return $builder->countAllResults();
        if ($limit > 0) $builder->limit($limit, $offset);
        return $builder->get();
    }

    public function getLinks(int $limit = 0)
    {
        $builder = $this->db->table($this->table);
        $builder->select('id, link_title, link_url, link_target');
        $builder->where('link_type', 'link');
        $builder->where('is_deleted', 'false');
        if ($limit > 0) $builder->limit($limit);
        return $builder->get();
    }
}
