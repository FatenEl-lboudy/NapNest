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
        Schema::create('cbt_techniques', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->enum('type', ['meditation', 'flashcard', 'breathing', 'tips'])->default('tips');
            $table->text('description')->nullable();
            $table->string('resource_path')->nullable(); // for PDF, video, or JSON file
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cbt_techniques');
    }
};
