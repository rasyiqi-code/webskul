<?php

namespace App\Models;

use App\Models\BaseModel;

class M_albums extends BaseModel
{
    protected $table = 'albums';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'album_title', 'album_description', 'album_cover',
        'album_slug', 'is_deleted'
    ];

    public function getWhere(string $keyword = '', string $returnType = 'count', int $limit = 0, int $offset = 0)
    {
        $builder = $this->db->table($this->table);
        $builder->select('id, album_title, album_description, album_cover, album_slug, is_deleted');
        if (!empty($keyword)) {
            $builder->like('album_title', $keyword);
            $builder->orLike('album_description', $keyword);
            $builder->orLike('album_slug', $keyword);
        }
        if ($returnType === 'count') return $builder->countAllResults();
        if ($limit > 0) $builder->limit($limit, $offset);
        return $builder->get();
    }

    public function getAlbums(int $limit = 0, int $offset = 0)
    {
        $builder = $this->db->table($this->table);
        $builder->select("
            id
            , album_title
            , album_description
            , COALESCE(album_cover, 'no-image.png') AS album_cover
            , album_slug
        ");
        $builder->where('is_deleted', 'false');
        $builder->orderBy('created_at', 'DESC');
        if ($limit > 0) $builder->limit($limit, $offset);
        return $builder->get();
    }
}
