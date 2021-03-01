<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateExerciseBodySectionTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('exercise_body_section', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('exercise_id');
            $table->unsignedBigInteger('body_section_id');
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('exercise_id')->references('id')->on('exercise')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('body_section_id')->references('id')->on('body_section')->onDelete('cascade')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('exercise_body_section');
    }
}
