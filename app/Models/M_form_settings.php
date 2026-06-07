<?php

namespace App\Models;

use App\Models\BaseModel;

class M_form_settings extends BaseModel
{
    protected $table = 'field_settings';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'field_name', 'field_description', 'field_setting'
    ];

    public function getRows()
    {
        return $this->db->table($this->table)
            ->select('id, field_name, field_description, field_setting')
            ->get();
    }

    public function getFormSettings()
    {
        $query = $this->getRows();
        $form = [];
        foreach ($query->getResult() as $row) {
            $fieldSetting = json_decode($row->field_setting);
            $form[$row->field_name] = [
                'admission' => $fieldSetting->admission ?? false,
                'admission_required' => $fieldSetting->admission_required ?? false
            ];
        }
        return $form;
    }
}
