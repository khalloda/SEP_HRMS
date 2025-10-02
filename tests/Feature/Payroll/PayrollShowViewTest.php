<?php

namespace Tests\Feature\Payroll;

use App\Models\PayrollRun;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class PayrollShowViewTest extends TestCase
{
    use DatabaseTransactions;

    protected function setUp(): void
    {
        parent::setUp();

        Role::firstOrCreate(['name' => 'HR_Admin_Manager', 'guard_name' => 'web']);
        $this->ensurePayrollSchema();
    }

    public function test_show_page_renders_payroll_details(): void
    {
        config()->set('payroll.enabled', true);

        $user = User::create([
            'name' => 'Payroll Admin',
            'email' => 'payroll-admin-' . Str::uuid() . '@example.com',
            'password' => bcrypt('password123'),
        ]);
        $user->assignRole('HR_Admin_Manager');

        $run = PayrollRun::create([
            'title' => 'Demo Payroll',
            'description' => 'Automated test payroll run.',
            'pay_period_start' => now()->startOfMonth()->toDateString(),
            'pay_period_end' => now()->endOfMonth()->toDateString(),
            'pay_date' => now()->endOfMonth()->addDays(5)->toDateString(),
            'status' => PayrollRun::STATUS_CALCULATED,
            'currency' => 'SAR',
            'total_employees' => 3,
            'total_gross' => 30000,
            'total_net' => 25000,
            'total_deductions' => 5000,
            'created_by' => $user->id,
        ]);

        $response = $this->actingAs($user)->get(route('payroll.show', $run));

        if ($response->exception) {
            throw $response->exception;
        }

        $response->assertOk();
        $response->assertSee('Demo Payroll');
        $response->assertSee('Total Gross');
        $response->assertSee(number_format($run->total_net, 2));
    }

    public function test_show_page_respects_feature_flag(): void
    {
        config()->set('payroll.enabled', false);

        $user = User::create([
            'name' => 'Payroll Admin',
            'email' => 'payroll-admin-' . Str::uuid() . '@example.com',
            'password' => bcrypt('password123'),
        ]);
        $user->assignRole('HR_Admin_Manager');

        $run = PayrollRun::create([
            'title' => 'Draft Payroll',
            'pay_period_start' => now()->startOfMonth()->toDateString(),
            'pay_period_end' => now()->endOfMonth()->toDateString(),
            'pay_date' => now()->endOfMonth()->addDays(5)->toDateString(),
            'status' => PayrollRun::STATUS_DRAFT,
            'currency' => 'SAR',
            'total_employees' => 0,
            'total_gross' => 0,
            'total_net' => 0,
            'total_deductions' => 0,
            'created_by' => $user->id,
        ]);

        $this->actingAs($user)
            ->get(route('payroll.show', $run))
            ->assertNotFound();
    }
    protected function ensurePayrollSchema(): void
    {
        if (! Schema::hasColumn('payroll_runs', 'currency')) {
            Schema::table('payroll_runs', function (Blueprint $table) {
                $table->string('currency', 3)->default('SAR');
            });
        }

        if (! Schema::hasColumn('payroll_runs', 'total_employees')) {
            Schema::table('payroll_runs', function (Blueprint $table) {
                $table->integer('total_employees')->default(0);
            });
        }

        foreach (['total_gross', 'total_net', 'total_deductions'] as $column) {
            if (! Schema::hasColumn('payroll_runs', $column)) {
                Schema::table('payroll_runs', function (Blueprint $table) use ($column) {
                    $table->decimal($column, 15, 2)->default(0);
                });
            }
        }
    }

}