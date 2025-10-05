<?php

namespace App\Services;

use App\Models\Payslip;
use App\Models\PayrollRun;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\View;
use Mpdf\Mpdf;
use Mpdf\Output\Destination;
use Carbon\Carbon;

class PayslipPdfService
{
    protected array $config;

    public function __construct()
    {
        $this->config = [
            'mode' => 'utf-8',
            'format' => 'A4',
            'orientation' => 'P',
            'margin_left' => 15,
            'margin_right' => 15,
            'margin_top' => 16,
            'margin_bottom' => 16,
            'margin_header' => 9,
            'margin_footer' => 9,
            'default_font_size' => 10,
            'default_font' => 'dejavusans',
            'direction' => app()->getLocale() === 'ar' ? 'rtl' : 'ltr',
            'allow_charset_conversion' => true,
            'autoScriptToLang' => true,
            'autoLangToFont' => true,
        ];
    }

    /**
     * Generate PDF for a single payslip.
     */
    public function generatePayslipPdf(Payslip $payslip): bool
    {
        try {
            // Load relationships
            $payslip->load([
                'employee.department',
                'employee.position',
                'payrollRun',
                'salaryStructure',
                'payslipLines.salaryComponent'
            ]);

            // Generate the PDF content
            $html = $this->generatePayslipHtml($payslip);

            // Create PDF
            $mpdf = new Mpdf($this->config);

            // Set document info
            $mpdf->SetTitle("Payslip - {$payslip->employee_name} - {$payslip->pay_period}");
            $mpdf->SetAuthor('Sarie Eldin & Partners Legal Advisors');
            $mpdf->SetCreator('SEP HRMS');

            // Add header and footer
            $this->addHeaderFooter($mpdf, $payslip);

            // Write HTML to PDF
            $mpdf->WriteHTML($html);

            // Generate filename
            $filename = $this->generateFilename($payslip);

            // Save PDF
            $pdfContent = $mpdf->Output('', Destination::STRING_RETURN);
            $stored = Storage::disk('private')->put($filename, $pdfContent);

            if ($stored) {
                // Update payslip with PDF info
                $payslip->markPdfGenerated($filename);
                return true;
            }

            return false;
        } catch (\Exception $e) {
            Log::error("Failed to generate payslip PDF", [
                'payslip_id' => $payslip->id,
                'employee_code' => $payslip->employee_code,
                'error' => $e->getMessage()
            ]);
            return false;
        }
    }

    /**
     * Generate PDFs for all payslips in a payroll run.
     */
    public function generateBulkPayslipPdfs(PayrollRun $payrollRun): array
    {
        $payslips = $payrollRun->payslips()
            ->with([
                'employee.department',
                'employee.position',
                'payslipLines.salaryComponent'
            ])
            ->get();

        $results = [
            'total' => $payslips->count(),
            'success' => 0,
            'errors' => []
        ];

        foreach ($payslips as $payslip) {
            try {
                $success = $this->generatePayslipPdf($payslip);
                if ($success) {
                    $results['success']++;
                } else {
                    $results['errors'][] = "Failed to generate PDF for {$payslip->employee_name}";
                }
            } catch (\Exception $e) {
                $results['errors'][] = "Error generating PDF for {$payslip->employee_name}: {$e->getMessage()}";
            }
        }

        return $results;
    }

    /**
     * Add watermark to existing PDF.
     */
    public function addWatermark(Payslip $payslip, string $watermarkText): string
    {
        if (!$payslip->hasPdf()) {
            throw new \Exception('Payslip PDF does not exist');
        }

        try {
            // Read existing PDF
            $pdfContent = Storage::disk('private')->get($payslip->pdf_path);

            // Create new PDF with watermark
            $mpdf = new Mpdf($this->config);

            // Import existing PDF
            $pagecount = $mpdf->SetSourceFile($pdfContent);

            for ($i = 1; $i <= $pagecount; $i++) {
                $tplId = $mpdf->importPage($i);
                $mpdf->AddPage();
                $mpdf->useTemplate($tplId);

                // Add watermark
                $this->addWatermarkToPage($mpdf, $watermarkText);
            }

            // Generate watermarked filename
            $watermarkedFilename = 'temp/watermarked_' . time() . '_' . basename($payslip->pdf_path);

            // Save watermarked PDF
            $watermarkedContent = $mpdf->Output('', Destination::STRING_RETURN);
            Storage::disk('private')->put($watermarkedFilename, $watermarkedContent);

            return $watermarkedFilename;
        } catch (\Exception $e) {
            Log::error("Failed to add watermark to payslip PDF", [
                'payslip_id' => $payslip->id,
                'error' => $e->getMessage()
            ]);
            throw $e;
        }
    }

    /**
     * Generate HTML content for payslip.
     */
    protected function generatePayslipHtml(Payslip $payslip): string
    {
        // Group payslip lines by type
        $earnings = $payslip->payslipLines()->earnings()->ordered()->get();
        $deductions = $payslip->payslipLines()->deductions()->ordered()->get();
        $infoComponents = $payslip->payslipLines()->infoOnly()->ordered()->get();

        // Prepare data for view
        $data = [
            'payslip' => $payslip,
            'earnings' => $earnings,
            'deductions' => $deductions,
            'infoComponents' => $infoComponents,
            'generatedAt' => now(),
            'isRtl' => app()->getLocale() === 'ar',
        ];

        // Generate HTML using Blade template
        return View::make('pdf.payslip', $data)->render();
    }

    /**
     * Add header and footer to PDF.
     */
    protected function addHeaderFooter(Mpdf $mpdf, Payslip $payslip): void
    {
        // Header
        $headerHtml = View::make('pdf.payslip-header', [
            'payslip' => $payslip,
            'isRtl' => app()->getLocale() === 'ar'
        ])->render();

        $mpdf->SetHTMLHeader($headerHtml);

        // Footer
        $footerHtml = View::make('pdf.payslip-footer', [
            'payslip' => $payslip,
            'generatedAt' => now(),
            'isRtl' => app()->getLocale() === 'ar'
        ])->render();

        $mpdf->SetHTMLFooter($footerHtml);
    }

    /**
     * Add watermark to current page.
     */
    protected function addWatermarkToPage(Mpdf $mpdf, string $text): void
    {
        $mpdf->SetAlpha(0.3);
        $mpdf->Rotate(45, 105, 200);
        $mpdf->SetFont('Arial', 'B', 50);
        $mpdf->SetTextColor(255, 0, 0);
        $mpdf->Text(20, 200, $text);
        $mpdf->Rotate(0);
        $mpdf->SetAlpha(1);
        $mpdf->SetTextColor(0, 0, 0);
    }

    /**
     * Generate filename for payslip PDF.
     */
    protected function generateFilename(Payslip $payslip): string
    {
        $date = $payslip->pay_period_end->format('Y-m');
        $employeeCode = $payslip->employee_code;
        $timestamp = now()->format('YmdHis');

        return "payslips/{$date}/payslip_{$employeeCode}_{$date}_{$timestamp}.pdf";
    }

    /**
     * Generate consolidated payroll report PDF.
     */
    public function generatePayrollReportPdf(PayrollRun $payrollRun): string
    {
        try {
            // Load payroll run with relationships
            $payrollRun->load([
                'payslips.employee.department',
                'payslips.payslipLines.salaryComponent'
            ]);

            // Generate HTML content
            $html = View::make('pdf.payroll-report', [
                'payrollRun' => $payrollRun,
                'isRtl' => app()->getLocale() === 'ar',
                'generatedAt' => now(),
            ])->render();

            // Create PDF
            $mpdf = new Mpdf([
                ...$this->config,
                'format' => 'A4-L', // Landscape for wider report
            ]);

            // Set document info
            $mpdf->SetTitle("Payroll Report - {$payrollRun->title}");
            $mpdf->SetAuthor('Sarie Eldin & Partners Legal Advisors');
            $mpdf->SetCreator('SEP HRMS');

            // Write HTML to PDF
            $mpdf->WriteHTML($html);

            // Generate filename
            $filename = "reports/payroll_report_{$payrollRun->id}_" . now()->format('YmdHis') . ".pdf";

            // Save PDF
            $pdfContent = $mpdf->Output('', Destination::STRING_RETURN);
            Storage::disk('private')->put($filename, $pdfContent);

            return $filename;
        } catch (\Exception $e) {
            Log::error("Failed to generate payroll report PDF", [
                'payroll_run_id' => $payrollRun->id,
                'error' => $e->getMessage()
            ]);
            throw $e;
        }
    }

    /**
     * Generate year-end tax certificate PDF for employee.
     */
    public function generateTaxCertificatePdf(int $employeeId, int $year): string
    {
        try {
            // Get all payslips for employee in the year
            $payslips = Payslip::forEmployee($employeeId)
                ->whereYear('pay_date', $year)
                ->with(['employee.department', 'payslipLines'])
                ->get();

            if ($payslips->isEmpty()) {
                throw new \Exception('No payslips found for employee in specified year');
            }

            $employee = $payslips->first()->employee;

            // Calculate totals
            $totals = [
                'gross_annual' => $payslips->sum('gross_pay'),
                'net_annual' => $payslips->sum('net_pay'),
                'deductions_annual' => $payslips->sum('total_deductions'),
                'tax_deducted' => $payslips->flatMap->payslipLines
                    ->where('component_code', 'INCOME_TAX')
                    ->sum('amount'),
                'social_insurance' => $payslips->flatMap->payslipLines
                    ->where('component_code', 'SOCIAL_INSURANCE')
                    ->sum('amount'),
            ];

            // Generate HTML content
            $html = View::make('pdf.tax-certificate', [
                'employee' => $employee,
                'year' => $year,
                'payslips' => $payslips,
                'totals' => $totals,
                'isRtl' => app()->getLocale() === 'ar',
                'generatedAt' => now(),
            ])->render();

            // Create PDF
            $mpdf = new Mpdf($this->config);

            // Set document info
            $mpdf->SetTitle("Tax Certificate - {$employee->full_name} - {$year}");
            $mpdf->SetAuthor('Sarie Eldin & Partners Legal Advisors');
            $mpdf->SetCreator('SEP HRMS');

            // Write HTML to PDF
            $mpdf->WriteHTML($html);

            // Generate filename
            $filename = "certificates/tax_certificate_{$employee->code}_{$year}_" . now()->format('YmdHis') . ".pdf";

            // Save PDF
            $pdfContent = $mpdf->Output('', Destination::STRING_RETURN);
            Storage::disk('private')->put($filename, $pdfContent);

            return $filename;
        } catch (\Exception $e) {
            Log::error("Failed to generate tax certificate PDF", [
                'employee_id' => $employeeId,
                'year' => $year,
                'error' => $e->getMessage()
            ]);
            throw $e;
        }
    }
}