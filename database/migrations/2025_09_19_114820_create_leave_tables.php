<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('leave_policies')) {
            Schema::create('leave_policies', function (Blueprint $table) {
                $table->id();
                $table->string('code', 50)->unique();
                $table->string('name');
                $table->string('accrual_rule', 50)->default('fixed'); // fixed|monthly|yearly
                $table->decimal('days_per_year', 5, 2)->default(0);
                $table->boolean('carry_over')->default(false);
                $table->decimal('max_carry_over', 5, 2)->default(0);
                $table->timestamps();
            });
        }
        if (!Schema::hasTable('leave_balances')) {
            Schema::create('leave_balances', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('user_id')->index();
                $table->unsignedBigInteger('policy_id')->index();
                $table->string('period', 10)->index(); // e.g., 2025
                $table->decimal('opening', 6, 2)->default(0);
                $table->decimal('accrued', 6, 2)->default(0);
                $table->decimal('taken', 6, 2)->default(0);
                $table->decimal('closing', 6, 2)->default(0);
                $table->timestamps();
                $table->unique(['user_id','policy_id','period']);
            });
        }
        if (!Schema::hasTable('leave_requests')) {
            Schema::create('leave_requests', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('user_id')->index();
                $table->unsignedBigInteger('policy_id')->index();
                $table->date('from_date');
                $table->date('to_date');
                $table->decimal('days', 6, 2);
                $table->string('status', 20)->default('pending')->index(); // pending|approved|rejected|cancelled
                $table->unsignedBigInteger('approver_id')->nullable()->index();
                $table->text('reason')->nullable();
                $table->timestamps();
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('leave_requests');
        Schema::dropIfExists('leave_balances');
        Schema::dropIfExists('leave_policies');
    }
};

