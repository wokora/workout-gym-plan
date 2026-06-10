<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFoodTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('food', function (Blueprint $table) {
            $table->id();
            $table->string('code', 10);
            $table->string('name');
            $table->decimal('energy_kj', 8, 2)->nullable();
            $table->decimal('energy_kcal', 8, 2)->nullable();
            $table->decimal('water', 8, 2)->nullable();
            $table->decimal('protein', 8, 2)->nullable();
            $table->decimal('fat', 8, 2)->nullable();
            $table->decimal('carbs', 8, 2)->nullable();
            $table->decimal('fibre', 8, 2)->nullable();
            $table->decimal('ash', 8, 2)->nullable();
            $table->decimal('ca', 8, 2)->nullable();
            $table->decimal('mg', 8, 2)->nullable();
            $table->decimal('fe', 8, 2)->nullable();
            $table->decimal('na', 8, 2)->nullable();
            $table->decimal('vit_a', 8, 2)->nullable();
            $table->decimal('folate', 8, 2)->nullable();
            $table->decimal('vit_c', 8, 2)->nullable();
            $table->string('standard_portion_description')->nullable();
            $table->decimal('standard_portion_grams', 8, 2)->nullable();
            $table->string('description')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('food');
    }
}
