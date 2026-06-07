<?php

namespace App\Models;

use CodeIgniter\Model;

class M_menus extends Model
{
    protected $table = 'menus';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'menu_title', 'menu_url', 'menu_target', 'menu_type',
        'menu_parent_id', 'menu_position', 'is_deleted'
    ];

    public function getMenus()
    {
        return $this->db->table($this->table)
            ->select('id, menu_title, menu_url, menu_type, is_deleted')
            ->orderBy('menu_parent_id', 'ASC')
            ->orderBy('menu_position', 'ASC')
            ->get();
    }

    public function nestedMenus(int $menuParentId = 0)
    {
        $menu = [];
        $query = $this->db->table($this->table)
            ->select('id, menu_title, menu_url, menu_target, menu_type')
            ->where('menu_parent_id', $menuParentId)
            ->where('is_deleted', 'false')
            ->orderBy('menu_position', 'ASC')
            ->orderBy('menu_title', 'ASC')
            ->get();
        foreach ($query->getResult() as $row) {
            $menu[] = [
                'id' => $row->id,
                'menu_title' => $row->menu_title,
                'menu_url' => $row->menu_url,
                'menu_target' => $row->menu_target,
                'menu_type' => $row->menu_type,
                'children' => $this->nestedMenus($row->id),
            ];
        }
        return $menu;
    }

    public function saveMenuPosition(int $menuParentId, $children)
    {
        if (!is_null($children)) {
            $i = 1;
            foreach ($children as $value) {
                $id = $value['id'];
                $this->db->table($this->table)
                    ->where($this->primaryKey, $id)
                    ->update([
                        'menu_parent_id' => $menuParentId,
                        'menu_position' => $i
                    ]);
                if (isset($value['children'][0])) {
                    $this->saveMenuPosition($id, $value['children']);
                }
                $i++;
            }
        }
    }
}
