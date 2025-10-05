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
        Schema::create('generated_letters', function (Blueprint $table) {
            $table->id();
            $table->foreignId('letter_template_id')->constrained('letter_templates');
            $table->foreignId('employee_id')->constrained('employees');
            $table->string('reference_number', 50)->unique();
            $table->string('subject', 500);
            $table->longText('content');
            $table->enum('status', ['draft', 'pending_approval', 'approved', 'sent', 'rejected'])->default('draft');
            $table->foreignId('generated_by')->constrained('users');
            $table->foreignId('approved_by')->nullable()->constrained('users');
            $table->timestamp('approved_at')->nullable();
            $table->timestamp('sent_at')->nullable();
            $table->json('additional_data')->nullable();
            $table->string('file_path')->nullable();
            $table->timestamps();

            // Indexes
            $table->index(['status', 'created_at']);
            $table->index(['employee_id', 'status']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('generated_letters');
    }
};
