<?php

namespace App\Services;

use App\Models\Employee;

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
            foreach ($structure->structureComponents as $pivot) {
                $component = $pivot->component;
                if (!$component) {
                    continue;
                }
                $value = $pivot->value_numeric ?? 0.0; // basic fixed value; formulas handled later
                if ($component->comp_type === 'earning') {
                    $earnings += (float) $value;
                    $components['earnings'][] = ['code' => $component->code, 'name' => $component->name, 'value' => (float) $value];
                } elseif ($component->comp_type === 'deduction') {
                    $deductions += (float) $value;
                    $components['deductions'][] = ['code' => $component->code, 'name' => $component->name, 'value' => (float) $value];
                }
            }
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
