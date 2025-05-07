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
        Schema::create('psqi_tests', function (Blueprint $table) {
            $table->id();
           $table->unsignedBigInteger('patient_id');

            $table->integer('score')->nullable();
            $table->string('status')->nullable();
            $table->integer('sleep_quality')->nullable();
            $table->integer('sleep_latency')->nullable();
            $table->integer('sleep_duration')->nullable();
            $table->integer('sleep_efficiency')->nullable();
            $table->integer('sleep_disturbances')->nullable();
            $table->integer('use_of_sleep_medication')->nullable();
            $table->integer('daytime_dysfunction')->nullable();
            $table->json('answers')->nullable();

            $table->foreign('patient_id')
                  ->references('patient_id')
                  ->on('users')
                  ->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('psqi_tests');
    }
};
