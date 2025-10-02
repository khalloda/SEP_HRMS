<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasColumn('payroll_runs', 'approval_required')) {
            Schema::table('payroll_runs', function (Blueprint $table) {
                $table->boolean('approval_required')->default(false)->after('status');
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('payroll_runs', 'approval_required')) {
            Schema::table('payroll_runs', function (Blueprint $table) {
                $table->dropColumn('approval_required');
            });
        }
    }
};
