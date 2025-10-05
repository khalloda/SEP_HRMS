<?php

namespace Tests\Feature\Payroll;

use App\Models\User;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class PayrollFeatureFlagTest extends TestCase
{
    use DatabaseTransactions;

    protected function setUp(): void
    {
        parent::setUp();

        $this->ensurePayrollSchema();

        Role::firstOrCreate(['name' => 'HR_Admin_Manager', 'guard_name' => 'web']);
    }

    public function test_payroll_routes_return_not_found_when_flag_disabled(): void
    {
        config()->set('payroll.enabled', false);

        $response = $this->actingAs($this->makePayrollUser())
            ->get(route('payroll.statistics'));

        $response->assertNotFound();
    }

    public function test_payroll_routes_accessible_when_flag_enabled(): void
    {
        config()->set('payroll.enabled', true);

        $response = $this->actingAs($this->makePayrollUser())
            ->get(route('payroll.statistics'));

        $response->assertOk()
            ->assertJsonStructure([
                'total_runs',
                'draft_runs',
                'pending_approval',
                'posted_this_year',
                'total_payslips_this_year',
                'monthly_totals',
            ]);
    }

    protected function makePayrollUser(): User
    {
        $user = User::create([
            'name' => 'Payroll Admin',
            'email' => Str::uuid().'@example.com',
            'password' => 'password123',
        ]);

        $user->assignRole('HR_Admin_Manager');

        return $user;
    }

    protected function ensurePayrollSchema(): void
    {
        if (Schema::hasTable('payroll_runs') && ! Schema::hasColumn('payroll_runs', 'pay_date')) {
            Schema::drop('payroll_runs');
        }

        if (Schema::hasTable('payslips') && ! Schema::hasColumn('payslips', 'pay_date')) {
            Schema::drop('payslips');
        }

        if (Schema::hasTable('payslip_lines') && ! Schema::hasColumn('payslip_lines', 'formula')) {
            Schema::drop('payslip_lines');
        }

        if (! Schema::hasTable('payroll_runs')) {
            Schema::create('payroll_runs', function (Blueprint $table) {
                $table->id();
                $table->string('title')->nullable();
                $table->date('pay_period_start')->nullable();
                $table->date('pay_period_end')->nullable();
                $table->date('pay_date')->nullable();
                $table->string('status')->default('draft');
                $table->decimal('total_gross', 15, 2)->default(0);
                $table->decimal('total_net', 15, 2)->default(0);
                $table->decimal('total_deductions', 15, 2)->default(0);
                $table->unsignedBigInteger('created_by')->nullable();
                $table->timestamps();
            });
        }

        if (! Schema::hasTable('payslips')) {
            Schema::create('payslips', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('payroll_run_id')->nullable();
                $table->unsignedBigInteger('employee_id')->nullable();
                $table->string('employee_name')->nullable();
                $table->date('pay_period_start')->nullable();
                $table->date('pay_period_end')->nullable();
                $table->date('pay_date')->nullable();
                $table->decimal('gross_pay', 15, 2)->default(0);
                $table->decimal('net_pay', 15, 2)->default(0);
                $table->decimal('total_deductions', 15, 2)->default(0);
                $table->string('status')->default('draft');
                $table->timestamps();
            });
        }

        if (! Schema::hasTable('payslip_lines')) {
            Schema::create('payslip_lines', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('payslip_id');
                $table->unsignedBigInteger('salary_component_id')->nullable();
                $table->string('component_code')->nullable();
                $table->string('component_name_en')->nullable();
                $table->string('component_name_ar')->nullable();
                $table->string('component_type');
                $table->string('calculation_mode')->nullable();
                $table->decimal('amount', 15, 2)->default(0);
                $table->string('formula')->nullable();
                $table->string('formula_used')->nullable();
                $table->integer('priority_order')->default(0);
                $table->boolean('include_in_gross')->default(false);
                $table->boolean('taxable')->default(false);
                $table->timestamps();
            });
        }
    }
}
