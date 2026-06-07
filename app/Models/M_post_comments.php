<?php

namespace App\Models;

use App\Models\BaseModel;

class M_post_comments extends BaseModel
{
    protected $table = 'comments';

    public function getRecentComments($limit = 5)
    {
        $builder = $this->db->table($this->table . ' x1');
        $builder->select('
            x2.id,
            x1.comment_author,
            x1.comment_url,
            x1.comment_content,
            x2.id AS comment_post_id,
            x2.post_title,
            x2.post_slug,
            x1.created_at
        ');
        $builder->join('posts x2', 'x1.comment_post_id = x2.id', 'LEFT');
        $builder->where('x1.comment_type', 'post');
        $builder->where('x1.comment_status', 'approved');
        $builder->where('x1.is_deleted', 'false');
        $builder->orderBy('x1.created_at', 'DESC');
        if ($limit > 0) $builder->limit($limit);
        return $builder->get()->getResult();
    }

    public function getPostComments(int $commentPostId = 0, int $limit = 0, int $offset = 0)
    {
        $builder = $this->db->table($this->table . ' x1');
        $builder->select('x1.*');
        $builder->join('posts x2', 'x1.comment_post_id = x2.id', 'LEFT');
        $builder->where('x1.comment_type', 'post');
        $builder->where('x1.comment_status', 'approved');
        $builder->where('x1.is_deleted', 'false');
        $builder->where('x1.comment_post_id', $commentPostId);
        $builder->orderBy('x1.created_at', 'DESC');
        if ($limit > 0) $builder->limit($limit, $offset);
        return $builder->get();
    }

    public function getWhere(string $keyword = '', string $returnType = 'count', int $limit = 0, int $offset = 0)
    {
        $builder = $this->db->table($this->table . ' x1');
        $builder->select('
            x1.id, x1.comment_author, x1.comment_url, x1.comment_content, x1.comment_status,
            x2.id AS comment_post_id, x2.post_title, x2.post_slug, x1.created_at, x1.is_deleted
        ');
        $builder->join('posts x2', 'x1.comment_post_id = x2.id', 'LEFT');
        $builder->where('x1.comment_type', 'post');
        if (!empty($keyword)) {
            $builder->groupStart();
            $builder->like('x1.comment_author', $keyword);
            $builder->orLike('x1.comment_content', $keyword);
            $builder->orLike('x2.post_title', $keyword);
            $builder->groupEnd();
        }
        $builder->orderBy('x1.created_at', 'DESC');
        if ($returnType === 'count') return $builder->countAllResults();
        if ($limit > 0) $builder->limit($limit, $offset);
        return $builder->get();
    }
}
