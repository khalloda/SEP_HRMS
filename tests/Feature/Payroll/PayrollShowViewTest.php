<?php

namespace Tests\Feature\Payroll;

use App\Models\PayrollRun;
use App\Models\User;
use App\Services\PayrollCalculationService;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Spatie\Permission\Models\Role;
use Mockery;
use Tests\TestCase;

class PayrollShowViewTest extends TestCase
{
    use DatabaseTransactions;

    protected $calculationService;

    protected function setUp(): void
    {
        parent::setUp();

        Role::firstOrCreate(['name' => 'HR_Admin_Manager', 'guard_name' => 'web']);
        $this->ensurePayrollSchema();

        $this->calculationService = Mockery::mock(PayrollCalculationService::class);
        $this->calculationService->shouldReceive('getCalculationSummary')
            ->byDefault()
            ->andReturn([
                'total_employees' => 0,
                'total_gross' => 0,
                'total_net' => 0,
                'total_deductions' => 0,
                'by_department' => [],
                'earnings_breakdown' => [],
                'deductions_breakdown' => [],
            ]);
        $this->calculationService->shouldReceive('validatePayrollRun')
            ->byDefault()
            ->andReturn([]);

        $this->app->instance(PayrollCalculationService::class, $this->calculationService);
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

        self::assertNotNull($run->id);

        $response = $this->actingAs($user)->get(route('payroll.show', $run));

        if ($response->exception) {
            throw $response->exception;
        }

        $response->assertOk();
        $response->assertSee('Demo Payroll');
        $response->assertSee('Total Gross');
        $response->assertSee(number_format($run->total_net, 2));
    }

    public function test_show_page_merges_validation_issues_from_session(): void
    {
        config()->set('payroll.enabled', true);

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

        $this->calculationService->shouldReceive('validatePayrollRun')
            ->once()
            ->with(Mockery::on(fn ($payload) => $payload->is($run)))
            ->andReturn(['Employee missing bank account']);

        $response = $this->actingAs($user)
            ->withSession([
                'validation_issues' => [
                    'Employee missing bank account',
                    'Employee missing bank account',
                    'Attendance incomplete',
                ],
            ])
            ->get(route('payroll.show', $run));

        $response->assertOk();
        $response->assertSee('Validation Issues Detected');
        $response->assertSee('Employee missing bank account');
        $response->assertSee('Attendance incomplete');

        $this->assertSame(1, substr_count($response->getContent(), 'Employee missing bank account'));
    }

    public function test_show_page_displays_correlation_reference(): void
    {
        config()->set('payroll.enabled', true);

        $user = User::create([
            'name' => 'Payroll Admin',
            'email' => 'payroll-admin-' . Str::uuid() . '@example.com',
            'password' => bcrypt('password123'),
        ]);
        $user->assignRole('HR_Admin_Manager');

        $run = PayrollRun::create([
            'title' => 'Correlation Test Payroll',
            'pay_period_start' => now()->startOfMonth()->toDateString(),
            'pay_period_end' => now()->endOfMonth()->toDateString(),
            'pay_date' => now()->endOfMonth()->addDays(5)->toDateString(),
            'status' => PayrollRun::STATUS_CALCULATED,
            'currency' => 'SAR',
            'total_employees' => 5,
            'total_gross' => 50000,
            'total_net' => 42000,
            'total_deductions' => 8000,
            'created_by' => $user->id,
        ]);

        $response = $this->actingAs($user)
            ->withSession([
                'calculation_reference' => 'ABC-123',
                'calculation_results' => [
                    'employees_processed' => 5,
                    'payslips_created' => 5,
                ],
            ])
            ->get(route('payroll.show', $run));

        $response->assertOk();
        $response->assertSee('Correlation ID');
        $response->assertSee('ABC-123');
        $response->assertSee('Employees processed');
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
