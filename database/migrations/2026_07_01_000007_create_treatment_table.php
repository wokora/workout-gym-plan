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
        Schema::create('treatment', function (Blueprint $table) {
            $table->id();
            $table->foreignId('treatment_type_id')->constrained('treatment_type')->cascadeOnUpdate()->restrictOnDelete();
            $table->foreignId('evidence_level_id')->nullable()->constrained('evidence_level')->cascadeOnUpdate()->nullOnDelete();
            $table->string('name');
            $table->string('slug')->unique();
            $table->longText('description')->nullable();
            $table->longText('mechanism_of_action')->nullable();
            $table->longText('standard_protocol')->nullable();
            $table->text('side_effects')->nullable();
            $table->text('cautions_warnings')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index('name');
            $table->index('treatment_type_id');
            $table->index('evidence_level_id');
            $table->index('deleted_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('treatment');
    }
};
