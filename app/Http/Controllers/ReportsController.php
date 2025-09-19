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
                    return [$item->department ? $item->department->name_en : 'Unknown' => $item->count];
                }),
            'by_position' => Employee::with('position')
                ->select('position_id', DB::raw('count(*) as count'))
                ->groupBy('position_id')
                ->get()
                ->mapWithKeys(function ($item) {
                    return [$item->position ? $item->position->name_en : 'Unknown' => $item->count];
                }),
            'by_status' => Employee::select('status', DB::raw('count(*) as count'))
                ->groupBy('status')
                ->pluck('count', 'status'),
            'by_employment_type' => Employee::with('employmentType')
                ->select('employment_type_id', DB::raw('count(*) as count'))
                ->groupBy('employment_type_id')
                ->get()
                ->mapWithKeys(function ($item) {
                    return [$item->employmentType ? $item->employmentType->name : 'Unknown' => $item->count];
                }),
            'by_tenure_group' => $this->getTenureGroupStats(),
            'by_hire_year' => Employee::select(DB::raw('YEAR(hire_date) as year'), DB::raw('count(*) as count'))
                ->whereNotNull('hire_date')
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
     * Department analysis report.
     */
    public function departmentAnalysis(Request $request)
    {
        $filters = $request->validate([
            'export_format' => 'nullable|in:excel,pdf'
        ]);

        $departments = Department::withCount(['employees'])
            ->with(['employees.position'])
            ->orderBy('name_en')
            ->get();

        $analysis = $departments->map(function ($department) {
            $employees = $department->employees;
            $activeContracts = Contract::whereHas('employee', function ($query) use ($department) {
                $query->where('department_id', $department->id);
            })->where('status', 'active')->count();

            return [
                'department' => $department,
                'total_employees' => $employees->count(),
                'by_position' => $employees->groupBy('position.name_en')->map->count(),
                'avg_tenure' => $employees->avg(function ($emp) {
                    return $emp->hire_date ? $emp->hire_date->diffInMonths(now()) : 0;
                }),
                'active_contracts' => $activeContracts,
            ];
        });

        if ($request->filled('export_format')) {
            return $this->exportDepartmentAnalysis($analysis, $filters['export_format']);
        }

        return view('reports.department-analysis', compact('analysis', 'filters'));
    }

    /**
     * Position analysis report.
     */
    public function positionAnalysis(Request $request)
    {
        $filters = $request->validate([
            'export_format' => 'nullable|in:excel,pdf'
        ]);

        $positions = Position::withCount(['employees'])
            ->with(['employees.department'])
            ->orderBy('name_en')
            ->get();

        $analysis = $positions->map(function ($position) {
            $employees = $position->employees;
            return [
                'position' => $position,
                'total_employees' => $employees->count(),
                'by_department' => $employees->groupBy('department.name_en')->map->count(),
                'avg_tenure' => $employees->avg(function ($emp) {
                    return $emp->hire_date ? $emp->hire_date->diffInMonths(now()) : 0;
                }),
                'eligible_overtime' => $position->overtime_eligible ? $employees->count() : 0,
            ];
        });

        if ($request->filled('export_format')) {
            return $this->exportPositionAnalysis($analysis, $filters['export_format']);
        }

        return view('reports.position-analysis', compact('analysis', 'filters'));
    }

    /**
     * Contract expiry report.
     */
    public function contractExpiry(Request $request)
    {
        $filters = $request->validate([
            'days_ahead' => 'nullable|integer|min:1|max:365',
            'export_format' => 'nullable|in:excel,pdf'
        ]);

        $daysAhead = $filters['days_ahead'] ?? 90;
        $cutoffDate = now()->addDays($daysAhead);

        $contracts = Contract::with(['employee.department', 'employee.position'])
            ->where('status', 'active')
            ->where('end_date', '<=', $cutoffDate)
            ->where('end_date', '>=', now())
            ->orderBy('end_date')
            ->get();

        $groupedContracts = $contracts->groupBy(function ($contract) {
            $daysUntilExpiry = now()->diffInDays($contract->end_date, false);
            if ($daysUntilExpiry <= 7) return 'urgent';
            if ($daysUntilExpiry <= 30) return 'soon';
            return 'future';
        });

        if ($request->filled('export_format')) {
            return $this->exportContractExpiry($contracts, $groupedContracts, $filters['export_format']);
        }

        return view('reports.contract-expiry', compact('contracts', 'groupedContracts', 'filters', 'daysAhead'));
    }

    /**
     * Contract analysis report.
     */
    public function contractAnalysis(Request $request)
    {
        $filters = $request->validate([
            'export_format' => 'nullable|in:excel,pdf'
        ]);

        $analysis = [
            'by_type' => Contract::select('type', DB::raw('count(*) as count'))
                ->groupBy('type')
                ->pluck('count', 'type'),
            'by_status' => Contract::select('status', DB::raw('count(*) as count'))
                ->groupBy('status')
                ->pluck('count', 'status'),
            'by_department' => Contract::with('employee.department')
                ->get()
                ->groupBy('employee.department.name_en')
                ->map->count(),
            'renewal_trend' => Contract::where('status', 'active')
                ->where('end_date', '>=', now())
                ->where('end_date', '<=', now()->addYear())
                ->get()
                ->groupBy(function ($contract) {
                    return $contract->end_date->format('Y-m');
                })
                ->map->count(),
        ];

        if ($request->filled('export_format')) {
            return $this->exportContractAnalysis($analysis, $filters['export_format']);
        }

        return view('reports.contract-analysis', compact('analysis', 'filters'));
    }

    /**
     * Salary analysis report.
     */
    public function salaryAnalysis(Request $request)
    {
        $filters = $request->validate([
            'department_id' => 'nullable|exists:departments,id',
            'export_format' => 'nullable|in:excel,pdf'
        ]);

        $user = Auth::user();
        if (!$user->can('view-net-salary')) {
            abort(403, 'Unauthorized to view salary analysis');
        }

        $payslips = Payslip::with(['employee.department', 'employee.position'])
            ->whereMonth('pay_period_start', now()->month)
            ->whereYear('pay_period_start', now()->year)
            ->when($filters['department_id'] ?? null, function ($query, $departmentId) {
                $query->whereHas('employee', function ($q) use ($departmentId) {
                    $q->where('department_id', $departmentId);
                });
            })
            ->get();

        $analysis = [
            'by_department' => $payslips->groupBy('employee.department.name_en')
                ->map(function ($group) {
                    return [
                        'count' => $group->count(),
                        'avg_gross' => $group->avg('gross_salary'),
                        'avg_net' => $group->avg('net_salary'),
                        'total_gross' => $group->sum('gross_salary'),
                        'total_net' => $group->sum('net_salary'),
                    ];
                }),
            'by_position' => $payslips->groupBy('employee.position.name_en')
                ->map(function ($group) {
                    return [
                        'count' => $group->count(),
                        'avg_gross' => $group->avg('gross_salary'),
                        'avg_net' => $group->avg('net_salary'),
                    ];
                }),
        ];

        if ($request->filled('export_format')) {
            return $this->exportSalaryAnalysis($analysis, $filters['export_format']);
        }

        $departments = Department::orderBy('name_en')->get();

        return view('reports.salary-analysis', compact('analysis', 'filters', 'departments'));
    }

    /**
     * Payslip generation report.
     */
    public function payslipReport(Request $request)
    {
        $filters = $request->validate([
            'month' => 'nullable|date_format:Y-m',
            'status' => 'nullable|in:generated,sent,downloaded',
            'export_format' => 'nullable|in:excel,pdf'
        ]);

        $month = $filters['month'] ?? now()->format('Y-m');
        $monthStart = Carbon::createFromFormat('Y-m', $month)->startOfMonth();
        $monthEnd = $monthStart->copy()->endOfMonth();

        $payslips = Payslip::with(['employee.department', 'employee.position'])
            ->whereBetween('pay_period_start', [$monthStart, $monthEnd])
            ->when($filters['status'] ?? null, function ($query, $status) {
                if ($status === 'sent') {
                    $query->whereNotNull('email_sent_at');
                } elseif ($status === 'downloaded') {
                    $query->whereNotNull('downloaded_at');
                } else {
                    $query->whereNotNull('generated_at');
                }
            })
            ->orderBy('created_at', 'desc')
            ->get();

        $summary = [
            'total' => $payslips->count(),
            'generated' => $payslips->whereNotNull('generated_at')->count(),
            'sent' => $payslips->whereNotNull('email_sent_at')->count(),
            'downloaded' => $payslips->whereNotNull('downloaded_at')->count(),
        ];

        if ($request->filled('export_format')) {
            return $this->exportPayslipReport($payslips, $summary, $filters['export_format'], $month);
        }

        return view('reports.payslip-report', compact('payslips', 'summary', 'filters', 'month'));
    }

    /**
     * Overtime report.
     */
    public function overtimeReport(Request $request)
    {
        $filters = $request->validate([
            'month' => 'nullable|date_format:Y-m',
            'department_id' => 'nullable|exists:departments,id',
            'export_format' => 'nullable|in:excel,pdf'
        ]);

        $month = $filters['month'] ?? now()->format('Y-m');
        $monthStart = Carbon::createFromFormat('Y-m', $month)->startOfMonth();
        $monthEnd = $monthStart->copy()->endOfMonth();

        $overtimeData = AttendanceSummary::with(['employee.department', 'employee.position'])
            ->whereBetween('date', [$monthStart, $monthEnd])
            ->where('overtime_hours', '>', 0)
            ->when($filters['department_id'] ?? null, function ($query, $departmentId) {
                $query->whereHas('employee', function ($q) use ($departmentId) {
                    $q->where('department_id', $departmentId);
                });
            })
            ->get()
            ->groupBy('employee_id')
            ->map(function ($records) {
                $employee = $records->first()->employee;
                return [
                    'employee' => $employee,
                    'total_overtime' => $records->sum('overtime_hours'),
                    'overtime_days' => $records->where('overtime_hours', '>', 0)->count(),
                    'avg_daily_overtime' => $records->avg('overtime_hours'),
                ];
            })
            ->sortByDesc('total_overtime');

        $summary = [
            'total_employees' => $overtimeData->count(),
            'total_overtime_hours' => $overtimeData->sum('total_overtime'),
            'avg_overtime_per_employee' => $overtimeData->count() > 0 ? $overtimeData->avg('total_overtime') : 0,
        ];

        if ($request->filled('export_format')) {
            return $this->exportOvertimeReport($overtimeData, $summary, $filters['export_format'], $month);
        }

        $departments = Department::orderBy('name_en')->get();

        return view('reports.overtime-report', compact('overtimeData', 'summary', 'filters', 'departments', 'month'));
    }

    /**
     * Attendance trends report.
     */
    public function attendanceTrends(Request $request)
    {
        $filters = $request->validate([
            'months' => 'nullable|integer|min:1|max:12',
            'department_id' => 'nullable|exists:departments,id',
            'export_format' => 'nullable|in:excel,pdf'
        ]);

        $months = $filters['months'] ?? 6;
        $startDate = now()->subMonths($months)->startOfMonth();
        $endDate = now()->endOfMonth();

        $attendance = AttendanceSummary::with(['employee.department'])
            ->whereBetween('date', [$startDate, $endDate])
            ->when($filters['department_id'] ?? null, function ($query, $departmentId) {
                $query->whereHas('employee', function ($q) use ($departmentId) {
                    $q->where('department_id', $departmentId);
                });
            })
            ->get();

        $trends = $attendance->groupBy(function ($record) {
            return $record->date->format('Y-m');
        })->map(function ($monthRecords) {
            return [
                'total_employees' => $monthRecords->groupBy('employee_id')->count(),
                'present_days' => $monthRecords->where('status', 'present')->count(),
                'absent_days' => $monthRecords->where('status', 'absent')->count(),
                'late_instances' => $monthRecords->where('late_minutes', '>', 0)->count(),
                'total_hours' => $monthRecords->sum('work_hours'),
                'overtime_hours' => $monthRecords->sum('overtime_hours'),
                'attendance_rate' => $monthRecords->count() > 0
                    ? ($monthRecords->where('status', 'present')->count() / $monthRecords->count()) * 100
                    : 0,
            ];
        });

        if ($request->filled('export_format')) {
            return $this->exportAttendanceTrends($trends, $filters['export_format'], $months);
        }

        $departments = Department::orderBy('name_en')->get();

        return view('reports.attendance-trends', compact('trends', 'filters', 'departments', 'months'));
    }

    /**
     * Document expiry report.
     */
    public function documentExpiry(Request $request)
    {
        $filters = $request->validate([
            'days_ahead' => 'nullable|integer|min:1|max:365',
            'document_type' => 'nullable|string',
            'export_format' => 'nullable|in:excel,pdf'
        ]);

        $daysAhead = $filters['days_ahead'] ?? 90;
        $cutoffDate = now()->addDays($daysAhead);

        $documents = Document::with(['employee.department', 'tags'])
            ->where('expires_at', '<=', $cutoffDate)
            ->where('expires_at', '>=', now())
            ->when($filters['document_type'] ?? null, function ($query, $type) {
                $query->where('type', $type);
            })
            ->orderBy('expires_at')
            ->get();

        $groupedDocuments = $documents->groupBy(function ($document) {
            $daysUntilExpiry = now()->diffInDays($document->expires_at, false);
            if ($daysUntilExpiry <= 7) return 'urgent';
            if ($daysUntilExpiry <= 30) return 'soon';
            return 'future';
        });

        if ($request->filled('export_format')) {
            return $this->exportDocumentExpiry($documents, $groupedDocuments, $filters['export_format']);
        }

        $documentTypes = Document::TYPES ?? [];

        return view('reports.document-expiry', compact('documents', 'groupedDocuments', 'filters', 'documentTypes', 'daysAhead'));
    }

    /**
     * Compliance report.
     */
    public function complianceReport(Request $request)
    {
        $filters = $request->validate([
            'export_format' => 'nullable|in:excel,pdf'
        ]);

        $compliance = [
            'required_documents' => $this->getRequiredDocumentsCompliance(),
            'expired_documents' => Document::where('expires_at', '<', now())->count(),
            'expiring_documents' => Document::where('expires_at', '>=', now())
                ->where('expires_at', '<=', now()->addDays(30))
                ->count(),
            'missing_documents' => $this->getMissingDocumentsCount(),
            'contract_compliance' => [
                'expired_contracts' => Contract::where('status', 'active')
                    ->where('end_date', '<', now())
                    ->count(),
                'expiring_contracts' => Contract::where('status', 'active')
                    ->where('end_date', '>=', now())
                    ->where('end_date', '<=', now()->addDays(30))
                    ->count(),
            ],
        ];

        if ($request->filled('export_format')) {
            return $this->exportComplianceReport($compliance, $filters['export_format']);
        }

        return view('reports.compliance-report', compact('compliance', 'filters'));
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
     * Get tenure group statistics based on hire date.
     */
    private function getTenureGroupStats()
    {
        $employees = Employee::whereNotNull('hire_date')->get();
        $tenureGroups = [
            '0-1 years' => 0,
            '1-3 years' => 0,
            '3-5 years' => 0,
            '5-10 years' => 0,
            '10+ years' => 0,
        ];

        foreach ($employees as $employee) {
            $tenureYears = $employee->hire_date->diffInYears(now());
            if ($tenureYears < 1) {
                $tenureGroups['0-1 years']++;
            } elseif ($tenureYears < 3) {
                $tenureGroups['1-3 years']++;
            } elseif ($tenureYears < 5) {
                $tenureGroups['3-5 years']++;
            } elseif ($tenureYears < 10) {
                $tenureGroups['5-10 years']++;
            } else {
                $tenureGroups['10+ years']++;
            }
        }

        return $tenureGroups;
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
            $html = view('reports.exports.demographics-pdf', compact('demographics'))->render();
            $mpdf = new Mpdf();
            $mpdf->WriteHTML($html);
            return response($mpdf->Output($filename . '.pdf', 'S'))
                ->header('Content-Type', 'application/pdf')
                ->header('Content-Disposition', 'attachment; filename="' . $filename . '.pdf"');
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
            $html = view('reports.exports.contract-status-pdf', compact('data', 'summary'))->render();
            $mpdf = new Mpdf();
            $mpdf->WriteHTML($html);
            return response($mpdf->Output($filename . '.pdf', 'S'))
                ->header('Content-Type', 'application/pdf')
                ->header('Content-Disposition', 'attachment; filename="' . $filename . '.pdf"');
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
            $html = view('reports.exports.payroll-summary-pdf', compact('data', 'summary', 'month'))->render();
            $mpdf = new Mpdf();
            $mpdf->WriteHTML($html);
            return response($mpdf->Output($filename . '.pdf', 'S'))
                ->header('Content-Type', 'application/pdf')
                ->header('Content-Disposition', 'attachment; filename="' . $filename . '.pdf"');
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
            $html = view('reports.exports.attendance-summary-pdf', compact('data', 'summary', 'month'))->render();
            $mpdf = new Mpdf();
            $mpdf->WriteHTML($html);
            return response($mpdf->Output($filename . '.pdf', 'S'))
                ->header('Content-Type', 'application/pdf')
                ->header('Content-Disposition', 'attachment; filename="' . $filename . '.pdf"');
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
            $html = view('reports.exports.document-inventory-pdf', compact('data', 'summary'))->render();
            $mpdf = new Mpdf();
            $mpdf->WriteHTML($html);
            return response($mpdf->Output($filename . '.pdf', 'S'))
                ->header('Content-Type', 'application/pdf')
                ->header('Content-Disposition', 'attachment; filename="' . $filename . '.pdf"');
        }
    }

    /**
     * Get required documents compliance data.
     */
    private function getRequiredDocumentsCompliance()
    {
        $employees = Employee::with('documents')->get();
        $requiredTypes = ['national_id', 'contract', 'bar_registration'];

        $compliance = [];
        foreach ($requiredTypes as $type) {
            $employeesWithType = $employees->filter(function ($employee) use ($type) {
                return $employee->documents->where('type', $type)->isNotEmpty();
            })->count();

            $compliance[$type] = [
                'total_employees' => $employees->count(),
                'compliant_employees' => $employeesWithType,
                'compliance_rate' => $employees->count() > 0 ? ($employeesWithType / $employees->count()) * 100 : 0,
            ];
        }

        return $compliance;
    }

    /**
     * Get missing documents count.
     */
    private function getMissingDocumentsCount()
    {
        $employees = Employee::with('documents')->get();
        $requiredTypes = ['national_id', 'contract'];

        $missingCount = 0;
        foreach ($employees as $employee) {
            foreach ($requiredTypes as $type) {
                if ($employee->documents->where('type', $type)->isEmpty()) {
                    $missingCount++;
                }
            }
        }

        return $missingCount;
    }

    /**
     * Export methods placeholder - implement as needed.
     */
    private function exportDepartmentAnalysis($analysis, $format)
    {
        // Implementation for department analysis export
        return response()->json(['message' => 'Department analysis export will be implemented']);
    }

    private function exportPositionAnalysis($analysis, $format)
    {
        // Implementation for position analysis export
        return response()->json(['message' => 'Position analysis export will be implemented']);
    }

    private function exportContractExpiry($contracts, $groupedContracts, $format)
    {
        // Implementation for contract expiry export
        return response()->json(['message' => 'Contract expiry export will be implemented']);
    }

    private function exportContractAnalysis($analysis, $format)
    {
        // Implementation for contract analysis export
        return response()->json(['message' => 'Contract analysis export will be implemented']);
    }

    private function exportSalaryAnalysis($analysis, $format)
    {
        // Implementation for salary analysis export
        return response()->json(['message' => 'Salary analysis export will be implemented']);
    }

    private function exportPayslipReport($payslips, $summary, $format, $month)
    {
        // Implementation for payslip report export
        return response()->json(['message' => 'Payslip report export will be implemented']);
    }

    private function exportOvertimeReport($overtimeData, $summary, $format, $month)
    {
        // Implementation for overtime report export
        return response()->json(['message' => 'Overtime report export will be implemented']);
    }

    private function exportAttendanceTrends($trends, $format, $months)
    {
        // Implementation for attendance trends export
        return response()->json(['message' => 'Attendance trends export will be implemented']);
    }

    private function exportDocumentExpiry($documents, $groupedDocuments, $format)
    {
        // Implementation for document expiry export
        return response()->json(['message' => 'Document expiry export will be implemented']);
    }

    private function exportComplianceReport($compliance, $format)
    {
        // Implementation for compliance report export
        return response()->json(['message' => 'Compliance report export will be implemented']);
    }
}