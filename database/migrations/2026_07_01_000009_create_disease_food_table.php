<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('disease_food', function (Blueprint $table) {
            $table->id();
            $table->foreignId('disease_id')->constrained('disease')->cascadeOnDelete()->cascadeOnUpdate();
            $table->foreignId('food_id')->constrained('food')->cascadeOnDelete()->cascadeOnUpdate();
            $table->string('recommendation_type'); // recommended, therapeutic, limit, avoid
            $table->text('rationale')->nullable();
            $table->string('serving_notes')->nullable();
            $table->timestamps();

            $table->unique(['disease_id', 'food_id']);
            $table->index('recommendation_type');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('disease_food');
    }
};
