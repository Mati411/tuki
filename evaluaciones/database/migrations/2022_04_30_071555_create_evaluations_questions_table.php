<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEvaluationsQuestionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('evaluations_questions', function (Blueprint $table) {
            $table->increments('evaluations_questions_id');
            $table->integer('evaluations_id')->unsigned();
            $table->string('question');
            $table->string('category');
            $table->integer('type')->unsigned();
            $table->integer('default_value')->nullable();
            $table->boolean('inverse')->default(false);

            $table->foreign('evaluations_id')->references('evaluations_id')->on('evaluations');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('evaluations_questions');
    }
}
