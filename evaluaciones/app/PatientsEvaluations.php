<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PatientsEvaluations extends Model
{
    /**
     * The primary key associated with the table.
     *
     * @var string
     */
    protected $primaryKey = 'patients_evaluations_id';

    /**
     * The relationships that should always be loaded.
     *
     * @var array
     */
    protected $with = ['evaluation'];

    /**
     * Get the evaluation that owns the question.
     */
    public function evaluation()
    {
        return $this->belongsTo('App\Evaluations', 'evaluations_id', 'evaluations_id');
    }

    /**
     * Get the answers for the patient evaluation.
     */
    public function answers()
    {
        return $this->hasMany('App\PatientsEvaluationsAnswers', 'patients_evaluations_id', 'patients_evaluations_id');
    }
}
