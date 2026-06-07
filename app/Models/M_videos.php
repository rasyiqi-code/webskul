<?php

namespace App\Models;

use App\Models\BaseModel;

class M_videos extends BaseModel
{
    protected $table = 'posts';
    protected $primaryKey = 'id';

    public function getWhere(string $keyword = '', string $returnType = 'count', int $limit = 0, int $offset = 0)
    {
        $builder = $this->db->table($this->table);
        $builder->select('id, post_title, post_content, is_deleted');
        $builder->where('post_type', 'video');
        if (!empty($keyword)) {
            $builder->groupStart();
            $builder->like('post_title', $keyword);
            $builder->groupEnd();
        }
        if ($returnType === 'count') return $builder->countAllResults();
        if ($limit > 0) $builder->limit($limit, $offset);
        return $builder->get();
    }

    public function getVideos(int $limit = 0, int $offset = 0)
    {
        $builder = $this->db->table($this->table);
        $builder->select('id, post_title, post_content');
        $builder->where('post_type', 'video');
        $builder->where('is_deleted', 'false');
        $builder->orderBy('created_at', 'DESC');
        if ($limit > 0) $builder->limit($limit, $offset);
        return $builder->get();
    }
}
