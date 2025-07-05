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
        Schema::create('devices', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('patient_id');
            $table->foreign('patient_id')
                ->references('patient_id')
                ->on('users')
                ->onDelete('cascade');
            $table->string('device_name');
            $table->string('serial_number')->nullable();
            $table->string('connection_type')->default('bluetooth'); // e.g., BLE, USB
            $table->integer('battery_level')->nullable();
            $table->timestamp('last_synced_at')->nullable();
            $table->string('status')->default('connected'); 
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('devices');
    }
};
