<?php

namespace App\Services;

use App\Exports\ArrayExport;
use App\Models\Employee;
use App\Models\Contract;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Facades\Excel;
use Mpdf\Mpdf;

class ReportExportService
{
    public static function generateAttachment(array $report): ?array
    {
        $key = $report['report_key'] ?? '';
        $format = strtolower($report['format'] ?? 'xlsx');
        $params = $report['params'] ?? [];

        switch ($key) {
            case 'reports.employee.list':
                $head = ['Name','Department','Position','Status'];
                $rows = self::employeeListRows($params);
                $filename = 'employee_list_'.date('Ymd_His').'.'.$format;
                return self::makeFile($head, $rows, $format, $filename);
            case 'reports.contract.status':
                $head = ['Employee','Status','Type','Start','End'];
                $rows = self::contractStatusRows($params);
                $filename = 'contract_status_'.date('Ymd_His').'.'.$format;
                return self::makeFile($head, $rows, $format, $filename);
            default:
                return null; // Unsupported for now
        }
    }

    private static function employeeListRows(array $p): array
    {
        $q = Employee::with(['department','position'])
            ->when(($p['department_id'] ?? null), fn($qq,$v)=>$qq->where('department_id',$v))
            ->when(($p['position_id'] ?? null), fn($qq,$v)=>$qq->where('position_id',$v))
            ->when(($p['employment_status'] ?? null), fn($qq,$v)=>$qq->where('employment_status',$v))
            ->orderBy('last_name');
        return $q->get()->map(function($e){
            return [
                ($e->arabic_name ?: ($e->first_name.' '.$e->last_name)),
                $e->department->name_en ?? '',
                $e->position->name_en ?? '',
                ucfirst($e->employment_status ?? $e->status)
            ];
        })->toArray();
    }

    private static function contractStatusRows(array $p): array
    {
        $q = Contract::with('employee')
            ->when(($p['status'] ?? null), fn($qq,$v)=>$qq->where('status',$v))
            ->when(($p['contract_type'] ?? null), fn($qq,$v)=>$qq->where('type',$v))
            ->latest('start_date');
        return $q->get()->map(function($c){
            return [
                $c->employee?->full_name,
                ucfirst($c->status),
                ucfirst($c->type),
                optional($c->start_date)->format('Y-m-d'),
                optional($c->end_date)->format('Y-m-d')
            ];
        })->toArray();
    }

    private static function makeFile(array $head, array $rows, string $format, string $filename): array
    {
        $tmpDir = storage_path('app/tmp');
        if (!is_dir($tmpDir)) @mkdir($tmpDir, 0775, true);
        $path = $tmpDir.'/'.Str::random(8).'_'.$filename;
        if (in_array($format, ['xlsx','csv'])) {
            $writer = $format === 'csv' ? \Maatwebsite\Excel\Excel::CSV : \Maatwebsite\Excel\Excel::XLSX;
            $bin = Excel::raw(new ArrayExport($head, $rows), $writer);
            file_put_contents($path, $bin);
        } elseif ($format === 'pdf') {
            $html = '<h3>'.$filename.'</h3><table border="1" cellpadding="6" cellspacing="0"><thead><tr>';
            foreach ($head as $h) $html .= '<th>'.htmlspecialchars($h).'</th>';
            $html .= '</tr></thead><tbody>';
            foreach ($rows as $r) {
                $html .= '<tr>'; foreach ($r as $cell) { $html .= '<td>'.htmlspecialchars((string)$cell).'</td>'; } $html .= '</tr>';
            }
            $html .= '</tbody></table>';
            $mpdf = new Mpdf(['tempDir' => storage_path('app/tmp')]);
            $mpdf->WriteHTML($html);
            $mpdf->Output($path, \Mpdf\Output\Destination::FILE);
        } else {
            // default xlsx
            $bin = Excel::raw(new ArrayExport($head, $rows), \Maatwebsite\Excel\Excel::XLSX);
            file_put_contents($path, $bin);
        }
        return ['path'=>$path,'filename'=>$filename];
    }
}

