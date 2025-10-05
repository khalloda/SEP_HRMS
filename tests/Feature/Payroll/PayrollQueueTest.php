<?php

namespace Tests\Feature\Payroll;

use App\Jobs\ProcessPayrollRun;
use App\Models\PayrollRun;
use App\Models\User;
use App\Services\PayrollCalculationService;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\Bus;
use Illuminate\Support\Facades\Config;
use Mockery;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class PayrollQueueTest extends TestCase
{
    use DatabaseTransactions;

    protected function tearDown(): void
    {
        Mockery::close();

        parent::tearDown();
    }

    public function test_calculate_action_dispatches_job_when_queue_enabled(): void
    {
        Config::set('payroll.enabled', true);
        Config::set('payroll.queue_enabled', true);

        Bus::fake();

        Role::firstOrCreate(['name' => 'HR_Admin_Manager', 'guard_name' => 'web']);

        $user = User::create([
            'name' => 'Queue Runner',
            'email' => 'queue-runner-' . uniqid() . '@example.com',
            'password' => bcrypt('password123'),
        ]);
        $user->assignRole('HR_Admin_Manager');

        $payrollRun = PayrollRun::create([
            'title' => 'Queued Run',
            'status' => PayrollRun::STATUS_DRAFT,
            'pay_period_start' => Carbon::parse('2025-09-01'),
            'pay_period_end' => Carbon::parse('2025-09-30'),
            'pay_date' => Carbon::parse('2025-10-05'),
            'currency' => 'USD',
            'created_by' => $user->id,
        ]);

        $this->withoutExceptionHandling();

        $serviceMock = Mockery::mock(PayrollCalculationService::class);
        $serviceMock->shouldReceive('validatePayrollRun')->once()->with(Mockery::type(PayrollRun::class))->andReturn([]);
        $serviceMock->shouldReceive('calculatePayrollRun')->never();

        $this->app->instance(PayrollCalculationService::class, $serviceMock);

        $response = $this->actingAs($user)->post(route('payroll.calculate', $payrollRun));

        $response->assertRedirect(route('payroll.show', $payrollRun));
        $response->assertSessionHas('success', 'Payroll run queued for processing.');
        $response->assertSessionHas('calculation_reference');
        $response->assertSessionHas('calculation_job.correlation_id');
        $response->assertSessionHas('calculation_job.payroll_run_id', $payrollRun->id);

        Bus::assertDispatched(ProcessPayrollRun::class, function (ProcessPayrollRun $job) use ($payrollRun) {
            return $job->payrollRunId === $payrollRun->id && !empty($job->correlationId);
        });
    }
    public function test_calculate_action_returns_reference_when_processed_inline(): void
    {
        Config::set('payroll.enabled', true);
        Config::set('payroll.queue_enabled', false);

        Role::firstOrCreate(['name' => 'HR_Admin_Manager', 'guard_name' => 'web']);

        $user = User::create([
            'name' => 'Inline Runner',
            'email' => 'inline-runner-' . uniqid() . '@example.com',
            'password' => bcrypt('password123'),
        ]);
        $user->assignRole('HR_Admin_Manager');

        $payrollRun = PayrollRun::create([
            'title' => 'Inline Run',
            'status' => PayrollRun::STATUS_DRAFT,
            'pay_period_start' => Carbon::parse('2025-09-01'),
            'pay_period_end' => Carbon::parse('2025-09-30'),
            'pay_date' => Carbon::parse('2025-10-05'),
            'currency' => 'USD',
            'created_by' => $user->id,
        ]);

        $capturedContext = null;

        $serviceMock = Mockery::mock(PayrollCalculationService::class);
        $serviceMock->shouldReceive('validatePayrollRun')->once()->with(Mockery::type(PayrollRun::class))->andReturn([]);
        $serviceMock->shouldReceive('calculatePayrollRun')
            ->once()
            ->with(Mockery::type(PayrollRun::class), Mockery::on(function (array $context) use (&$capturedContext) {
                $this->assertArrayHasKey('correlation_id', $context);
                $this->assertNotEmpty($context['correlation_id']);
                $capturedContext = $context;

                return true;
            }))
            ->andReturn([
                'success' => true,
                'employees_processed' => 3,
                'payslips_created' => 3,
                'errors' => [],
            ]);

        $this->app->instance(PayrollCalculationService::class, $serviceMock);

        $response = $this->actingAs($user)->post(route('payroll.calculate', $payrollRun));

        $response->assertRedirect(route('payroll.show', $payrollRun));
        $response->assertSessionHas('success');
        $response->assertSessionHas('calculation_reference');
        $response->assertSessionHas('calculation_results');

        $reference = session('calculation_reference');

        $this->assertIsString($reference);
        $this->assertNotEmpty($reference);
        $this->assertSame($reference, $capturedContext['correlation_id']);
    }

}
