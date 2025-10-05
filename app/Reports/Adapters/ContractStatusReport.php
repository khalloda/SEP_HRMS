<?php

namespace App\Reports\Adapters;

use App\Models\Contract;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\LazyCollection;

class ContractStatusReport
{
    public static function validationRules(): array
    {
        return [
            'status' => ['nullable', 'in:active,expired,terminated'],
            'contract_type' => ['nullable', 'in:permanent,fixed_term,probation,internship,consultancy'],
            'export_format' => ['required', 'in:excel,pdf'],
        ];
    }

    public static function authorize(?Authenticatable $user): void
    {
        abort_if(! $user || ! $user->can('reports.view'), 403);
    }

    public static function totalRows(array $filters): int
    {
        return self::baseQuery($filters)->count();
    }

    public static function rows(array $filters): LazyCollection
    {
        return self::baseQuery($filters)
            ->lazy()
            ->map(fn (Contract $contract) => self::mapContractRow($contract));
    }

    public static function headings(): array
    {
        return [
            __('Employee'),
            __('Department'),
            __('Contract Type'),
            __('Status'),
            __('Start Date'),
            __('End Date'),
            __('Days Until Expiry'),
        ];
    }

    public static function title(): string
    {
        return __('Contract Status Report');
    }

    public static function filename(array $filters, string $format): string
    {
        $suffix = now()->format('Ymd_His');
        $extension = match (strtolower($format)) {
            'excel' => 'xlsx',
            'pdf' => 'pdf',
            default => trim(strtolower($format), '.'),
        };

        return "contract-status-{$suffix}.{$extension}";
    }

    public static function pdfView(): string
    {
        return 'reports.exports.contract-status-pdf';
    }

    public static function pdfContext(array $filters): array
    {
        $contracts = self::baseQuery($filters)->get();

        return [
            'data' => $contracts->map(fn (Contract $contract) => self::mapContractRow($contract))->all(),
            'summary' => self::summary(),
            'filters' => $filters,
            'generatedAt' => now(),
        ];
    }

    protected static function baseQuery(array $filters): Builder
    {
        return Contract::query()
            ->with(['employee.department'])
            ->when($filters['status'] ?? null, fn (Builder $query, string $status) => $query->where('status', $status))
            ->when($filters['contract_type'] ?? null, fn (Builder $query, string $type) => $query->where('type', $type))
            ->orderBy('end_date');
    }

    public static function summary(array $filters = []): array
    {
        return [
            'total' => Contract::count(),
            'by_status' => Contract::select('status', DB::raw('count(*) as count'))
                ->groupBy('status')
                ->pluck('count', 'status')
                ->all(),
            'by_type' => Contract::select('type', DB::raw('count(*) as count'))
                ->groupBy('type')
                ->pluck('count', 'type')
                ->all(),
            'expiring_soon' => Contract::where('status', 'active')
                ->where('end_date', '<=', now()->addDays(30))
                ->count(),
        ];
    }

    protected static function mapContractRow(Contract $contract): array
    {
        $daysUntilExpiry = $contract->end_date ? now()->diffInDays($contract->end_date, false) : null;

        return [
            'Employee' => $contract->employee->display_name,
            'Department' => $contract->employee->department->name_en ?? '-',
            'Contract Type' => ucfirst(str_replace('_', ' ', $contract->type ?? '')),
            'Status' => ucfirst($contract->status ?? ''),
            'Start Date' => optional($contract->start_date)->format('Y-m-d') ?? '-',
            'End Date' => optional($contract->end_date)->format('Y-m-d') ?? 'N/A',
            'Days Until Expiry' => $daysUntilExpiry ?? 'N/A',
        ];
    }
}
