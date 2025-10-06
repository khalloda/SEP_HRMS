<?php

namespace App\Services;

use App\Models\Employee;
use App\Models\SalaryStructure;
use App\Models\SalaryStructureComponent;
use App\Services\Payroll\ExpressionEvaluator;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class SalaryHistoryService
{
    public function fetch(Employee $employee, array $filters): array
    {
        $query = \App\Models\SalaryStructure::query()
            ->with(['structureComponents.component'])
            ->forEmployee($employee->id)
            ->orderBy('effective_from', 'desc');

        if (!empty($filters['from'])) {
            $query->where('effective_from', '>=', $filters['from']);
        }
        if (!empty($filters['to'])) {
            $query->where(function ($q) use ($filters) {
                $q->whereNull('effective_to')->orWhere('effective_to', '<=', $filters['to']);
            });
        }

        $structures = $query->paginate(20);

        $canViewNet = auth()->user()?->can('payroll.view_net') ?? false;

        $items = [];
        foreach ($structures as $structure) {
            $earnings = 0.0;
            $deductions = 0.0;
            $components = ['earnings' => [], 'deductions' => []];
            $evaluated = $this->evaluatePeriodAmounts($structure);
            foreach ($evaluated['components'] as $bucket => $list) {
                foreach ($list as $c) {
                    if ($bucket === 'earnings') {
                        $earnings += (float) $c['value'];
                    }
                    if ($bucket === 'deductions') {
                        $deductions += (float) $c['value'];
                    }
                }
            }
            $components = $evaluated['components'];
            $gross = $earnings;
            $net = $gross - $deductions;

            $items[] = [
                'id' => $structure->id,
                'effective_from' => optional($structure->effective_from)?->format('Y-m-d'),
                'effective_to' => optional($structure->effective_to)?->format('Y-m-d'),
                'components' => $components,
                'totals' => [
                    'gross' => $canViewNet ? $gross : null,
                    'net' => $canViewNet ? $net : null,
                    'earnings' => $canViewNet ? $earnings : null,
                    'deductions' => $canViewNet ? $deductions : null,
                ],
                // Flattened for PDF export convenience
                'details_for_export' => [
                    'earnings' => $components['earnings'],
                    'deductions' => $components['deductions'],
                ],
            ];
        }

        return [
            'items' => $items,
            'pagination' => [
                'current_page' => $structures->currentPage(),
                'last_page' => $structures->lastPage(),
                'total' => $structures->total(),
            ],
            'can_view_net' => $canViewNet,
        ];
    }

    /**
     * Evaluate amounts for a salary structure period with caching.
     * Returns array{components: array{earnings: array<int,array{code:string,name:string,value:float,formula?:string}>, deductions: array<int,array{code:string,name:string,value:float,formula?:string}>}}
     */
    public function evaluatePeriodAmounts(SalaryStructure $structure): array
    {
        if (!config('payroll.use_salary_history_runtime_eval')) {
            // Fallback to numeric values only
            $components = ['earnings' => [], 'deductions' => []];
            foreach ($structure->structureComponents as $pivot) {
                $comp = $pivot->component;
                if (!$comp) {
                    continue;
                }
                $value = (float) ($pivot->value_numeric ?? 0.0);
                $bucket = $comp->comp_type === 'deduction' ? 'deductions' : 'earnings';
                $components[$bucket][] = ['code' => $comp->code, 'name' => $comp->name, 'value' => round($value, 2)];
            }
            return ['components' => $components];
        }

        $ttl = (int) config('payroll.salary_history_eval_ttl', 1800);
        $hashSource = $structure->updated_at?->timestamp . '|' . $structure->structureComponents->max('updated_at');
        $key = 'salary_history_eval:' . $structure->employee_id . ':' . $structure->id . ':' . sha1((string) $hashSource);

        return Cache::remember($key, $ttl, function () use ($structure) {
            $components = ['earnings' => [], 'deductions' => []];
            $context = [];
            $evaluator = app(ExpressionEvaluator::class);

            $ordered = $structure->structureComponents->sortBy('priority_order');
            foreach ($ordered as $pivot) {
                $comp = $pivot->component;
                if (!$comp) {
                    continue;
                }
                $value = null;
                if ($pivot->value_numeric !== null) {
                    $value = (float) $pivot->value_numeric;
                } elseif (!empty($pivot->formula_expr)) {
                    try {
                        // Replace codes in formula with current context values
                        $expr = $this->substituteVariables((string) $pivot->formula_expr, $context);
                        $value = $evaluator->evaluate($expr, config('payroll.expression_engine'));
                    } catch (\Throwable $e) {
                        Log::warning('Salary history eval error', [
                            'structure_id' => $structure->id,
                            'component_code' => $comp->code,
                            'message' => $e->getMessage(),
                        ]);
                        $value = 0.0;
                    }
                } else {
                    $value = 0.0;
                }

                $value = round((float) $value, 2);
                $context[$comp->code] = $value;
                $bucket = $comp->comp_type === 'deduction' ? 'deductions' : 'earnings';
                $components[$bucket][] = [
                    'code' => $comp->code,
                    'name' => \App\Support\Payroll\ComponentName::display($comp),
                    'value' => $value,
                    'formula' => $pivot->formula_expr,
                ];
            }

            return ['components' => $components];
        });
    }

    protected function substituteVariables(string $expr, array $context): string
    {
        // Replace tokens like BASIC_SALARY with numeric values from context
        return preg_replace_callback('/\b[A-Z_][A-Z0-9_]*\b/', function ($m) use ($context) {
            $code = $m[0];
            return array_key_exists($code, $context) ? (string) ($context[$code] ?? 0) : '0';
        }, $expr) ?? $expr;
    }

    public function export(Employee $employee, array $filters, string $format)
    {
        $data = $this->fetch($employee, $filters);
        $rows = collect($data['items'])->map(function ($row) {
            return [
                'Effective From' => $row['effective_from'],
                'Effective To' => $row['effective_to'],
                'Earnings' => $row['totals']['earnings'],
                'Deductions' => $row['totals']['deductions'],
                'Gross' => $row['totals']['gross'],
                'Net' => $row['totals']['net'],
                'Details' => $row['details_for_export'] ?? null,
            ];
        });

        if ($format === 'xlsx' || $format === 'excel') {
            $headings = array_keys($rows->first() ?? [
                'Effective From' => null,
                'Effective To' => null,
                'Earnings' => null,
                'Deductions' => null,
                'Gross' => null,
                'Net' => null,
            ]);

            // Consolidated or detailed via query param ?detail=true|false
            if (!($filters['detail'] ?? true)) {
                $export = new \App\Exports\GenericReportExport(function () use ($rows) {
                    foreach ($rows as $r) {
                        yield array_values($r);
                    }
                }, $headings);
                $filename = 'salary-history-' . $employee->code . '-' . now()->format('Ymd_His') . '.xlsx';
                return \Maatwebsite\Excel\Facades\Excel::download($export, $filename);
            }

            // Detailed: choose single-sheet (default) or multi-sheet via ?multisheet=1
            if (($filters['multisheet'] ?? false)) {
                $sheets = [];
                $sheets['Summary'] = [
                    'headings' => $headings,
                    'rows' => $rows->map(fn($r) => array_values($r))->all(),
                ];
                $i = 1;
                foreach ($rows as $r) {
                    $details = $r['Details'] ?? ['earnings' => [], 'deductions' => []];
                    $sheetName = 'Period_' . $i++;
                    $detailRows = [];
                    $detailRows[] = ['Earnings'];
                    foreach ($details['earnings'] as $c) {
                        $detailRows[] = [$c['code'], $c['name'], $c['value']];
                    }
                    $detailRows[] = [];
                    $detailRows[] = ['Deductions'];
                    foreach ($details['deductions'] as $c) {
                        $detailRows[] = [$c['code'], $c['name'], $c['value']];
                    }
                    $sheets[$sheetName] = [
                        'headings' => ['Code', 'Name', 'Value'],
                        'rows' => $detailRows,
                    ];
                }
                $export = new \App\Exports\MultiSheetSalaryHistoryExport($sheets);
                $filename = 'salary-history-' . $employee->code . '-' . now()->format('Ymd_His') . '.xlsx';
                return \Maatwebsite\Excel\Facades\Excel::download($export, $filename);
            }

            // Detailed single-sheet with breakdown columns
            $export = new \App\Exports\GenericReportExport(function () use ($rows) {
                foreach ($rows as $r) {
                    $earningStr = collect($r['Details']['earnings'] ?? [])->map(fn($c) => $c['code'] . ':' . $c['value'])->implode(', ');
                    $deductionStr = collect($r['Details']['deductions'] ?? [])->map(fn($c) => $c['code'] . ':' . $c['value'])->implode(', ');
                    yield [
                        $r['Effective From'],
                        $r['Effective To'],
                        $r['Earnings'],
                        $r['Deductions'],
                        $r['Gross'],
                        $r['Net'],
                        $earningStr,
                        $deductionStr
                    ];
                }
            }, array_merge($headings, ['Earnings Breakdown', 'Deductions Breakdown']));

            $filename = 'salary-history-' . $employee->code . '-' . now()->format('Ymd_His') . '.xlsx';
            return \Maatwebsite\Excel\Facades\Excel::download($export, $filename);
        }

        // PDF using existing mPDF integration
        $mpdf = new \Mpdf\Mpdf([
            'mode' => 'utf-8',
            'format' => 'A4',
            'margin_top' => 15,
            'margin_left' => 12,
            'margin_right' => 12,
            'tempDir' => storage_path('app/tmp'),
        ]);

        $html = view('employees.salary-history.pdf', [
            'employee' => $employee,
            'rows' => $rows,
            'generatedAt' => now(),
            'detail' => (bool) ($filters['detail'] ?? true),
        ])->render();

        $mpdf->WriteHTML($html);
        $output = $mpdf->Output('', 'S');
        return response($output, 200, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'attachment; filename="salary-history-' . $employee->code . '.pdf"',
        ]);
    }
}
