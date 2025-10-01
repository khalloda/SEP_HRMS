<?php

namespace App\Services\Reports;

use App\Exports\GenericReportExport;
use App\Jobs\GenerateReportExport;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Facades\Excel;
use Mpdf\Mpdf;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ReportExportService
{
    public const STATUS_QUEUED = 'queued';
    public const STATUS_PROCESSING = 'processing';
    public const STATUS_COMPLETED = 'completed';
    public const STATUS_FAILED = 'failed';

    public function start(string $reportSlug, array $filters, string $format, Authenticatable $user)
    {
        $definition = $this->definition($reportSlug);
        abort_unless($definition, 404, __('Report definition not found.'));

        $format = strtolower($format);
        abort_unless(in_array($format, $definition['formats'], true), 422, __('Unsupported export format.'));

        $adapterClass = $definition['adapter'];
        $adapterClass::authorize($user);

        $totalRows = $adapterClass::totalRows($filters);
        $threshold = $definition['queue_threshold'] ?? 0;

        if (!$this->shouldQueue($totalRows, $threshold)) {
            return $this->generateSync($adapterClass, $filters, $format);
        }

        return $this->queueExport($reportSlug, $definition, $adapterClass, $filters, $format, $totalRows, $user);
    }

    public function definition(string $slug): ?array
    {
        return config('reports.definitions.' . $slug);
    }

    public function getStatus(string $correlationId): ?array
    {
        return Cache::get($this->cacheKey($correlationId));
    }

    public function markFailed(string $correlationId, \Throwable $exception): void
    {
        $this->updateState($correlationId, function (array $state) use ($exception) {
            $state['status'] = self::STATUS_FAILED;
            $state['message'] = $exception->getMessage();
            $state['completed_at'] = now()->toIso8601String();

            return $state;
        });

        $state = $this->getStatus($correlationId);

        Log::withContext([
            'correlation_id' => $correlationId,
            'report_key' => $state['report_key'] ?? null,
            'report_name' => $state['report_name'] ?? null,
        ])->error('Report export failed.', [
            'exception' => $exception,
        ]);
    }

    public function handleQueued(string $correlationId, string $reportSlug, array $filters, string $format): void
    {
        $definition = $this->definition($reportSlug);
        abort_unless($definition, 404);

        $adapterClass = $definition['adapter'];

        $this->updateState($correlationId, function (array $state) use ($definition, $correlationId) {
            $state['status'] = self::STATUS_PROCESSING;
            $state['started_at'] = now()->toIso8601String();

            Log::withContext([
                'correlation_id' => $state['correlation_id'] ?? $correlationId,
                'report_key' => $definition['key'] ?? $definition['adapter'],
                'report_name' => $definition['name'] ?? $definition['adapter'],
            ])->info('Report export processing started.');

            return $state;
        });

        try {
            $filename = $adapterClass::filename($filters, $format);
            $path = $this->resolveStoragePath($correlationId, $filename);

            if ($format === 'excel') {
                $this->generateExcel($path, $adapterClass, $filters, $correlationId);
            } else {
                $this->generatePdf($path, $adapterClass, $filters);
                $this->updateProgress($correlationId, 100, 100);
            }

            $this->updateState($correlationId, function (array $state) use ($path, $filename) {
                $state['status'] = self::STATUS_COMPLETED;
                $state['download_path'] = $path;
                $state['filename'] = $filename;
                $state['completed_at'] = now()->toIso8601String();
                $state['progress'] = 100;
                $state['processed_rows'] = $state['total_rows'];

                Log::withContext([
                    'correlation_id' => $state['correlation_id'],
                    'report_key' => $state['report_key'] ?? $state['report_slug'],
                    'report_name' => $state['report_name'] ?? $state['report_slug'],
                ])->info('Report export completed successfully.');

                return $state;
            });
        } catch (\Throwable $exception) {
            $this->markFailed($correlationId, $exception);
            throw $exception;
        }
    }

    public function download(string $correlationId): BinaryFileResponse|StreamedResponse
    {
        $state = $this->getStatus($correlationId);
        abort_unless($state && ($state['status'] ?? null) === self::STATUS_COMPLETED, 404);
        $path = $state['download_path'] ?? null;
        abort_unless($path && Storage::disk('private')->exists($path), 404);

        return Storage::disk('private')->download($path, $state['filename'] ?? basename($path));
    }

    protected function generateSync(string $adapterClass, array $filters, string $format)
    {
        $rowsCollection = $adapterClass::rows($filters);
        $rowsArray = $rowsCollection instanceof \Illuminate\Support\LazyCollection
            ? $rowsCollection->toArray()
            : (array) $rowsCollection;

        $headings = $adapterClass::headings();

        if ($format === 'excel') {
            $exportRows = array_map('array_values', $rowsArray);
            $export = new GenericReportExport(function () use ($exportRows) {
                return $exportRows;
            }, $headings);

            $filename = $adapterClass::filename($filters, 'xlsx');

            return Excel::download($export, $filename);
        }

        $mpdf = $this->makePdfInstance();
        $context = $adapterClass::pdfContext($filters);
        $context['headings'] = $headings;
        $context['title'] = $adapterClass::title();

        $mpdf->WriteHTML(view($adapterClass::pdfView(), $context)->render());

        $filename = $adapterClass::filename($filters, 'pdf');

        return response($mpdf->Output('', 'S'), 200, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ]);
    }

    protected function queueExport(string $slug, array $definition, string $adapterClass, array $filters, string $format, int $totalRows, Authenticatable $user): array
    {
        $correlationId = (string) Str::uuid();
        $filename = $adapterClass::filename($filters, $format);

        $state = [
            'correlation_id' => $correlationId,
            'report_slug' => $slug,
            'report_key' => $definition['key'] ?? $slug,
            'report_name' => $definition['name'] ?? Str::headline($slug),
            'format' => $format,
            'status' => self::STATUS_QUEUED,
            'progress' => 0,
            'total_rows' => $totalRows,
            'processed_rows' => 0,
            'queued_at' => now()->toIso8601String(),
            'requested_by' => $user->id,
            'filename' => $filename,
            'download_path' => null,
            'message' => null,
        ];

        Cache::put($this->cacheKey($correlationId), $state, $this->ttl());

        Log::withContext([
            'correlation_id' => $correlationId,
            'report_key' => $state['report_key'],
            'report_name' => $state['report_name'] ?? $state['report_slug'],
        ])->info('Report export queued.');

        GenerateReportExport::dispatch($correlationId, $slug, $filters, $format)
            ->onConnection(config('reports.queue_connection'))
            ->onQueue(config('reports.queue_name'));

        return $state;
    }

    protected function shouldQueue(int $totalRows, int $threshold): bool
    {
        if (config('reports.force_queue')) {
            return true;
        }

        return $totalRows > $threshold;
    }

    protected function generateExcel(string $path, string $adapterClass, array $filters, string $correlationId): void
    {
        $headings = $adapterClass::headings();
        $total = max(1, $adapterClass::totalRows($filters));

        $rowsGenerator = function () use ($adapterClass, $filters, $total, $correlationId) {
            $processed = 0;
            foreach ($adapterClass::rows($filters) as $row) {
                $processed++;
                if ($processed % 50 === 0 || $processed === $total) {
                    $this->updateProgress($correlationId, $processed, $total);
                }
                yield array_values($row);
            }
        };

        $export = new GenericReportExport($rowsGenerator, $headings);

        Storage::disk('private')->makeDirectory(dirname($path));
        Excel::store($export, $path, 'private');
    }

    protected function generatePdf(string $path, string $adapterClass, array $filters): void
    {
        $mpdf = $this->makePdfInstance();
        $context = $adapterClass::pdfContext($filters);
        $context['title'] = $adapterClass::title();
        $context['headings'] = $adapterClass::headings();

        $mpdf->WriteHTML(view($adapterClass::pdfView(), $context)->render());

        Storage::disk('private')->makeDirectory(dirname($path));
        Storage::disk('private')->put($path, $mpdf->Output('', 'S'));
    }

    protected function updateProgress(string $correlationId, int $processed, int $total): void
    {
        $percent = min(100, round(($processed / max(1, $total)) * 100, 1));
        $this->updateState($correlationId, function (array $state) use ($processed, $total, $percent) {
            $state['processed_rows'] = $processed;
            $state['total_rows'] = $total;
            $state['progress'] = $percent;

            return $state;
        });
    }

    protected function makePdfInstance(): Mpdf
    {
        return new Mpdf([
            'mode' => 'utf-8',
            'format' => 'A4',
            'margin_top' => 12,
            'margin_bottom' => 12,
            'margin_left' => 10,
            'margin_right' => 10,
            'tempDir' => storage_path('app/tmp'),
        ]);
    }

    protected function resolveStoragePath(string $correlationId, string $filename): string
    {
        return 'reports/exports/' . $correlationId . '/' . $filename;
    }

    protected function updateState(string $correlationId, callable $callback): array
    {
        $key = $this->cacheKey($correlationId);
        $state = Cache::get($key, [
            'correlation_id' => $correlationId,
            'status' => self::STATUS_QUEUED,
            'progress' => 0,
            'total_rows' => 0,
            'processed_rows' => 0,
            'report_slug' => null,
            'report_key' => null,
            'report_name' => null,
            'filename' => null,
            'download_path' => null,
        ]);

        $state = $callback($state) ?? $state;

        Cache::put($key, $state, $this->ttl());

        return $state;
    }

    protected function cacheKey(string $correlationId): string
    {
        return 'reports:export:' . $correlationId;
    }

    protected function ttl(): int
    {
        return (int) config('reports.cache_ttl', 3600);
    }
}



