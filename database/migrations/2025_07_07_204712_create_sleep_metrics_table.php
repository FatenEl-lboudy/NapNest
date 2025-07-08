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
        Schema::create('sleep_metrics', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('patient_id');
            $table->foreign('patient_id')
                ->references('patient_id')
                ->on('users')
                ->onDelete('cascade');
            $table->date('sleep_date');
            $table->integer('time_in_bed_min');
            $table->integer('sleep_onset_latency_min');
            $table->integer('total_sleep_time_min');
            $table->integer('wake_after_sleep_onset_min');
            $table->integer('sleep_efficiency_pct');
            $table->integer('number_of_awakenings');
            $table->integer('rem_latency_min')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sleep_metrics');
    }
};
