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


    public function test_lock_action_redirects_back_with_success_flash(): void
    {
        config()->set('payroll.enabled', true);

        $user = $this->makeHrAdminUser();

        $run = PayrollRun::create([
            'title' => 'Lockable Payroll',
            'pay_period_start' => now()->startOfMonth()->toDateString(),
            'pay_period_end' => now()->endOfMonth()->toDateString(),
            'pay_date' => now()->endOfMonth()->addDays(5)->toDateString(),
            'status' => PayrollRun::STATUS_CALCULATED,
            'currency' => 'SAR',
            'total_employees' => 2,
            'total_gross' => 20000,
            'total_net' => 18000,
            'total_deductions' => 2000,
            'created_by' => $user->id,
        ]);

        $response = $this->actingAs($user)->post(route('payroll.lock', $run));

        $response->assertRedirect(route('payroll.show', $run));
        $response->assertSessionHas('success', __('hrms.payroll.locked_successfully'));

        $run->refresh();

        $this->assertEquals(PayrollRun::STATUS_LOCKED, $run->status);
        $this->assertEquals($user->id, $run->locked_by);
    }

    public function test_unlock_action_redirects_back_with_success_flash(): void
    {
        config()->set('payroll.enabled', true);

        $user = $this->makeHrAdminUser();

        $run = PayrollRun::create([
            'title' => 'Unlockable Payroll',
            'pay_period_start' => now()->startOfMonth()->toDateString(),
            'pay_period_end' => now()->endOfMonth()->toDateString(),
            'pay_date' => now()->endOfMonth()->addDays(5)->toDateString(),
            'status' => PayrollRun::STATUS_LOCKED,
            'currency' => 'SAR',
            'locked_by' => $user->id,
            'locked_at' => now(),
            'total_employees' => 2,
            'total_gross' => 20000,
            'total_net' => 18000,
            'total_deductions' => 2000,
            'created_by' => $user->id,
        ]);

        $response = $this->actingAs($user)->post(route('payroll.unlock', $run));

        $response->assertRedirect(route('payroll.show', $run));
        $response->assertSessionHas('success', __('hrms.payroll.unlocked_successfully'));

        $run->refresh();

        $this->assertEquals(PayrollRun::STATUS_CALCULATED, $run->status);
        $this->assertNull($run->locked_by);
    }

    public function test_approve_action_redirects_back_with_success_flash(): void
    {
        config()->set('payroll.enabled', true);

        $user = $this->makeHrAdminUser();

        $run = PayrollRun::create([
            'title' => 'Approval Payroll',
            'pay_period_start' => now()->startOfMonth()->toDateString(),
            'pay_period_end' => now()->endOfMonth()->toDateString(),
            'pay_date' => now()->endOfMonth()->addDays(5)->toDateString(),
            'status' => PayrollRun::STATUS_PENDING_APPROVAL,
            'currency' => 'SAR',
            'locked_by' => $user->id,
            'locked_at' => now(),
            'total_employees' => 2,
            'total_gross' => 20000,
            'total_net' => 18000,
            'total_deductions' => 2000,
            'created_by' => $user->id,
        ]);

        $response = $this->actingAs($user)->post(route('payroll.approve', $run));

        $response->assertRedirect(route('payroll.show', $run));
        $response->assertSessionHas('success', __('hrms.payroll.approved_successfully'));

        $run->refresh();

        $this->assertEquals(PayrollRun::STATUS_APPROVED, $run->status);
        $this->assertEquals($user->id, $run->approved_by);
    }

    public function test_post_action_redirects_back_with_success_flash(): void
    {
        config()->set('payroll.enabled', true);

        $user = $this->makeHrAdminUser();

        $run = PayrollRun::create([
            'title' => 'Postable Payroll',
            'pay_period_start' => now()->startOfMonth()->toDateString(),
            'pay_period_end' => now()->endOfMonth()->toDateString(),
            'pay_date' => now()->endOfMonth()->addDays(5)->toDateString(),
            'status' => PayrollRun::STATUS_LOCKED,
            'currency' => 'SAR',
            'locked_by' => $user->id,
            'locked_at' => now(),
            'total_employees' => 2,
            'total_gross' => 20000,
            'total_net' => 18000,
            'total_deductions' => 2000,
            'created_by' => $user->id,
        ]);

        $response = $this->actingAs($user)->post(route('payroll.post', $run));

        $response->assertRedirect(route('payroll.show', $run));
        $response->assertSessionHas('success', __('hrms.payroll.posted_successfully'));

        $run->refresh();

        $this->assertEquals(PayrollRun::STATUS_POSTED, $run->status);
        $this->assertEquals($user->id, $run->posted_by);
    }

    public function test_cancel_action_redirects_back_with_success_flash(): void
    {
        config()->set('payroll.enabled', true);

        $user = $this->makeHrAdminUser();

        $run = PayrollRun::create([
            'title' => 'Cancelable Payroll',
            'pay_period_start' => now()->startOfMonth()->toDateString(),
            'pay_period_end' => now()->endOfMonth()->toDateString(),
            'pay_date' => now()->endOfMonth()->addDays(5)->toDateString(),
            'status' => PayrollRun::STATUS_DRAFT,
            'currency' => 'SAR',
            'total_employees' => 2,
            'total_gross' => 20000,
            'total_net' => 18000,
            'total_deductions' => 2000,
            'created_by' => $user->id,
        ]);

        $response = $this->actingAs($user)->post(route('payroll.cancel', $run), [
            'cancellation_reason' => 'Testing cancellation path',
        ]);

        $response->assertRedirect(route('payroll.show', $run));
        $response->assertSessionHas('success', __('hrms.payroll.cancelled_successfully'));

        $run->refresh();

        $this->assertEquals(PayrollRun::STATUS_CANCELLED, $run->status);
    }

    public function test_action_buttons_honor_policy_states(): void
    {
        config()->set('payroll.enabled', true);

        $user = $this->makeHrAdminUser();

        $draftRun = PayrollRun::create([
            'title' => 'Draft Payroll',
            'pay_period_start' => now()->startOfMonth()->toDateString(),
            'pay_period_end' => now()->endOfMonth()->toDateString(),
            'pay_date' => now()->endOfMonth()->addDays(5)->toDateString(),
            'status' => PayrollRun::STATUS_DRAFT,
            'currency' => 'SAR',
            'total_employees' => 1,
            'total_gross' => 10000,
            'total_net' => 8000,
            'total_deductions' => 2000,
            'created_by' => $user->id,
        ]);

        $calculatedRun = PayrollRun::create([
            'title' => 'Calculated Payroll',
            'pay_period_start' => now()->startOfMonth()->toDateString(),
            'pay_period_end' => now()->endOfMonth()->toDateString(),
            'pay_date' => now()->endOfMonth()->addDays(5)->toDateString(),
            'status' => PayrollRun::STATUS_CALCULATED,
            'currency' => 'SAR',
            'total_employees' => 1,
            'total_gross' => 12000,
            'total_net' => 9000,
            'total_deductions' => 3000,
            'created_by' => $user->id,
        ]);

        $lockedRun = PayrollRun::create([
            'title' => 'Locked Payroll',
            'pay_period_start' => now()->startOfMonth()->toDateString(),
            'pay_period_end' => now()->endOfMonth()->toDateString(),
            'pay_date' => now()->endOfMonth()->addDays(5)->toDateString(),
            'status' => PayrollRun::STATUS_LOCKED,
            'currency' => 'SAR',
            'locked_by' => $user->id,
            'locked_at' => now(),
            'total_employees' => 1,
            'total_gross' => 13000,
            'total_net' => 9500,
            'total_deductions' => 3500,
            'created_by' => $user->id,
        ]);

        $pendingApprovalRun = PayrollRun::create([
            'title' => 'Pending Approval Payroll',
            'pay_period_start' => now()->startOfMonth()->toDateString(),
            'pay_period_end' => now()->endOfMonth()->toDateString(),
            'pay_date' => now()->endOfMonth()->addDays(5)->toDateString(),
            'status' => PayrollRun::STATUS_PENDING_APPROVAL,
            'currency' => 'SAR',
            'locked_by' => $user->id,
            'locked_at' => now(),
            'total_employees' => 1,
            'total_gross' => 14000,
            'total_net' => 10000,
            'total_deductions' => 4000,
            'created_by' => $user->id,
        ]);

        $approvedRun = PayrollRun::create([
            'title' => 'Approved Payroll',
            'pay_period_start' => now()->startOfMonth()->toDateString(),
            'pay_period_end' => now()->endOfMonth()->toDateString(),
            'pay_date' => now()->endOfMonth()->addDays(5)->toDateString(),
            'status' => PayrollRun::STATUS_APPROVED,
            'currency' => 'SAR',
            'approved_by' => $user->id,
            'approved_at' => now(),
            'total_employees' => 1,
            'total_gross' => 15000,
            'total_net' => 11000,
            'total_deductions' => 4000,
            'created_by' => $user->id,
        ]);

        $this->actingAs($user)->get(route('payroll.show', $draftRun))
            ->assertSee("/payroll/{$draftRun->id}/calculate")
            ->assertDontSee("/payroll/{$draftRun->id}/lock");

        $this->actingAs($user)->get(route('payroll.show', $calculatedRun))
            ->assertSee("/payroll/{$calculatedRun->id}/lock")
            ->assertDontSee("/payroll/{$calculatedRun->id}/unlock");

        $this->actingAs($user)->get(route('payroll.show', $lockedRun))
            ->assertSee("/payroll/{$lockedRun->id}/unlock")
            ->assertSee("/payroll/{$lockedRun->id}/post");

        $this->actingAs($user)->get(route('payroll.show', $pendingApprovalRun))
            ->assertSee("/payroll/{$pendingApprovalRun->id}/approve")
            ->assertDontSee("/payroll/{$pendingApprovalRun->id}/unlock");

        $this->actingAs($user)->get(route('payroll.show', $approvedRun))
            ->assertSee("/payroll/{$approvedRun->id}/post")
            ->assertDontSee("/payroll/{$approvedRun->id}/approve");
    }

    public function test_cancelled_run_displays_cancellation_details(): void
    {
        config()->set('payroll.enabled', true);

        $admin = $this->makeHrAdminUser();

        $run = PayrollRun::create([
            'title' => 'Cancelled Payroll',
            'pay_period_start' => now()->startOfMonth()->toDateString(),
            'pay_period_end' => now()->endOfMonth()->toDateString(),
            'pay_date' => now()->endOfMonth()->addDays(5)->toDateString(),
            'status' => PayrollRun::STATUS_DRAFT,
            'currency' => 'SAR',
            'total_employees' => 1,
            'total_gross' => 1200,
            'total_net' => 900,
            'total_deductions' => 300,
            'created_by' => $admin->id,
        ]);

        $run->cancel($admin, 'Budget constraints');

        $response = $this->actingAs($admin)->get(route('payroll.show', $run));

        $response->assertOk();
        $response->assertSee('Cancellation Reason');
        $response->assertSee('Budget constraints');
        $response->assertSee('Status: Cancelled');
    }

    public function test_unauthorized_users_cannot_trigger_lifecycle_actions(): void
    {
        config()->set('payroll.enabled', true);

        $user = User::create([
            'name' => 'Coordinator',
            'email' => 'coordinator-' . Str::uuid() . '@example.com',
            'password' => bcrypt('password123'),
        ]);
        $user->assignRole('HR_Coordinator');

        $run = PayrollRun::create([
            'title' => 'Draft Payroll',
            'pay_period_start' => now()->startOfMonth()->toDateString(),
            'pay_period_end' => now()->endOfMonth()->toDateString(),
            'pay_date' => now()->endOfMonth()->addDays(5)->toDateString(),
            'status' => PayrollRun::STATUS_DRAFT,
            'currency' => 'SAR',
            'total_employees' => 1,
            'total_gross' => 1000,
            'total_net' => 800,
            'total_deductions' => 200,
            'created_by' => $user->id,
        ]);

        $this->actingAs($user)->post(route('payroll.lock', $run))->assertForbidden();
        $this->actingAs($user)->post(route('payroll.approve', $run))->assertForbidden();
        $this->actingAs($user)->post(route('payroll.post', $run))->assertForbidden();
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
    private function makeHrAdminUser(): User
    {
        $user = User::create([
            'name' => 'Payroll Admin',
            'email' => 'payroll-admin-' . Str::uuid() . '@example.com',
            'password' => bcrypt('password123'),
        ]);

        $user->assignRole('HR_Admin_Manager');

        return $user;
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
