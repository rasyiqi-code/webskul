<?php

namespace App\Models;

use CodeIgniter\Model;

class M_post_comments extends Model
{
    protected $table = 'comments';

    public function getRecentComments($limit = 5)
    {
        return $this->select('comments.id, comments.comment_author, comments.comment_content, comments.created_at, posts.post_title')
            ->join('posts', 'posts.id = comments.comment_post_id', 'left')
            ->where('comments.comment_type', 'comment')
            ->where('comments.is_deleted', 'false')
            ->orderBy('comments.created_at', 'DESC')
            ->limit($limit)
            ->get()
            ->getResult();
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
}
