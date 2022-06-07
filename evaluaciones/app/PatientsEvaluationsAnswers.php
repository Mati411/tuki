<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PatientsEvaluationsAnswers extends Model
{
    /**
     * The primary key associated with the table.
     *
     * @var string
     */
    protected $primaryKey = 'patients_evaluations_answers_id';

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;

    /**
     * Get the patient evaluation that owns the answer.
     */
    public function evaluation()
    {
        return $this->belongsTo('App\PatientsEvaluations', 'patients_evaluations_id');
    }

    /**
     * Get the question related to the answer.
     */
    public function question()
    {
        return $this->belongsTo('App\EvaluationsQuestions', 'evaluations_questions_id');
    }
}
