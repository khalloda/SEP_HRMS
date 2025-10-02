<?php

namespace Tests\Unit\Payroll;

use App\Models\PayrollRun;
use App\Services\Payroll\ExpressionEvaluator;
use App\Services\PayrollCalculationService;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Config;
use Tests\TestCase;

class PayrollChunkingTest extends TestCase
{
    use DatabaseTransactions;

    public function test_calculate_payroll_run_processes_employees_in_configured_chunks(): void
    {
        Config::set('payroll.enabled', true);
        Config::set('payroll.queue_enabled', false);
        Config::set('payroll.chunk_size', 2);

        $user = User::create([
            'name' => 'Chunk Owner',
            'email' => 'chunk-owner-' . uniqid() . '@example.com',
            'password' => bcrypt('password123'),
        ]);

        $payrollRun = PayrollRun::create([
            'title' => 'Chunking Run',
            'status' => PayrollRun::STATUS_DRAFT,
            'pay_period_start' => Carbon::parse('2025-09-01'),
            'pay_period_end' => Carbon::parse('2025-09-30'),
            'pay_date' => Carbon::parse('2025-10-05'),
            'currency' => 'USD',
            'created_by' => $user->id,
        ]);

        $service = new class(app(ExpressionEvaluator::class)) extends PayrollCalculationService {
            /** @var array<int, array<int, mixed>> */
            public array $chunks = [];

            protected function getEligibleEmployees(PayrollRun $payrollRun): Collection
            {
                return collect([1, 2, 3, 4, 5]);
            }

            protected function processEmployeeChunk(PayrollRun $payrollRun, Collection $employees, array $context): array
            {
                $this->chunks[] = $employees->values()->all();

                return [
                    'success' => true,
                    'employees_processed' => $employees->count(),
                    'payslips_created' => $employees->count(),
                    'errors' => [],
                ];
            }
        };

        $result = $service->calculatePayrollRun($payrollRun);

        $this->assertSame([[1, 2], [3, 4], [5]], $service->chunks);
        $this->assertTrue($result['success']);
        $this->assertSame(5, $result['employees_processed']);
        $this->assertSame(5, $result['payslips_created']);
        $this->assertEmpty($result['errors']);
        $this->assertSame(PayrollRun::STATUS_CALCULATED, $payrollRun->fresh()->status);
    }
}
