<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Evaluations extends Model
{
    /**
     * The primary key associated with the table.
     *
     * @var string
     */
    protected $primaryKey = 'evaluations_id';

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;

    /**
     * Get the questions for the evaluation.
     */
    public function questions()
    {
        return $this->hasMany('App\EvaluationsQuestions', 'evaluations_id' );
    }

    /**
     * Get the created instances for the evaluation.
     */
    public function evaluations()
    {
        return $this->hasMany('App\PatientsEvaluations');
    }
}
