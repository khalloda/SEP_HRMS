<?php

namespace Tests\Feature\Payroll;

use App\Exports\ArrayExport;
use App\Exports\PayslipCsvExport;
use App\Models\Department;
use App\Models\Employee;
use App\Models\EmploymentType;
use App\Models\PayrollRun;
use App\Models\Payslip;
use App\Models\Position;
use App\Models\User;
use App\Services\PayslipPdfService;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;
use Mockery;
use Spatie\Permission\Models\Role;
use Tests\TestCase;
use ZipArchive;

class PayslipExportTest extends TestCase
{
    use DatabaseTransactions;

    public function tearDown(): void
    {
        parent::tearDown();
        Mockery::close();
    }

    public function test_admin_can_download_filtered_excel_export(): void
    {
        Config::set('payroll.enabled', true);
        Role::firstOrCreate(['name' => 'HR_Admin_Manager', 'guard_name' => 'web']);

        $user = User::create([
            'name' => 'Payroll Admin',
            'email' => 'payslip-export-' . uniqid() . '@example.com',
            'password' => bcrypt('password123'),
        ]);
        $user->assignRole('HR_Admin_Manager');

        $payrollRun = $this->createPayrollRunWithPayslip($user);

        Excel::fake();

        $response = $this->actingAs($user)->get(route('payslips.export', [
            'format' => 'excel',
            'payroll_run_id' => $payrollRun->id,
        ]));

        $response->assertOk();

        Excel::assertDownloaded('payslips-' . now()->format('Y-m-d') . '.xlsx', function ($export) {
            $this->assertInstanceOf(ArrayExport::class, $export);
            $rows = $export->array();
            $this->assertNotEmpty($rows);
            $this->assertSame('EMP-TEST', $rows[0][0]);

            return true;
        });
    }

    public function test_admin_can_download_csv_export(): void
    {
        Config::set('payroll.enabled', true);
        Role::firstOrCreate(['name' => 'HR_Admin_Manager', 'guard_name' => 'web']);

        $user = User::create([
            'name' => 'Payroll Admin',
            'email' => 'payslip-export-' . uniqid() . '@example.com',
            'password' => bcrypt('password123'),
        ]);
        $user->assignRole('HR_Admin_Manager');

        $payrollRun = $this->createPayrollRunWithPayslip($user);

        $response = $this->actingAs($user)->get(route('payslips.export', [
            'format' => 'csv',
            'payroll_run_id' => $payrollRun->id,
        ]));

        $response->assertOk();
        $content = $response->streamedContent();
        $this->assertStringContainsString('Employee Code', $content);
        $this->assertStringContainsString('EMP-TEST', $content);
    }

    public function test_admin_can_download_pdf_archive(): void
    {
        Config::set('payroll.enabled', true);
        Role::firstOrCreate(['name' => 'HR_Admin_Manager', 'guard_name' => 'web']);

        $user = User::create([
            'name' => 'Payroll Admin',
            'email' => 'payslip-export-' . uniqid() . '@example.com',
            'password' => bcrypt('password123'),
        ]);
        $user->assignRole('HR_Admin_Manager');

        $payrollRun = $this->createPayrollRunWithPayslip($user);

        Storage::fake('private');

        $pdfService = Mockery::mock(PayslipPdfService::class);
        $pdfService->shouldReceive('generatePayslipPdf')->andReturnUsing(function (Payslip $payslip) {
            $path = 'payslips/' . $payslip->id . '.pdf';
            Storage::disk('private')->put($path, 'PDF CONTENT');
            $payslip->forceFill([
                'pdf_path' => $path,
                'pdf_generated_at' => now(),
            ])->save();

            return true;
        });

        app()->instance(PayslipPdfService::class, $pdfService);

        $response = $this->actingAs($user)->get(route('payslips.export', [
            'format' => 'pdf',
            'payroll_run_id' => $payrollRun->id,
        ]));

        $response->assertOk();

        $zipContent = $response->streamedContent();
        $tempZip = tempnam(sys_get_temp_dir(), 'payslips_zip_');
        file_put_contents($tempZip, $zipContent);

        $zip = new ZipArchive();
        $openResult = $zip->open($tempZip);
        $this->assertTrue($openResult === true);
        $this->assertSame(1, $zip->numFiles);
        $this->assertStringEndsWith('.pdf', $zip->getNameIndex(0));
        $zip->close();

        @unlink($tempZip);
    }

    private function createPayrollRunWithPayslip(User $creator): PayrollRun
    {
        $department = Department::create([
            'code' => 'FIN-' . uniqid(),
            'name_en' => 'Finance',
            'name_ar' => '???????',
        ]);

        $position = Position::create([
            'name_en' => 'Accountant',
            'name_ar' => '?????',
            'category' => 1,
            'grade_order' => 1,
        ]);

        $employmentType = EmploymentType::firstOrCreate(
            ['name_en' => 'Permanent'],
            ['name_ar' => '????']
        );

        $employee = Employee::create([
            'code' => 'EMP-TEST',
            'first_name' => 'Jane',
            'last_name' => 'Doe',
            'arabic_name' => '??? ??',
            'email' => 'employee-' . uniqid() . '@example.com',
            'phone' => '0100000000',
            'hire_date' => now()->subYear()->toDateString(),
            'status' => 'active',
            'department_id' => $department->id,
            'position_id' => $position->id,
            'employment_type_id' => $employmentType->id,
            'manager_id' => null,
            'national_id' => str_pad((string) random_int(1, 99999999999999), 14, '0', STR_PAD_LEFT),
            'salary_visibility_flag' => true,
        ]);

        $periodStart = now()->startOfMonth();
        $periodEnd = $periodStart->copy()->endOfMonth();

        $payrollRun = PayrollRun::create([
            'title' => 'Monthly Payroll',
            'description' => 'Automated test payroll run.',
            'pay_period_start' => $periodStart->toDateString(),
            'pay_period_end' => $periodEnd->toDateString(),
            'pay_date' => $periodEnd->copy()->addDays(5)->toDateString(),
            'status' => PayrollRun::STATUS_CALCULATED,
            'currency' => 'SAR',
            'total_employees' => 1,
            'total_gross' => 5000,
            'total_net' => 4500,
            'total_deductions' => 500,
            'created_by' => $creator->id,
        ]);

        Payslip::create([
            'payroll_run_id' => $payrollRun->id,
            'employee_id' => $employee->id,
            'salary_structure_id' => null,
            'employee_code' => $employee->code,
            'employee_name' => $employee->full_name,
            'employee_arabic_name' => $employee->arabic_name,
            'department_name' => $department->name_en,
            'position_name' => $position->name_en,
            'pay_period_start' => $periodStart->toDateString(),
            'pay_period_end' => $periodEnd->toDateString(),
            'pay_date' => $payrollRun->pay_date,
            'currency' => $payrollRun->currency,
            'gross_pay' => 5000,
            'total_deductions' => 500,
            'net_pay' => 4500,
            'basic_salary' => 3000,
            'status' => Payslip::STATUS_GENERATED,
            'generated_at' => now(),
        ]);

        return $payrollRun;
    }
}
