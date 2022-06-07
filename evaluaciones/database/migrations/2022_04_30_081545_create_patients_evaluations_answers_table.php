<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePatientsEvaluationsAnswersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('patients_evaluations_answers', function (Blueprint $table) {
            $table->increments('patients_evaluations_answers_id');
            $table->integer('patients_evaluations_id')->unsigned();
            $table->integer('evaluations_questions_id')->unsigned();
            $table->string('value');

            $table->foreign('patients_evaluations_id')->references('patients_evaluations_id')->on('patients_evaluations');
            $table->foreign('evaluations_questions_id')->references('evaluations_questions_id')->on('evaluations_questions');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('patients_evaluations_answers');
    }
}
