<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\Contract;
use App\Models\Payslip;
use App\Models\AttendanceSummary;
use App\Models\Document;
use App\Models\Department;
use App\Models\Position;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Maatwebsite\Excel\Facades\Excel;
use Mpdf\Mpdf;
use App\Exports\EmployeeListExport;

class ReportsController extends Controller
{
    /**
     * Reports dashboard with available report types.
     */
    public function index()
    {
        $user = Auth::user();

        // Get summary statistics for the dashboard
        $stats = [
            'total_employees' => Employee::count(),
            'active_contracts' => Contract::where('status', 'active')->count(),
            'expiring_contracts' => Contract::where('status', 'active')
                ->where('end_date', '<=', now()->addDays(30))
                ->count(),
            'documents_count' => Document::count(),
            'payslips_this_month' => Payslip::whereMonth('created_at', now()->month)
                ->whereYear('created_at', now()->year)
                ->count(),
        ];

        // Available report categories
        $reportCategories = [
            'employee' => [
                'title' => __('Employee Reports'),
                'description' => __('Employee lists, statistics, and demographic analysis'),
                'icon' => 'fas fa-users',
                'reports' => [
                    'employee-list' => __('Employee Directory'),
                    'employee-demographics' => __('Employee Demographics'),
                    'department-analysis' => __('Department Analysis'),
                    'position-analysis' => __('Position Analysis'),
                ]
            ],
            'contract' => [
                'title' => __('Contract Reports'),
                'description' => __('Contract status, expiry tracking, and analysis'),
                'icon' => 'fas fa-file-contract',
                'reports' => [
                    'contract-status' => __('Contract Status Report'),
                    'contract-expiry' => __('Contract Expiry Report'),
                    'contract-analysis' => __('Contract Analysis'),
                ]
            ],
            'payroll' => [
                'title' => __('Payroll Reports'),
                'description' => __('Salary analysis and payroll summaries'),
                'icon' => 'fas fa-file-invoice-dollar',
                'reports' => [
                    'payroll-summary' => __('Payroll Summary'),
                    'salary-analysis' => __('Salary Analysis by Department'),
                    'payslip-report' => __('Payslip Generation Report'),
                ]
            ],
            'attendance' => [
                'title' => __('Attendance Reports'),
                'description' => __('Attendance tracking and time analysis'),
                'icon' => 'fas fa-clock',
                'reports' => [
                    'attendance-summary' => __('Attendance Summary'),
                    'overtime-report' => __('Overtime Report'),
                    'attendance-trends' => __('Attendance Trends'),
                ]
            ],
            'document' => [
                'title' => __('Document Reports'),
                'description' => __('Document tracking and compliance reports'),
                'icon' => 'fas fa-folder-open',
                'reports' => [
                    'document-inventory' => __('Document Inventory'),
                    'document-expiry' => __('Document Expiry Report'),
                    'compliance-report' => __('Compliance Report'),
                ]
            ]
        ];

        return view('reports.index', compact('stats', 'reportCategories'));
    }

    /**
     * Employee directory report.
     */
    public function employeeList(Request $request)
    {
        $filters = $request->validate([
            'department_id' => 'nullable|exists:departments,id',
            'position_id' => 'nullable|exists:positions,id',
            'employment_status' => 'nullable|in:active,inactive,terminated',
            'export_format' => 'nullable|in:excel,pdf'
        ]);

        $employees = Employee::with(['department', 'position', 'manager'])
            ->when($filters['department_id'] ?? null, function ($query, $departmentId) {
                $query->where('department_id', $departmentId);
            })
            ->when($filters['position_id'] ?? null, function ($query, $positionId) {
                $query->where('position_id', $positionId);
            })
            ->when($filters['employment_status'] ?? null, function ($query, $status) {
                $query->where('employment_status', $status);
            })
            ->orderBy('department_id')
            ->orderBy('last_name')
            ->get();

        if ($request->filled('export_format')) {
            return $this->exportEmployeeList($employees, $filters['export_format'], $filters);
        }

        $departments = Department::orderBy('name_en')->get();
        $positions = Position::orderBy('name_en')->get();

        return view('reports.employee-list', compact('employees', 'departments', 'positions', 'filters'));
    }

    /**
     * Employee demographics report.
     */
    public function employeeDemographics(Request $request)
    {
        $filters = $request->validate([
            'export_format' => 'nullable|in:excel,pdf'
        ]);

        $demographics = [
            'by_department' => Employee::with('department')
                ->select('department_id', DB::raw('count(*) as count'))
                ->groupBy('department_id')
                ->get()
                ->mapWithKeys(function ($item) {
                    return [$item->department->name_en => $item->count];
                }),
            'by_position' => Employee::with('position')
                ->select('position_id', DB::raw('count(*) as count'))
                ->groupBy('position_id')
                ->get()
                ->mapWithKeys(function ($item) {
                    return [$item->position->name_en => $item->count];
                }),
            'by_employment_status' => Employee::select('employment_status', DB::raw('count(*) as count'))
                ->groupBy('employment_status')
                ->pluck('count', 'employment_status'),
            'by_gender' => Employee::select('gender', DB::raw('count(*) as count'))
                ->groupBy('gender')
                ->pluck('count', 'gender'),
            'by_age_group' => $this->getAgeGroupStats(),
            'by_hire_year' => Employee::select(DB::raw('YEAR(hire_date) as year'), DB::raw('count(*) as count'))
                ->groupBy(DB::raw('YEAR(hire_date)'))
                ->orderBy('year')
                ->pluck('count', 'year'),
        ];

        if ($request->filled('export_format')) {
            return $this->exportDemographics($demographics, $filters['export_format']);
        }

        return view('reports.employee-demographics', compact('demographics'));
    }

    /**
     * Contract status report.
     */
    public function contractStatus(Request $request)
    {
        $filters = $request->validate([
            'status' => 'nullable|in:active,expired,terminated',
            'contract_type' => 'nullable|in:permanent,fixed_term,probation,internship,consultancy',
            'export_format' => 'nullable|in:excel,pdf'
        ]);

        $contracts = Contract::with(['employee.department', 'employee.position'])
            ->when($filters['status'] ?? null, function ($query, $status) {
                $query->where('status', $status);
            })
            ->when($filters['contract_type'] ?? null, function ($query, $type) {
                $query->where('type', $type);
            })
            ->orderBy('end_date')
            ->get();

        $summary = [
            'total' => $contracts->count(),
            'by_status' => Contract::select('status', DB::raw('count(*) as count'))
                ->groupBy('status')
                ->pluck('count', 'status'),
            'by_type' => Contract::select('type', DB::raw('count(*) as count'))
                ->groupBy('type')
                ->pluck('count', 'type'),
            'expiring_soon' => Contract::where('status', 'active')
                ->where('end_date', '<=', now()->addDays(30))
                ->count(),
        ];

        if ($request->filled('export_format')) {
            return $this->exportContractStatus($contracts, $summary, $filters['export_format']);
        }

        return view('reports.contract-status', compact('contracts', 'summary', 'filters'));
    }

    /**
     * Payroll summary report.
     */
    public function payrollSummary(Request $request)
    {
        $filters = $request->validate([
            'month' => 'nullable|date_format:Y-m',
            'department_id' => 'nullable|exists:departments,id',
            'export_format' => 'nullable|in:excel,pdf'
        ]);

        $month = $filters['month'] ?? now()->format('Y-m');
        $monthStart = Carbon::createFromFormat('Y-m', $month)->startOfMonth();
        $monthEnd = $monthStart->copy()->endOfMonth();

        $payslips = Payslip::with(['employee.department', 'employee.position'])
            ->whereBetween('pay_period_start', [$monthStart, $monthEnd])
            ->when($filters['department_id'] ?? null, function ($query, $departmentId) {
                $query->whereHas('employee', function ($q) use ($departmentId) {
                    $q->where('department_id', $departmentId);
                });
            })
            ->get();

        $summary = [
            'total_employees' => $payslips->count(),
            'total_gross' => $payslips->sum('gross_salary'),
            'total_net' => $payslips->sum('net_salary'),
            'total_deductions' => $payslips->sum('total_deductions'),
            'by_department' => $payslips->groupBy('employee.department.name_en')
                ->map(function ($group) {
                    return [
                        'count' => $group->count(),
                        'gross' => $group->sum('gross_salary'),
                        'net' => $group->sum('net_salary'),
                    ];
                }),
        ];

        if ($request->filled('export_format')) {
            return $this->exportPayrollSummary($payslips, $summary, $filters['export_format'], $month);
        }

        $departments = Department::orderBy('name_en')->get();

        return view('reports.payroll-summary', compact('payslips', 'summary', 'filters', 'departments', 'month'));
    }

    /**
     * Attendance summary report.
     */
    public function attendanceSummary(Request $request)
    {
        $filters = $request->validate([
            'month' => 'nullable|date_format:Y-m',
            'department_id' => 'nullable|exists:departments,id',
            'export_format' => 'nullable|in:excel,pdf'
        ]);

        $month = $filters['month'] ?? now()->format('Y-m');
        $monthStart = Carbon::createFromFormat('Y-m', $month)->startOfMonth();
        $monthEnd = $monthStart->copy()->endOfMonth();

        $attendance = AttendanceSummary::with(['employee.department', 'employee.position'])
            ->whereBetween('date', [$monthStart, $monthEnd])
            ->when($filters['department_id'] ?? null, function ($query, $departmentId) {
                $query->whereHas('employee', function ($q) use ($departmentId) {
                    $q->where('department_id', $departmentId);
                });
            })
            ->get()
            ->groupBy('employee_id')
            ->map(function ($group) {
                $employee = $group->first()->employee;
                return [
                    'employee' => $employee,
                    'total_hours' => $group->sum('work_hours'),
                    'overtime_hours' => $group->sum('overtime_hours'),
                    'present_days' => $group->where('status', 'present')->count(),
                    'late_days' => $group->where('late_minutes', '>', 0)->count(),
                    'absent_days' => $group->where('status', 'absent')->count(),
                ];
            });

        $summary = [
            'total_employees' => $attendance->count(),
            'total_hours' => $attendance->sum('total_hours'),
            'total_overtime' => $attendance->sum('overtime_hours'),
            'average_hours' => $attendance->count() > 0 ? $attendance->avg('total_hours') : 0,
        ];

        if ($request->filled('export_format')) {
            return $this->exportAttendanceSummary($attendance, $summary, $filters['export_format'], $month);
        }

        $departments = Department::orderBy('name_en')->get();

        return view('reports.attendance-summary', compact('attendance', 'summary', 'filters', 'departments', 'month'));
    }

    /**
     * Document inventory report.
     */
    public function documentInventory(Request $request)
    {
        $filters = $request->validate([
            'document_type' => 'nullable|string',
            'expiry_status' => 'nullable|in:valid,expiring,expired',
            'export_format' => 'nullable|in:excel,pdf'
        ]);

        $documents = Document::with(['employee.department', 'tags'])
            ->when($filters['document_type'] ?? null, function ($query, $type) {
                $query->where('type', $type);
            })
            ->when($filters['expiry_status'] ?? null, function ($query, $status) {
                if ($status === 'expired') {
                    $query->where('expires_at', '<', now());
                } elseif ($status === 'expiring') {
                    $query->where('expires_at', '>=', now())
                          ->where('expires_at', '<=', now()->addDays(30));
                } elseif ($status === 'valid') {
                    $query->where(function ($q) {
                        $q->where('expires_at', '>', now()->addDays(30))
                          ->orWhereNull('expires_at');
                    });
                }
            })
            ->orderBy('created_at', 'desc')
            ->get();

        $summary = [
            'total' => $documents->count(),
            'by_type' => Document::select('type', DB::raw('count(*) as count'))
                ->groupBy('type')
                ->pluck('count', 'type'),
            'expiring_soon' => Document::where('expires_at', '<=', now()->addDays(30))
                ->where('expires_at', '>=', now())
                ->count(),
            'expired' => Document::where('expires_at', '<', now())->count(),
        ];

        if ($request->filled('export_format')) {
            return $this->exportDocumentInventory($documents, $summary, $filters['export_format']);
        }

        $documentTypes = Document::TYPES;

        return view('reports.document-inventory', compact('documents', 'summary', 'filters', 'documentTypes'));
    }

    /**
     * Get age group statistics.
     */
    private function getAgeGroupStats()
    {
        $employees = Employee::whereNotNull('birth_date')->get();
        $ageGroups = [
            '20-29' => 0,
            '30-39' => 0,
            '40-49' => 0,
            '50-59' => 0,
            '60+' => 0,
        ];

        foreach ($employees as $employee) {
            $age = $employee->birth_date->age;
            if ($age < 30) {
                $ageGroups['20-29']++;
            } elseif ($age < 40) {
                $ageGroups['30-39']++;
            } elseif ($age < 50) {
                $ageGroups['40-49']++;
            } elseif ($age < 60) {
                $ageGroups['50-59']++;
            } else {
                $ageGroups['60+']++;
            }
        }

        return $ageGroups;
    }

    /**
     * Export employee list.
     */
    private function exportEmployeeList($employees, $format, $filters)
    {
        $data = $employees->map(function ($employee) {
            return [
                'Employee Code' => $employee->code,
                'Full Name' => $employee->display_name,
                'Department' => $employee->department->name_en,
                'Position' => $employee->position->name_en,
                'Employment Status' => ucfirst($employee->employment_status),
                'Hire Date' => $employee->hire_date->format('Y-m-d'),
                'Manager' => $employee->manager->display_name ?? 'N/A',
                'Work Email' => $employee->work_email ?? 'N/A',
                'Phone' => $employee->phone ?? 'N/A',
            ];
        });

        $filename = 'employee-list-' . now()->format('Y-m-d');

        if ($format === 'excel') {
            return Excel::download(new EmployeeListExport($data, 'Employee Directory'), $filename . '.xlsx');
        } else {
            $html = view('reports.exports.employee-list-pdf', compact('data', 'filters'))->render();
            $mpdf = new Mpdf();
            $mpdf->WriteHTML($html);
            return response($mpdf->Output($filename . '.pdf', 'S'))
                ->header('Content-Type', 'application/pdf')
                ->header('Content-Disposition', 'attachment; filename="' . $filename . '.pdf"');
        }
    }

    /**
     * Export demographics report.
     */
    private function exportDemographics($demographics, $format)
    {
        $filename = 'employee-demographics-' . now()->format('Y-m-d');

        if ($format === 'excel') {
            // Create Excel with multiple sheets for each demographic breakdown
            return response()->json(['message' => 'Excel export for demographics will be implemented']);
        } else {
            $pdf = PDF::loadView('reports.exports.demographics-pdf', compact('demographics'));
            return $pdf->download($filename . '.pdf');
        }
    }

    /**
     * Export contract status report.
     */
    private function exportContractStatus($contracts, $summary, $format)
    {
        $filename = 'contract-status-' . now()->format('Y-m-d');

        $data = $contracts->map(function ($contract) {
            return [
                'Employee' => $contract->employee->display_name,
                'Department' => $contract->employee->department->name_en,
                'Contract Type' => ucfirst(str_replace('_', ' ', $contract->type)),
                'Status' => ucfirst($contract->status),
                'Start Date' => $contract->start_date->format('Y-m-d'),
                'End Date' => $contract->end_date ? $contract->end_date->format('Y-m-d') : 'N/A',
                'Days Until Expiry' => $contract->end_date ? now()->diffInDays($contract->end_date, false) : 'N/A',
            ];
        });

        if ($format === 'excel') {
            return Excel::download(new class($data) implements \Maatwebsite\Excel\Concerns\FromCollection {
                public function __construct(private $data) {}
                public function collection() { return $this->data; }
            }, $filename . '.xlsx');
        } else {
            $pdf = PDF::loadView('reports.exports.contract-status-pdf', compact('data', 'summary'));
            return $pdf->download($filename . '.pdf');
        }
    }

    /**
     * Export payroll summary.
     */
    private function exportPayrollSummary($payslips, $summary, $format, $month)
    {
        $filename = 'payroll-summary-' . $month;

        $data = $payslips->map(function ($payslip) {
            return [
                'Employee' => $payslip->employee->display_name,
                'Department' => $payslip->employee->department->name_en,
                'Position' => $payslip->employee->position->name_en,
                'Gross Salary' => number_format($payslip->gross_salary, 2),
                'Total Deductions' => number_format($payslip->total_deductions, 2),
                'Net Salary' => number_format($payslip->net_salary, 2),
                'Pay Period' => $payslip->pay_period_start->format('Y-m-d') . ' to ' . $payslip->pay_period_end->format('Y-m-d'),
            ];
        });

        if ($format === 'excel') {
            return Excel::download(new class($data) implements \Maatwebsite\Excel\Concerns\FromCollection {
                public function __construct(private $data) {}
                public function collection() { return $this->data; }
            }, $filename . '.xlsx');
        } else {
            $pdf = PDF::loadView('reports.exports.payroll-summary-pdf', compact('data', 'summary', 'month'));
            return $pdf->download($filename . '.pdf');
        }
    }

    /**
     * Export attendance summary.
     */
    private function exportAttendanceSummary($attendance, $summary, $format, $month)
    {
        $filename = 'attendance-summary-' . $month;

        $data = $attendance->map(function ($record) {
            return [
                'Employee' => $record['employee']->display_name,
                'Department' => $record['employee']->department->name_en,
                'Position' => $record['employee']->position->name_en,
                'Total Hours' => number_format($record['total_hours'], 2),
                'Overtime Hours' => number_format($record['overtime_hours'], 2),
                'Present Days' => $record['present_days'],
                'Late Days' => $record['late_days'],
                'Absent Days' => $record['absent_days'],
            ];
        });

        if ($format === 'excel') {
            return Excel::download(new class($data) implements \Maatwebsite\Excel\Concerns\FromCollection {
                public function __construct(private $data) {}
                public function collection() { return $this->data; }
            }, $filename . '.xlsx');
        } else {
            $pdf = PDF::loadView('reports.exports.attendance-summary-pdf', compact('data', 'summary', 'month'));
            return $pdf->download($filename . '.pdf');
        }
    }

    /**
     * Export document inventory.
     */
    private function exportDocumentInventory($documents, $summary, $format)
    {
        $filename = 'document-inventory-' . now()->format('Y-m-d');

        $data = $documents->map(function ($document) {
            return [
                'Employee' => $document->employee->display_name,
                'Department' => $document->employee->department->name_en,
                'Document Name' => $document->original_name,
                'Type' => $document->type_display_name,
                'Upload Date' => $document->created_at->format('Y-m-d'),
                'Expiry Date' => $document->expires_at ? $document->expires_at->format('Y-m-d') : 'N/A',
                'Status' => $document->is_expired ? 'Expired' : ($document->is_expiring_soon ? 'Expiring Soon' : 'Valid'),
                'Tags' => $document->tags->pluck('name')->implode(', '),
            ];
        });

        if ($format === 'excel') {
            return Excel::download(new class($data) implements \Maatwebsite\Excel\Concerns\FromCollection {
                public function __construct(private $data) {}
                public function collection() { return $this->data; }
            }, $filename . '.xlsx');
        } else {
            $pdf = PDF::loadView('reports.exports.document-inventory-pdf', compact('data', 'summary'));
            return $pdf->download($filename . '.pdf');
        }
    }
}