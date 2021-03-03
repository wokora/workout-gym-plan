<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatWorkoutDayExerciseTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('workout_day_exercise', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('workout_day_id');
            $table->unsignedBigInteger('exercise_id');
            $table->integer('number');
            $table->integer('sets');
            $table->integer('reps');
            $table->string('description')->nullable();
            $table->timestamps();
            $table->softDeletes();


            $table->foreign('workout_day_id')->references('id')->on('workout_day')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('exercise_id')->references('id')->on('exercise')->onDelete('cascade')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('workout_day_exercise');
    }
}
