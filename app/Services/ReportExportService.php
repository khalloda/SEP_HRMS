<?php

namespace App\Services;

use App\Exports\GenericReportExport;
use App\Jobs\GenerateReportExport;
use App\Models\Employee;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Facades\Excel;
use Mpdf\Mpdf;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class ReportExportService
{
    public const STATUS_QUEUED = 'queued';
    public const STATUS_PROCESSING = 'processing';
    public const STATUS_COMPLETED = 'completed';
    public const STATUS_FAILED = 'failed';

    public function start(string $slug, array $filters, string $format, Authenticatable $user)
    {
        $definition = $this->definition($slug);
        abort_unless($definition, 404, __('Selected report is not available.'));
        abort_if(!$user->can($definition['permission']), 403);

        $format = strtolower($format);
        abort_unless(in_array($format, $definition['formats'], true), 422, __('Unsupported export format.'));

        $correlationId = (string) Str::uuid();
        $filename = $this->buildFilename($slug, $filters, $format);

        $state = [
            'correlation_id' => $correlationId,
            'report_slug' => $slug,
            'report_name' => $definition['name'],
            'status' => self::STATUS_QUEUED,
            'format' => $format,
            'requested_by' => $user->id,
            'queued_at' => now()->toIso8601String(),
            'filename' => $filename,
            'download_path' => null,
            'progress' => 0,
            'total_rows' => null,
            'processed_rows' => 0,
        ];

        Cache::put($this->cacheKey($correlationId), $state, $this->ttl());

        Log::withContext(['correlation_id' => $correlationId])
            ->info('Report export queued.', ['report' => $slug]);

        GenerateReportExport::dispatch($correlationId, $slug, $filters, $format)
            ->onConnection(Config::get('reports.queue_connection'))
            ->onQueue(Config::get('reports.queue_name'));

        return $state;
    }

    public function runQueued(string $correlationId, string $slug, array $filters, string $format): void
    {
        $definition = $this->definition($slug);
        abort_unless($definition, 404);

        $this->updateState($correlationId, function (array $state) use ($slug) {
            $state['status'] = self::STATUS_PROCESSING;
            $state['started_at'] = now()->toIso8601String();

            Log::withContext(['correlation_id' => $state['correlation_id']])
                ->info('Report export processing started.', ['report' => $slug]);

            return $state;
        });

        try {
            $payload = $this->fetchRows($slug, $filters);
            $totalRows = $payload['rows']->count();

            $this->updateState($correlationId, function (array $state) use ($totalRows) {
                $state['total_rows'] = $totalRows;
                return $state;
            });

            $path = $this->storeExport($correlationId, $payload, $format);

            $this->updateState($correlationId, function (array $state) use ($path) {
                $state['status'] = self::STATUS_COMPLETED;
                $state['download_path'] = $path;
                $state['progress'] = 100;
                $state['processed_rows'] = $state['total_rows'];
                $state['completed_at'] = now()->toIso8601String();
                return $state;
            });

            Log::withContext(['correlation_id' => $correlationId])
                ->info('Report export completed.');
        } catch (\Throwable $exception) {
            $this->markFailed($correlationId, $exception);
            throw $exception;
        }
    }

    public function download(string $correlationId): BinaryFileResponse|StreamedResponse
    {
        $state = $this->getStatus($correlationId);
        abort_unless(($state['status'] ?? null) === self::STATUS_COMPLETED, 404);

        $path = $state['download_path'] ?? null;
        abort_unless($path && Storage::disk('private')->exists($path), 404);

        return Storage::disk('private')->download($path, $state['filename'] ?? basename($path));
    }

    public function markFailed(string $correlationId, \Throwable $exception): void
    {
        $this->updateState($correlationId, function (array $state) use ($exception) {
            $state['status'] = self::STATUS_FAILED;
            $state['message'] = $exception->getMessage();
            $state['failed_at'] = now()->toIso8601String();
            return $state;
        });

        Log::withContext(['correlation_id' => $correlationId])
            ->error('Report export failed.', ['exception' => $exception]);
    }

    public function getStatus(string $correlationId): ?array
    {
        return Cache::get($this->cacheKey($correlationId));
    }

    protected function storeExport(string $correlationId, array $payload, string $format): string
    {
        $path = 'report-exports/' . $correlationId . '/report.' . $format;
        Storage::disk('private')->makeDirectory(dirname($path));

        if ($format === 'excel') {
            $export = new GenericReportExport(function () use ($payload) {
                foreach ($payload['rows'] as $row) {
                    yield array_values($row);
                }
            }, $payload['headings']);

            Excel::store($export, $path, 'private');
        } else {
            $mpdf = new Mpdf([
                'mode' => 'utf-8',
                'format' => 'A4',
                'margin_top' => 15,
                'margin_left' => 12,
                'margin_right' => 12,
                'tempDir' => storage_path('app/tmp'),
            ]);

            $mpdf->WriteHTML(view('reports.exports.employee-list-pdf', [
                'data' => $payload['rows'],
                'generatedAt' => now(),
            ])->render());

            Storage::disk('private')->put($path, $mpdf->Output('', 'S'));
        }

        return $path;
    }

    protected function fetchRows(string $slug, array $filters): array
    {
        if ($slug !== 'employee-list') {
            throw new \InvalidArgumentException('Unsupported report: ' . $slug);
        }

        $query = Employee::query()
            ->with(['department', 'position', 'manager'])
            ->when($filters['department_id'] ?? null, fn (Builder $builder, $department) => $builder->where('department_id', $department))
            ->when($filters['position_id'] ?? null, fn (Builder $builder, $position) => $builder->where('position_id', $position));

        $rows = $query
            ->orderBy('department_id')
            ->orderBy('last_name')
            ->get()
            ->map(function (Employee $employee) {
                return [
                    'Employee Code' => $employee->code,
                    'Full Name' => $employee->display_name,
                    'Department' => $employee->department->name_en ?? '-',
                    'Position' => $employee->position->name_en ?? '-',
                    'Employment Status' => ucfirst($employee->employment_status ?? $employee->status ?? ''),
                    'Hire Date' => optional($employee->hire_date)->format('Y-m-d') ?? '-',
                    'Manager' => $employee->manager->display_name ?? __('Unassigned'),
                    'Work Email' => $employee->email ?? __('N/A'),
                    'Phone' => $employee->phone ?? __('N/A'),
                ];
            });

        return [
            'headings' => [
                __('Employee Code'),
                __('Full Name'),
                __('Department'),
                __('Position'),
                __('Employment Status'),
                __('Hire Date'),
                __('Manager'),
                __('Work Email'),
                __('Phone'),
            ],
            'rows' => $rows,
        ];
    }

    protected function buildFilename(string $slug, array $filters, string $format): string
    {
        $suffix = now()->format('Ymd_His');
        return $slug . '_' . $suffix . '.' . $format;
    }

    protected function cacheKey(string $correlationId): string
    {
        return 'report_exports:' . $correlationId;
    }

    protected function ttl(): int
    {
        return (int) Config::get('reports.cache_ttl', 3600);
    }

    protected function definition(string $slug): ?array
    {
        return Config::get('reports.definitions.' . $slug);
    }
}

