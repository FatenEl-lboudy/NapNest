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
        Schema::create('sleep_timeline_segments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('sleep_metric_id')->constrained()->onDelete('cascade');
            $table->enum('state', ['Sleep', 'Wake']);
            $table->time('start_time');
            $table->time('end_time');
            $table->decimal('duration_min', 5, 2);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sleep_timeline_segments');
    }
};
