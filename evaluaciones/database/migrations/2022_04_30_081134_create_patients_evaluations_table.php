<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePatientsEvaluationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('patients_evaluations', function (Blueprint $table) {
            $table->increments('patients_evaluations_id');
            $table->integer('evaluations_id')->unsigned();
            $table->uuid('guid');
            $table->string('reference');
            $table->enum('gender', ['MALE', 'FEMALE']);
            $table->boolean('answered')->default(false);
            $table->timestamp('answered_on')->nullable();
            $table->timestamps();

            $table->foreign('evaluations_id')->references('evaluations_id')->on('evaluations');

            $table->index('guid');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('patients_evaluations');
    }
}
