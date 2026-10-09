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
        Schema::create('disease_exercise', function (Blueprint $table) {
            $table->id();
            $table->foreignId('disease_id')->constrained('disease')->cascadeOnDelete()->cascadeOnUpdate();
            $table->foreignId('exercise_id')->constrained('exercise')->cascadeOnDelete()->cascadeOnUpdate();
            $table->string('safety_type'); // recommended, rehabilitative, caution, contraindicated
            $table->text('instructions')->nullable();
            $table->text('modifications')->nullable();
            $table->timestamps();

            $table->unique(['disease_id', 'exercise_id']);
            $table->index('safety_type');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('disease_exercise');
    }
};
