<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserVitalsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_vitals', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('vital_id');
            $table->decimal('value', 13, 2);
            $table->date('date_measured');
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('user_id')->references('id')->on('user')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('vital_id')->references('id')->on('vitals')->onDelete('cascade')->onUpdate('cascade');
            $table->index(['user_id', 'vital_id', 'date_measured']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('user_vitals');
    }
}
