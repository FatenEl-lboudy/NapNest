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
        Schema::table('cbt_techniques', function (Blueprint $table) {
            $table->boolean('is_active')->default(true)->after('resource_path');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('cbt_techniques', function (Blueprint $table) {
            $table->dropColumn('is_active');
        });
    }
};
