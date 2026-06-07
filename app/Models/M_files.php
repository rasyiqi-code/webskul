<?php

namespace App\Models;

use App\Models\BaseModel;

class M_files extends BaseModel
{
    protected $table = 'files';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'file_category_id', 'file_title', 'file_name', 'file_ext',
        'file_size', 'file_counter', 'file_visibility', 'is_deleted'
    ];

    public function getWhere(string $keyword = '', string $returnType = 'count', int $limit = 0, int $offset = 0)
    {
        $builder = $this->db->table($this->table . ' x1');
        $builder->select('
            x1.id
            , x1.file_title
            , x1.file_name
            , x1.file_ext
            , x1.file_size
            , x2.category_name
            , x1.file_counter
            , x1.file_visibility
            , x1.is_deleted
        ');
        $builder->join('categories x2', 'x1.file_category_id = x2.id', 'LEFT');
        $builder->where('x2.category_type', 'file');
        if (!empty($keyword)) {
            $builder->groupStart();
            $builder->like('x1.file_title', $keyword);
            $builder->orLike('x1.file_name', $keyword);
            $builder->orLike('x1.file_ext', $keyword);
            $builder->orLike('x1.file_size', $keyword);
            $builder->orLike('x2.category_name', $keyword);
            $builder->orLike('x1.file_visibility', $keyword);
            $builder->groupEnd();
        }
        if ($returnType === 'count') return $builder->countAllResults();
        if ($limit > 0) $builder->limit($limit, $offset);
        return $builder->get();
    }

    public function getFiles(string $slug = '', int $limit = 0, int $offset = 0)
    {
        $builder = $this->db->table($this->table . ' x1');
        $builder->select('
            x1.id
            , x1.file_title
            , x1.file_name
            , x1.file_ext
            , x1.file_size
            , x2.category_name
            , x1.file_counter
            , x1.file_visibility
        ');
        $builder->join("categories x2", "x1.file_category_id = x2.id AND x2.category_type = 'file'", "LEFT");
        $builder->where('x1.is_deleted', 'false');
        if (!empty($slug)) $builder->where('x2.category_slug', $slug);
        $builder->where('x1.file_visibility', 'public');
        if ($limit > 0) $builder->limit($limit, $offset);
        return $builder->get();
    }

    public function setFileCounter(int $id)
    {
        return $this->db->table('files')
            ->set('file_counter', 'file_counter + 1', false)
            ->where('id', _toInteger($id))
            ->update();
    }
}
