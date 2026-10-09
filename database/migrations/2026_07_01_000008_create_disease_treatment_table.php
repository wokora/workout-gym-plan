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
        Schema::create('disease_treatment', function (Blueprint $table) {
            $table->id();
            $table->foreignId('disease_id')->constrained('disease')->cascadeOnDelete()->cascadeOnUpdate();
            $table->foreignId('treatment_id')->constrained('treatment')->cascadeOnDelete()->cascadeOnUpdate();
            $table->boolean('is_primary_cure')->default(false); // Curative vs supportive / symptom relief
            $table->string('line_of_treatment')->nullable(); // First-line, Second-line, Adjunctive, Preventative
            $table->longText('specific_instructions')->nullable();
            $table->string('dosage_or_frequency')->nullable();
            $table->string('expected_duration')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->unique(['disease_id', 'treatment_id']);
            $table->index('is_primary_cure');
            $table->index('line_of_treatment');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('disease_treatment');
    }
};
