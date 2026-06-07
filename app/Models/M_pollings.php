<?php

namespace App\Models;

use App\Models\BaseModel;

class M_pollings extends BaseModel
{
    protected $table = 'pollings';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'answer_id', 'ip_address'
    ];

    public function insertVote(int $answerId)
    {
        $count = $this->db->table($this->table)
            ->where('ip_address', $_SERVER['REMOTE_ADDR'])
            ->where('LEFT(created_at, 10)=', date('Y-m-d'))
            ->countAllResults();
        if ($count === 0) {
            $builder = $this->db->table($this->table);
            $builder->set('answer_id', $answerId);
            $builder->set('ip_address', get_ip_address());
            $builder->set('created_by', __session('user_id'));
            $query = $builder->getCompiledInsert();
            $query = str_replace('INSERT INTO', 'INSERT IGNORE INTO', $query);
            return $this->db->query($query);
        }
        return false;
    }

    public function getPollings(int $questionId)
    {
        $builder = $this->db->table($this->table . ' x1');
        $builder->select('x2.answer AS labels, COUNT(*) AS data');
        $builder->join('answers x2', 'x1.answer_id = x2.id', 'LEFT');
        $builder->where('x2.question_id', _toInteger($questionId));
        $builder->groupBy('x1.answer_id');
        $builder->orderBy('x1.answer_id', 'ASC');
        return $builder->get();
    }
}
