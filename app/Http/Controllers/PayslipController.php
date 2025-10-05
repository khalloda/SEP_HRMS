<?php

namespace App\Http\Controllers;

use App\Models\Payslip;
use App\Models\PayrollRun;
use App\Models\Employee;
use App\Services\PayslipPdfService;
use App\Exports\ArrayExport;
use App\Exports\PayslipCsvExport;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Carbon\Carbon;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Facades\Excel;

class PayslipController extends Controller
{
    protected PayslipPdfService $pdfService;

    public function __construct(PayslipPdfService $pdfService)
    {
        $this->pdfService = $pdfService;
    }

    /**
     * Display a listing of payslips for an employee.
     */
    public function index(Request $request, Employee $employee = null)
    {
        Gate::authorize('viewAny', Payslip::class);

        $query = Payslip::query()->with(['employee.department', 'employee.position', 'payrollRun']);

        // Filter by employee if provided
        if ($employee) {
            Gate::authorize('view', $employee);
            $query->forEmployee($employee->id);
        }

        // Apply filters
        if ($request->filled('payroll_run_id')) {
            $query->where('payroll_run_id', $request->get('payroll_run_id'));
        }

        if ($request->filled('year')) {
            $year = $request->get('year');
            $query->whereYear('pay_date', $year);
        }

        if ($request->filled('month')) {
            $month = $request->get('month');
            $query->whereMonth('pay_date', $month);
        }

        if ($request->filled('status')) {
            $query->byStatus($request->get('status'));
        }

        // Apply search (only if not filtered by specific employee)
        if (!$employee && $request->filled('search')) {
            $search = $request->get('search');
            $query->where(function ($q) use ($search) {
                $q->where('employee_name', 'like', "%{$search}%")
                  ->orWhere('employee_code', 'like', "%{$search}%")
                  ->orWhere('employee_arabic_name', 'like', "%{$search}%");
            });
        }

        $payslips = $query->recent()->paginate(20)->appends($request->query());

        // Filter options
        $payrollRuns = PayrollRun::orderBy('pay_period_end', 'desc')->limit(12)->get();
        $statusOptions = Payslip::STATUSES;
        $yearOptions = range(date('Y') - 2, date('Y') + 1);
        $monthOptions = [
            1 => 'January', 2 => 'February', 3 => 'March', 4 => 'April',
            5 => 'May', 6 => 'June', 7 => 'July', 8 => 'August',
            9 => 'September', 10 => 'October', 11 => 'November', 12 => 'December'
        ];

        return view('payslips.index', compact(
            'payslips', 'employee', 'payrollRuns', 'statusOptions', 'yearOptions', 'monthOptions'
        ));
    }

    /**
     * Display the specified payslip.
     */
    public function show(Payslip $payslip)
    {
        Gate::authorize('viewPayslip', $payslip);

        $payslip->load([
            'employee.department',
            'employee.position',
            'payrollRun',
            'salaryStructure',
            'payslipLines.salaryComponent'
        ]);

        // Group payslip lines by type
        $earnings = $payslip->payslipLines()->earnings()->ordered()->get();
        $deductions = $payslip->payslipLines()->deductions()->ordered()->get();
        $infoComponents = $payslip->payslipLines()->infoOnly()->ordered()->get();

        // Check if user can view net/gross amounts
        $canViewNetGross = Gate::allows('viewNetGross', $payslip);

        // Mark as viewed if employee is viewing their own payslip
        if (auth()->user()->employee &&
            auth()->user()->employee->id === $payslip->employee_id) {
            $payslip->markAsViewed();
        }

        return view('payslips.show', compact(
            'payslip', 'earnings', 'deductions', 'infoComponents', 'canViewNetGross'
        ));
    }

    /**
     * Download payslip as PDF.
     */
    public function downloadPdf(Payslip $payslip)
    {
        Gate::authorize('viewPayslip', $payslip);

        // Generate PDF if it doesn't exist or is outdated
        if (!$payslip->hasPdf() || $this->isPdfOutdated($payslip)) {
            $this->pdfService->generatePayslipPdf($payslip);
        }

        // Check if user can view net/gross amounts for watermarking
        $canViewNetGross = Gate::allows('viewNetGross', $payslip);

        // Add watermark for restricted users
        if (!$canViewNetGross) {
            $watermarkedPath = $this->pdfService->addWatermark(
                $payslip,
                __('hrms.confidential_hr_use_only')
            );

            return response()->download(
                storage_path('app/private/' . $watermarkedPath),
                "Payslip-{$payslip->employee_code}-{$payslip->pay_period_end->format('Y-m')}.pdf"
            )->deleteFileAfterSend();
        }

        // Mark as viewed if employee is downloading their own payslip
        if (auth()->user()->employee &&
            auth()->user()->employee->id === $payslip->employee_id) {
            $payslip->markAsViewed();
        }

        return response()->download(
            storage_path('app/private/' . $payslip->pdf_path),
            "Payslip-{$payslip->employee_code}-{$payslip->pay_period_end->format('Y-m')}.pdf"
        );
    }

    /**
     * Generate PDF for a payslip.
     */
    public function generatePdf(Payslip $payslip)
    {
        Gate::authorize('viewPayslip', $payslip);

        $success = $this->pdfService->generatePayslipPdf($payslip);

        if ($success) {
            return redirect()->route('payslips.show', $payslip)
                ->with('success', __('hrms.payslip.pdf_generated_successfully'));
        } else {
            return redirect()->route('payslips.show', $payslip)
                ->with('error', __('hrms.payslip.pdf_generation_failed'));
        }
    }

    /**
     * Generate PDFs for all payslips in a payroll run.
     */
    public function generateBulkPdf(PayrollRun $payrollRun)
    {
        Gate::authorize('view', $payrollRun);

        $results = $this->pdfService->generateBulkPayslipPdfs($payrollRun);

        $message = __('hrms.payslip.bulk_pdf_generated', [
            'success' => $results['success'],
            'total' => $results['total']
        ]);

        if ($results['success'] === $results['total']) {
            return redirect()->route('payroll.payslips', $payrollRun)
                ->with('success', $message);
        } else {
            return redirect()->route('payroll.payslips', $payrollRun)
                ->with('warning', $message)
                ->with('pdf_errors', $results['errors']);
        }
    }

    /**
     * Send payslip via email to employee.
     */
    public function sendEmail(Payslip $payslip)
    {
        Gate::authorize('viewPayslip', $payslip);
        Gate::authorize('sendEmail', $payslip);

        // Check if employee has email
        if (!$payslip->employee->email) {
            return redirect()->route('payslips.show', $payslip)
                ->with('error', __('hrms.payslip.employee_no_email'));
        }

        // Generate PDF if needed
        if (!$payslip->hasPdf()) {
            $this->pdfService->generatePayslipPdf($payslip);
        }

        // Send email (this would integrate with your email service)
        $sent = $this->sendPayslipEmail($payslip);

        if ($sent) {
            $payslip->markAsSent();
            return redirect()->route('payslips.show', $payslip)
                ->with('success', __('hrms.payslip.sent_successfully'));
        } else {
            return redirect()->route('payslips.show', $payslip)
                ->with('error', __('hrms.payslip.send_failed'));
        }
    }

    /**
     * Send bulk emails for all payslips in a payroll run.
     */
    public function sendBulkEmail(PayrollRun $payrollRun)
    {
        Gate::authorize('view', $payrollRun);

        $payslips = $payrollRun->payslips()
            ->whereHas('employee', function ($query) {
                $query->whereNotNull('email');
            })
            ->get();

        $results = [
            'total' => $payslips->count(),
            'sent' => 0,
            'errors' => []
        ];

        foreach ($payslips as $payslip) {
            try {
                // Generate PDF if needed
                if (!$payslip->hasPdf()) {
                    $this->pdfService->generatePayslipPdf($payslip);
                }

                // Send email
                $sent = $this->sendPayslipEmail($payslip);

                if ($sent) {
                    $payslip->markAsSent();
                    $results['sent']++;
                } else {
                    $results['errors'][] = "Failed to send to {$payslip->employee_name}";
                }
            } catch (\Exception $e) {
                $results['errors'][] = "Error sending to {$payslip->employee_name}: {$e->getMessage()}";
            }
        }

        $message = __('hrms.payslip.bulk_email_sent', [
            'sent' => $results['sent'],
            'total' => $results['total']
        ]);

        if ($results['sent'] === $results['total']) {
            return redirect()->route('payroll.payslips', $payrollRun)
                ->with('success', $message);
        } else {
            return redirect()->route('payroll.payslips', $payrollRun)
                ->with('warning', $message)
                ->with('email_errors', $results['errors']);
        }
    }


/**
 * Export payslips data.
 */
public function export(Request $request, PayrollRun $payrollRun = null)
{
    Gate::authorize('export', Payslip::class);

    $format = strtolower($request->get('format', 'excel'));

    if ($payrollRun) {
        $payslips = $payrollRun->payslips()
            ->with(['employee.department', 'employee.position', 'payrollRun'])
            ->get();
        $baseFilename = Str::slug($payrollRun->title ?: 'payroll-run-' . $payrollRun->id) . '-' . now()->format('Y-m-d');
    } else {
        $query = Payslip::query()->with(['employee.department', 'employee.position', 'payrollRun']);

        if ($request->filled('payroll_run_id')) {
            $query->where('payroll_run_id', $request->get('payroll_run_id'));
        }

        if ($request->filled('status')) {
            $query->byStatus($request->get('status'));
        }

        $payslips = $query->get();
        $baseFilename = 'payslips-' . now()->format('Y-m-d');
    }

    if ($payslips->isEmpty()) {
        return back()->with('warning', __('No payslips found for export.'));
    }

    return match ($format) {
        'excel' => $this->exportToExcel($payslips, $baseFilename),
        'csv' => $this->exportToCsv($payslips, $baseFilename),
        'pdf' => $this->exportToPdf($payslips, $baseFilename),
        default => back()->with('warning', __('Unsupported export format.')),
    };
}
    /**
     * Get payslip statistics.
     */
    public function statistics(Request $request)
    {
        Gate::authorize('statistics', Payslip::class);

        $currentYear = date('Y');
        $currentMonth = date('m');

        $stats = [
            'total_payslips' => Payslip::count(),
            'payslips_this_year' => Payslip::whereYear('pay_date', $currentYear)->count(),
            'payslips_this_month' => Payslip::whereYear('pay_date', $currentYear)
                ->whereMonth('pay_date', $currentMonth)->count(),
            'generated_pdfs' => Payslip::whereNotNull('pdf_path')->count(),
            'sent_payslips' => Payslip::where('status', Payslip::STATUS_SENT)->count(),
            'viewed_payslips' => Payslip::where('status', Payslip::STATUS_VIEWED)->count(),
            'by_month' => Payslip::whereYear('pay_date', $currentYear)
                ->selectRaw('MONTH(pay_date) as month, COUNT(*) as count, SUM(net_pay) as total_net')
                ->groupBy('month')
                ->get(),
            'by_department' => Payslip::join('employees', 'payslips.employee_id', '=', 'employees.id')
                ->join('departments', 'employees.department_id', '=', 'departments.id')
                ->whereYear('payslips.pay_date', $currentYear)
                ->selectRaw('departments.name_en as department, COUNT(*) as count, SUM(payslips.net_pay) as total_net')
                ->groupBy('departments.id', 'departments.name_en')
                ->get(),
        ];

        return response()->json($stats);
    }

    /**
     * Check if PDF is outdated compared to payslip data.
     */
    protected function isPdfOutdated(Payslip $payslip): bool
    {
        return $payslip->pdf_generated_at &&
               $payslip->updated_at > $payslip->pdf_generated_at;
    }

    /**
     * Send payslip email to employee.
     */
    protected function sendPayslipEmail(Payslip $payslip): bool
    {
        // This would integrate with your email service (e.g., Laravel Mail)
        // For now, return true as placeholder
        //
        // Example implementation:
        // Mail::to($payslip->employee->email)
        //     ->send(new PayslipMail($payslip));

        return true;
    }


    private function exportToExcel($payslips, string $baseFilename)
    {
        $headings = $this->payslipExportHeadings();
        $rows = $payslips->map(fn (Payslip $payslip) => array_values($this->mapPayslipRow($payslip)))->all();

        return Excel::download(new ArrayExport($headings, $rows), $baseFilename . '.xlsx');
    }

    private function exportToCsv($payslips, string $baseFilename)
    {
        $headings = $this->payslipExportHeadings();
        $rows = $payslips->map(fn (Payslip $payslip) => $this->mapPayslipRow($payslip))->all();

        return PayslipCsvExport::download($headings, $rows, $baseFilename . '.csv');
    }

    private function exportToPdf($payslips, string $baseFilename)
    {
        $zip = new ZipArchive();
        $tmpFile = tempnam(sys_get_temp_dir(), 'payslips_export_');

        if ($zip->open($tmpFile, ZipArchive::CREATE | ZipArchive::OVERWRITE) !== true) {
            abort(500, __('Unable to prepare payslip archive.'));
        }

        foreach ($payslips as $payslip) {
            if (!$payslip->hasPdf() || $this->isPdfOutdated($payslip)) {
                $this->pdfService->generatePayslipPdf($payslip);
                $payslip->refresh();
            }

            if ($payslip->pdf_path && Storage::disk('private')->exists($payslip->pdf_path)) {
                $zip->addFromString($this->zipEntryFilename($payslip), Storage::disk('private')->get($payslip->pdf_path));
            }
        }

        if ($zip->numFiles === 0) {
            $zip->close();
            @unlink($tmpFile);
            abort(422, __('No payslip PDFs available to export.'));
        }

        $zip->close();

        return response()->streamDownload(function () use ($tmpFile) {
            $stream = fopen($tmpFile, 'rb');
            fpassthru($stream);
            fclose($stream);
            @unlink($tmpFile);
        }, $baseFilename . '.zip', [
            'Content-Type' => 'application/zip',
        ]);
    }

    private function payslipExportHeadings(): array
    {
        return [
            __('Employee Code'),
            __('Employee Name'),
            __('Department'),
            __('Position'),
            __('Pay Period Start'),
            __('Pay Period End'),
            __('Pay Date'),
            __('Gross Pay'),
            __('Total Deductions'),
            __('Net Pay'),
            __('Currency'),
            __('Status'),
        ];
    }

    private function mapPayslipRow(Payslip $payslip): array
    {
        $department = optional(optional($payslip->employee)->department)->name_en ?? $payslip->department_name ?? '-';
        $position = optional(optional($payslip->employee)->position)->name_en ?? $payslip->position_name ?? '-';

        return [
            $payslip->employee_code,
            $payslip->employee_name,
            $department,
            $position,
            optional($payslip->pay_period_start)->format('Y-m-d') ?? '',
            optional($payslip->pay_period_end)->format('Y-m-d') ?? '',
            optional($payslip->pay_date)->format('Y-m-d') ?? '',
            number_format((float) $payslip->gross_pay, 2, '.', ''),
            number_format((float) $payslip->total_deductions, 2, '.', ''),
            number_format((float) $payslip->net_pay, 2, '.', ''),
            $payslip->currency ?? '',
            $payslip->status,
        ];
    }

    private function zipEntryFilename(Payslip $payslip): string
    {
        $code = Str::slug($payslip->employee_code ?? ('employee-' . $payslip->employee_id));
        $period = optional($payslip->pay_period_end)->format('Y-m') ?? now()->format('Y-m');

        return $code . '_' . $period . '.pdf';
    }
}

