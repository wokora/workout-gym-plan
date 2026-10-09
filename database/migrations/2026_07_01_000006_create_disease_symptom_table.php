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
        Schema::create('disease_symptom', function (Blueprint $table) {
            $table->id();
            $table->foreignId('disease_id')->constrained('disease')->cascadeOnDelete()->cascadeOnUpdate();
            $table->foreignId('symptom_id')->constrained('symptom')->cascadeOnDelete()->cascadeOnUpdate();
            $table->boolean('is_primary')->default(false); // Hallmark / Primary symptom
            $table->string('frequency')->nullable(); // Frequent, Common, Occasional, Rare
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->unique(['disease_id', 'symptom_id']);
            $table->index('is_primary');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('disease_symptom');
    }
};
