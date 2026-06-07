<?php

namespace App\Models;

use CodeIgniter\Model;

class M_photos extends Model
{
    protected $table = 'photos';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'photo_album_id', 'photo_name', 'is_deleted'
    ];

    public function getWhere(string $keyword = '', string $returnType = 'count', int $limit = 0, int $offset = 0)
    {
        $builder = $this->db->table($this->table . ' x1');
        $builder->select('x1.id, CONCAT("thumbnail/",x1.photo_name) AS photo_name, x2.album_title, x1.is_deleted');
        $builder->join('albums x2', 'x1.photo_album_id=x2.id', 'left');
        if (!empty($keyword)) $builder->like('x2.album_title', $keyword);
        if ($returnType === 'count') return $builder->countAllResults();
        if ($limit > 0) $builder->limit($limit, $offset);
        return $builder->get();
    }

    public function deletePermanently(int $id)
    {
        $this->db->transBegin();
        $this->db->table($this->table)->where($this->primaryKey, $id)->delete();
        return $this->db->transStatus();
    }

    public function getPhotos(int $id)
    {
        return $this->db->table($this->table)
            ->select('photo_name')
            ->where('photo_album_id', $id)
            ->get();
    }
}
