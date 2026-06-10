<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMealPlanDayEntryTable extends Migration
{
    public function up()
    {
        Schema::create('meal_plan_day_entry', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('meal_plan_day_id');
            $table->unsignedBigInteger('meal_type_id');
            $table->integer('number');
            $table->string('description')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('meal_plan_day_id')->references('id')->on('meal_plan_day')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('meal_type_id')->references('id')->on('meal_type')->onDelete('cascade')->onUpdate('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('meal_plan_day_entry');
    }
}
