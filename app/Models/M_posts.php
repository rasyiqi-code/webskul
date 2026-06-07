<?php

namespace App\Models;

use CodeIgniter\Model;

class M_posts extends Model
{
    protected $table = 'posts';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'post_title', 'post_content', 'post_image', 'post_author',
        'post_status', 'post_visibility', 'post_slug', 'post_tags',
        'post_categories', 'post_counter', 'post_type', 'comment_status',
        'is_deleted'
    ];

    public function getWhere(string $keyword = '', string $returnType = 'count', int $limit = 0, int $offset = 0)
    {
        $builder = $this->db->table($this->table . ' x1');
        $builder->select("
            x1.id
            , x1.post_title
            , x2.full_name AS post_author
            , x1.post_status
            , x1.created_at
            , x1.is_deleted
        ");
        $builder->join('users x2', 'x1.post_author = x2.id', 'LEFT');
        $builder->where('x1.post_type', 'post');
        if (filter_var(__session('is_student'), FILTER_VALIDATE_BOOLEAN) || filter_var(__session('is_employee'), FILTER_VALIDATE_BOOLEAN)) {
            $builder->where('x1.post_author', __session('user_id'));
        }
        if (!empty($keyword)) {
            $builder->groupStart();
            $builder->like('x1.post_title', $keyword);
            $builder->orLike('x2.full_name', $keyword);
            $builder->orLike('x1.post_status', $keyword);
            $builder->orLike('x1.created_at', $keyword);
            $builder->groupEnd();
        }
        if ($returnType === 'count') return $builder->countAllResults();
        if ($limit > 0) $builder->limit($limit, $offset);
        return $builder->get();
    }

    public function getOpeningSpeech()
    {
        $row = $this->db->table($this->table)
            ->select('post_content')
            ->where('post_type', 'opening_speech')
            ->limit(1)
            ->get()
            ->getRow();
        return $row ? $row->post_content : '';
    }

    public function openingSpeechUpdate(array $dataset = [])
    {
        $count = $this->db->table($this->table)
            ->where('post_type', 'opening_speech')
            ->countAllResults();
        if ($count === 0) {
            return $this->db->table($this->table)->insert($dataset);
        }
        return $this->db->table($this->table)
            ->where('post_type', 'opening_speech')
            ->update($dataset);
    }
}
