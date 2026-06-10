<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMealPlanDayEntryFoodTable extends Migration
{
    public function up()
    {
        Schema::create('meal_plan_day_entry_food', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('meal_plan_day_entry_id');
            $table->unsignedBigInteger('food_id');
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('meal_plan_day_entry_id')->references('id')->on('meal_plan_day_entry')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('food_id')->references('id')->on('food')->onDelete('cascade')->onUpdate('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('meal_plan_day_entry_food');
    }
}
