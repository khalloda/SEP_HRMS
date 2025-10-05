<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('contracts', function (Blueprint $table) {
            if (!Schema::hasColumn('contracts', 'status')) {
                $table->string('status', 32)->default('draft')->index();
            }
            if (!Schema::hasColumn('contracts', 'approved_at')) {
                $table->timestamp('approved_at')->nullable();
                $table->unsignedBigInteger('approved_by')->nullable()->index();
            }
            if (!Schema::hasColumn('contracts', 'signed_at')) {
                $table->timestamp('signed_at')->nullable();
                $table->unsignedBigInteger('signed_by')->nullable()->index();
            }
            if (!Schema::hasColumn('contracts', 'termination_reason')) {
                $table->string('termination_reason', 191)->nullable();
                $table->text('termination_note')->nullable();
                $table->timestamp('termination_at')->nullable();
            }
        });
    }

    public function down(): void
    {
        Schema::table('contracts', function (Blueprint $table) {
            // Keep data by default; optional rollback
            if (Schema::hasColumn('contracts', 'termination_at')) {
                $table->dropColumn(['termination_at','termination_note','termination_reason']);
            }
            if (Schema::hasColumn('contracts', 'signed_at')) {
                $table->dropColumn(['signed_at','signed_by']);
            }
            if (Schema::hasColumn('contracts', 'approved_at')) {
                $table->dropColumn(['approved_at','approved_by']);
            }
            if (Schema::hasColumn('contracts', 'status')) {
                $table->dropColumn('status');
            }
        });
    }
};

