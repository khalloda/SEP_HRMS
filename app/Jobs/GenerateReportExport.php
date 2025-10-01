<?php

namespace App\Jobs;

use App\Services\Reports\ReportExportService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class GenerateReportExport implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public string $correlationId;
    public string $reportSlug;
    public array $filters;
    public string $format;

    public int $tries = 2;

    public function __construct(string $correlationId, string $reportSlug, array $filters, string $format)
    {
        $this->correlationId = $correlationId;
        $this->reportSlug = $reportSlug;
        $this->filters = $filters;
        $this->format = strtolower($format);
    }

    public function handle(ReportExportService $exportService): void
    {
        $exportService->handleQueued($this->correlationId, $this->reportSlug, $this->filters, $this->format);
    }

    public function failed(\Throwable $exception): void
    {
        app(ReportExportService::class)->markFailed($this->correlationId, $exception);
    }
}
