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
        Schema::create('file', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid');
            $table->foreignId('file_type_id')->constrained('file_type')->onUpdate('cascade')->onDelete('cascade');
            $table->foreignId('file_status_id')->constrained('file_status')->onUpdate('cascade')->onDelete('cascade');
            $table->morphs('fileable');
            $table->string('name', 100);
            $table->string('original_name');
            $table->integer('size', false);
            $table->string('type', 100);
            $table->string('file_hash', 64)->nullable(); // for duplicate detection
            $table->integer('position')->nullable();
            $table->string('payload')->nullable();
            $table->timestamps();
            $table->softDeletes();

            // Performance indexes
            $table->index('file_hash'); // For duplicate detection
            $table->index(['file_type_id', 'file_status_id']);
            $table->index('position');
            $table->index('size'); // For file size queries
            $table->index('deleted_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('file');
    }
};
