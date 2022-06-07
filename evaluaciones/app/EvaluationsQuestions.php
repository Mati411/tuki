<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class EvaluationsQuestions extends Model
{
    /**
     * The primary key associated with the table.
     *
     * @var string
     */
    protected $primaryKey = 'evaluations_questions_id';

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;

    /**
     * Get the evaluation that owns the question.
     */
    public function evaluation()
    {
        return $this->belongsTo('App\Evaluations');
    }
}
