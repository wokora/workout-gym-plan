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
        Schema::create('disease_recommendation', function (Blueprint $table) {
            $table->id();
            $table->foreignId('disease_id')->constrained('disease')->cascadeOnDelete()->cascadeOnUpdate();
            $table->foreignId('recommendation_type_id')->constrained('recommendation_type')->cascadeOnDelete()->cascadeOnUpdate();
            $table->string('recommendable_type');
            $table->unsignedBigInteger('recommendable_id');
            $table->text('rationale')->nullable();
            $table->text('instructions')->nullable();
            $table->timestamps();

            $table->index(['disease_id', 'recommendation_type_id']);
            $table->index(['recommendable_type', 'recommendable_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('disease_recommendation');
    }
};
