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
        Schema::create('cbt_flashcards', function (Blueprint $table) {
            $table->id();
            $table->foreignId('cbt_technique_id')->nullable()->constrained()->onDelete('cascade'); 
            $table->text('negative_thought');
            $table->text('positive_reframe');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cbt_flashcards');
    }
};
