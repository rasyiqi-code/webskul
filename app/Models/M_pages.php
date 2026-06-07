<?php

namespace App\Models;

use CodeIgniter\Model;

class M_pages extends Model
{
    protected $table = 'posts';
    protected $primaryKey = 'id';

    public function getWhere(string $keyword = '', string $returnType = 'count', int $limit = 0, int $offset = 0)
    {
        $builder = $this->db->table($this->table . ' x1');
        $builder->select('
            x1.id
            , x1.post_title
            , x2.full_name AS post_author
            , x1.created_at
            , x1.is_deleted
        ');
        $builder->join('users x2', 'x1.post_author = x2.id', 'LEFT');
        $builder->where('x1.post_type', 'page');
        if (!empty($keyword)) {
            $builder->groupStart();
            $builder->like('x1.post_title', $keyword);
            $builder->orLike('x2.full_name', $keyword);
            $builder->orLike('x1.created_at', $keyword);
            $builder->groupEnd();
        }
        if ($returnType === 'count') return $builder->countAllResults();
        if ($limit > 0) $builder->limit($limit, $offset);
        return $builder->get();
    }

    public function getPages()
    {
        return $this->db->table($this->table)
            ->select('id, post_title')
            ->where('post_type', 'page')
            ->where('is_deleted', 'false')
            ->get();
    }
}
