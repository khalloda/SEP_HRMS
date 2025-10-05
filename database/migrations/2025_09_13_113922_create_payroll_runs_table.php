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
        Schema::create('payroll_runs', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('description')->nullable();
            $table->date('pay_period_start');
            $table->date('pay_period_end');
            $table->date('pay_date');
            $table->enum('status', [
                'draft',
                'calculating',
                'calculated',
                'locked',
                'pending_approval',
                'approved',
                'posted',
                'cancelled'
            ])->default('draft');
            $table->string('currency', 3)->default('SAR');
            $table->integer('total_employees')->default(0);
            $table->decimal('total_gross', 15, 2)->default(0);
            $table->decimal('total_net', 15, 2)->default(0);
            $table->decimal('total_deductions', 15, 2)->default(0);
            $table->foreignId('created_by')->constrained('users');
            $table->foreignId('locked_by')->nullable()->constrained('users');
            $table->timestamp('locked_at')->nullable();
            $table->foreignId('approved_by')->nullable()->constrained('users');
            $table->timestamp('approved_at')->nullable();
            $table->foreignId('posted_by')->nullable()->constrained('users');
            $table->timestamp('posted_at')->nullable();
            $table->json('calculation_summary')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();

            // Indexes
            $table->index('status');
            $table->index('pay_period_start');
            $table->index('pay_period_end');
            $table->index('pay_date');
            $table->index(['status', 'pay_date']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payroll_runs');
    }
};
