<?php

namespace App\Models;

use App\Models\BaseModel;

class M_answers extends BaseModel
{
    protected $table = 'answers';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'question_id', 'answer', 'is_deleted'
    ];

    public function getWhere(string $keyword = '', string $returnType = 'count', int $limit = 0, int $offset = 0)
    {
        $builder = $this->db->table($this->table . ' x1');
        $builder->select('x1.id, x2.question, x1.answer, x1.is_deleted');
        $builder->join('questions x2', 'x1.question_id = x2.id', 'LEFT');
        if (!empty($keyword)) {
            $builder->like('x2.question', $keyword);
            $builder->orLike('x1.answer', $keyword);
        }
        if ($returnType === 'count') return $builder->countAllResults();
        if ($limit > 0) $builder->limit($limit, $offset);
        return $builder->get();
    }

    public function getAnswers(int $questionId)
    {
        return $this->db->table('answers')
            ->select('id, answer')
            ->where('question_id', _toInteger($questionId))
            ->where('is_deleted', 'false')
            ->get();
    }
}
