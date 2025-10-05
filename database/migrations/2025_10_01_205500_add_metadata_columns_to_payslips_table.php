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
        Schema::table('payslips', function (Blueprint $table) {
            if (! Schema::hasColumn('payslips', 'salary_structure_id')) {
                $table->unsignedBigInteger('salary_structure_id')
                    ->nullable()
                    ->after('employee_id');
            }

            if (! Schema::hasColumn('payslips', 'employee_arabic_name')) {
                $table->string('employee_arabic_name', 200)
                    ->nullable()
                    ->after('employee_name');
            }

            if (! Schema::hasColumn('payslips', 'department_name')) {
                $table->string('department_name', 200)
                    ->nullable()
                    ->after('employee_arabic_name');
            }

            if (! Schema::hasColumn('payslips', 'position_name')) {
                $table->string('position_name', 200)
                    ->nullable()
                    ->after('department_name');
            }

            if (! Schema::hasColumn('payslips', 'currency')) {
                $table->string('currency', 3)
                    ->default('SAR')
                    ->after('pay_date');
            }

            if (! Schema::hasColumn('payslips', 'basic_salary')) {
                $table->decimal('basic_salary', 15, 2)
                    ->default(0)
                    ->after('total_deductions');
            }

            if (! Schema::hasColumn('payslips', 'generated_at')) {
                $table->timestamp('generated_at')
                    ->nullable()
                    ->after('status');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('payslips', function (Blueprint $table) {
            if (Schema::hasColumn('payslips', 'salary_structure_id')) {
                $table->dropColumn('salary_structure_id');
            }

            if (Schema::hasColumn('payslips', 'employee_arabic_name')) {
                $table->dropColumn('employee_arabic_name');
            }

            if (Schema::hasColumn('payslips', 'department_name')) {
                $table->dropColumn('department_name');
            }

            if (Schema::hasColumn('payslips', 'position_name')) {
                $table->dropColumn('position_name');
            }

            if (Schema::hasColumn('payslips', 'currency')) {
                $table->dropColumn('currency');
            }

            if (Schema::hasColumn('payslips', 'basic_salary')) {
                $table->dropColumn('basic_salary');
            }

            if (Schema::hasColumn('payslips', 'generated_at')) {
                $table->dropColumn('generated_at');
            }
        });
    }
};
