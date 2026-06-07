<?php

namespace App\Models;

use CodeIgniter\Model;

class M_themes extends Model
{
    protected $table = 'themes';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'theme_name', 'theme_folder', 'theme_author', 'is_active', 'is_deleted'
    ];

    public function getWhere(string $keyword = '', string $returnType = 'count', int $limit = 0, int $offset = 0)
    {
        $builder = $this->db->table($this->table);
        $builder->select('id, theme_name, theme_folder, theme_author, is_active, is_deleted');
        if (!empty($keyword)) {
            $builder->like('theme_name', $keyword);
            $builder->orLike('theme_folder', $keyword);
            $builder->orLike('theme_author', $keyword);
        }
        $builder->orderBy('CAST(theme_name AS SIGNED)', 'ASC');
        if ($returnType === 'count') return $builder->countAllResults();
        if ($limit > 0) $builder->limit($limit, $offset);
        return $builder->get();
    }

    public function getActiveTheme()
    {
        $row = $this->db->table($this->table)
            ->select('theme_folder')
            ->where('is_active', 'true')
            ->limit(1)
            ->get()
            ->getRow();
        return $row ? $row->theme_folder : '1';
    }

    public function deactivateThemes(int $id = 0)
    {
        $builder = $this->db->table($this->table);
        if ($id > 0) $builder->where($this->primaryKey . ' !=', $id);
        return $builder->update(['is_active' => 'false']);
    }
}
