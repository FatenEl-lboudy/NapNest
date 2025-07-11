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
        Schema::create('my_paths', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('patient_id');
            $table->foreign('patient_id')
                ->references('patient_id')
                ->on('users')
                ->onDelete('cascade');
            $table->string('title');
            $table->text('instructions')->nullable();
            $table->unsignedInteger('day_index');
            $table->boolean('is_completed')->default(false);
            $table->timestamp('scheduled_for')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mypaths');
    }
};
