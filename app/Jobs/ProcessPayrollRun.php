<?php

namespace App\Jobs;

use App\Models\PayrollRun;
use App\Services\PayrollCalculationService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use App\Support\CorrelationIdManager;

class ProcessPayrollRun implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $payrollRunId;

    public string $correlationId;

    public function __construct(int $payrollRunId, ?string $correlationId = null)
    {
        $this->payrollRunId = $payrollRunId;
        $this->correlationId = $correlationId ?? (string) Str::uuid();

        $connection = config('payroll.queue_connection', config('queue.default'));

        if ($connection) {
            $this->onConnection($connection);
        }

        $queue = config('payroll.queue_name');

        if ($queue) {
            $this->onQueue($queue);
        }
    }

    public function handle(PayrollCalculationService $calculationService): void
    {
        $correlationIds = app(CorrelationIdManager::class);
        $correlationId = $correlationIds->set($this->correlationId);

        $payrollRun = PayrollRun::find($this->payrollRunId);

        if (!$payrollRun) {
            Log::warning('Attempted to process missing payroll run', [
                'payroll_run_id' => $this->payrollRunId,
                'correlation_id' => $correlationId,
            ]);

            return;
        }

        Log::withContext([
            'correlation_id' => $correlationId,
            'payroll_run_id' => $payrollRun->id,
        ]);

        $calculationService->calculatePayrollRun($payrollRun, [
            'queued' => true,
            'correlation_id' => $correlationId,
        ]);
    }
}
