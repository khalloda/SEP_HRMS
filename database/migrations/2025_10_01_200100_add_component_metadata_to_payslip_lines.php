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
        Schema::table('payslip_lines', function (Blueprint $table) {
            if (! Schema::hasColumn('payslip_lines', 'component_code')) {
                $table->string('component_code', 100)->nullable()->after('salary_component_id');
                $table->index('component_code');
            }

            if (! Schema::hasColumn('payslip_lines', 'component_name_en')) {
                $table->string('component_name_en', 150)->nullable()->after('component_code');
            }

            if (! Schema::hasColumn('payslip_lines', 'component_name_ar')) {
                $table->string('component_name_ar', 150)->nullable()->after('component_name_en');
            }

            if (! Schema::hasColumn('payslip_lines', 'formula_used')) {
                $table->string('formula_used', 500)->nullable()->after('formula');
            }

            if (! Schema::hasColumn('payslip_lines', 'priority_order')) {
                $table->integer('priority_order')->default(0)->after('priority');
            }

            if (! Schema::hasColumn('payslip_lines', 'include_in_gross')) {
                $table->boolean('include_in_gross')->default(false)->after('priority_order');
            }

            if (! Schema::hasColumn('payslip_lines', 'taxable')) {
                $table->boolean('taxable')->default(false)->after('include_in_gross');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('payslip_lines', function (Blueprint $table) {
            if (Schema::hasColumn('payslip_lines', 'component_code')) {
                $table->dropIndex(['component_code']);
                $table->dropColumn('component_code');
            }

            if (Schema::hasColumn('payslip_lines', 'component_name_en')) {
                $table->dropColumn('component_name_en');
            }

            if (Schema::hasColumn('payslip_lines', 'component_name_ar')) {
                $table->dropColumn('component_name_ar');
            }

            if (Schema::hasColumn('payslip_lines', 'formula_used')) {
                $table->dropColumn('formula_used');
            }

            if (Schema::hasColumn('payslip_lines', 'priority_order')) {
                $table->dropColumn('priority_order');
            }

            if (Schema::hasColumn('payslip_lines', 'include_in_gross')) {
                $table->dropColumn('include_in_gross');
            }

            if (Schema::hasColumn('payslip_lines', 'taxable')) {
                $table->dropColumn('taxable');
            }
        });
    }
};
