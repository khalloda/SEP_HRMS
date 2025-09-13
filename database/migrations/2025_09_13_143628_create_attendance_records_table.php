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
        Schema::create('attendance_records', function (Blueprint $table) {
            $table->id();
            $table->foreignId('employee_id')->constrained()->onDelete('cascade');
            $table->string('employee_code', 50); // For quick lookup
            $table->dateTime('timestamp'); // Actual biometric timestamp
            $table->enum('type', ['check_in', 'check_out', 'break_start', 'break_end'])->default('check_in');
            $table->string('device_id')->nullable(); // ZKTeco device identifier
            $table->string('device_info')->nullable(); // Device model/name
            $table->json('raw_data')->nullable(); // Original ZKTeco payload
            $table->enum('status', ['valid', 'invalid', 'duplicate', 'anomaly'])->default('valid');
            $table->text('notes')->nullable(); // Admin notes or anomaly details
            $table->timestamps();

            // Indexes for performance
            $table->index(['employee_id', 'timestamp']);
            $table->index('employee_code'); // Separate index for employee_code
            $table->index('timestamp');
            $table->index('type');
            $table->index('status');

            // Prevent exact duplicates
            $table->unique(['employee_id', 'timestamp', 'type']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('attendance_records');
    }
};
