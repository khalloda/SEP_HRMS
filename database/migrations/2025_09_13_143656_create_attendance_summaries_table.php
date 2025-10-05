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
        Schema::create('attendance_summaries', function (Blueprint $table) {
            $table->id();
            $table->foreignId('employee_id')->constrained()->onDelete('cascade');
            $table->string('employee_code', 50); // For quick lookup
            $table->date('date'); // Summary date
            $table->time('expected_start_time')->nullable(); // Expected work start
            $table->time('expected_end_time')->nullable(); // Expected work end
            $table->dateTime('first_check_in')->nullable(); // Actual first check in
            $table->dateTime('last_check_out')->nullable(); // Actual last check out
            $table->integer('total_work_minutes')->default(0); // Total work time in minutes
            $table->integer('break_minutes')->default(0); // Total break time in minutes
            $table->integer('late_minutes')->default(0); // Minutes late for start time
            $table->integer('early_departure_minutes')->default(0); // Minutes left early
            $table->integer('overtime_minutes')->default(0); // Overtime minutes (only for eligible positions)
            $table->boolean('is_absent')->default(false); // No attendance records
            $table->boolean('is_holiday')->default(false); // Company holiday
            $table->enum('status', ['present', 'absent', 'partial', 'holiday', 'leave'])->default('present');
            $table->text('notes')->nullable(); // HR notes or adjustments
            $table->json('anomalies')->nullable(); // Detected anomalies array
            $table->timestamps();

            // Indexes for performance
            $table->index(['employee_id', 'date']);
            $table->index('employee_code'); // Separate index for employee_code
            $table->index('date');
            $table->index('status');
            $table->index('is_absent');

            // Ensure one summary per employee per day
            $table->unique(['employee_id', 'date']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('attendance_summaries');
    }
};
