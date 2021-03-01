<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatWorkoutDayTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('workout_day', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('day_id');
            $table->unsignedBigInteger('workout_id');
            $table->string('description')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('day_id')->references('id')->on('day')->onDelete('cascade')->onUpdate('cascade');
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
        Schema::dropIfExists('workout_day');
    }
}
