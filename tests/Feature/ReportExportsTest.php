<?php

namespace Tests\Feature;

use App\Jobs\GenerateReportExport;
use App\Models\Contract;
use App\Models\Payslip;
use App\Models\PayrollRun;
use App\Models\Department;
use App\Models\Employee;
use App\Models\EmploymentType;
use App\Models\Position;
use App\Models\User;
use App\Services\Reports\ReportExportService;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Queue;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Tests\TestCase;

class ReportExportsTest extends TestCase
{
    use DatabaseTransactions;

    protected function setUp(): void
    {
        parent::setUp();

        Artisan::call('db:seed', ['--class' => 'RolesAndPermissionsSeeder']);
        config([
            'reports.use_new_exports' => true,
            'reports.force_queue' => true,
            'reports.definitions.employee-list.queue_threshold' => 0,
            'reports.definitions.contract-status.queue_threshold' => 0,
            'app.locale' => 'en',
        ]);
    }

    public function test_employee_list_export_is_queued_and_completes(): void
    {
        Storage::fake('private');
        Cache::flush();
        Queue::fake();

        $department = Department::firstOrCreate([
            'code' => 'DEP001',
        ], [
            'name_en' => 'Litigation',
            'name_ar' => 'Litigation',
        ]);

        $position = Position::firstOrCreate([
            'name_en' => 'Associate',
            'category' => 'lawyer',
        ], [
            'name_ar' => 'Associate',
        ]);

        $employmentType = EmploymentType::firstOrCreate([
            'name_en' => 'Full-time',
            'name_ar' => 'Full-time',
        ]);

        foreach (range(1, 3) as $i) {
            Employee::create([
                'code' => 'EMP' . str_pad((string) $i, 3, '0', STR_PAD_LEFT) . Str::upper(Str::random(4)),
                'first_name' => 'John' . $i,
                'last_name' => 'Doe',
                'email' => "john{$i}@example.com",
                'phone' => '0100000000' . $i,
                'hire_date' => now()->subMonths($i),
                'status' => 'active',
                'department_id' => $department->id,
                'position_id' => $position->id,
                'employment_type_id' => $employmentType->id,
            ]);
        }

        $user = User::firstOrCreate(
            ['email' => 'report@test.com'],
            [
                'name' => 'Report Runner',
                'password' => bcrypt('password123'),
            ]
        );
        $user->syncRoles([]);
        $user->givePermissionTo('reports.view');

        $service = app(ReportExportService::class);

        $result = $service->start('employee-list', [
            'export_format' => 'excel',
        ], 'excel', $user);

        $this->assertIsArray($result);
        $this->assertEquals(ReportExportService::STATUS_QUEUED, $result['status']);

        Queue::assertPushed(GenerateReportExport::class, function (GenerateReportExport $job) use ($service) {
            $job->handle($service);
            return true;
        });

        $state = $service->getStatus($result['correlation_id']);

        $this->assertEquals(ReportExportService::STATUS_COMPLETED, $state['status']);
        $this->assertNotEmpty($state['download_path']);
        Storage::disk('private')->assertExists($state['download_path']);
    }

    public function test_contract_status_export_is_queued_and_completes(): void
    {
        Storage::fake('private');
        Cache::flush();
        Queue::fake();

        $department = Department::firstOrCreate([
            'code' => 'DEP001',
        ], [
            'name_en' => 'Litigation',
            'name_ar' => 'Litigation',
        ]);

        $position = Position::firstOrCreate([
            'name_en' => 'Associate',
            'category' => 'lawyer',
        ], [
            'name_ar' => 'Associate',
        ]);

        $employmentType = EmploymentType::firstOrCreate([
            'name_en' => 'Full-time',
            'name_ar' => 'Full-time',
        ]);

        $employee = Employee::create([
            'code' => 'EMP' . Str::upper(Str::random(6)),
            'first_name' => 'Contract',
            'last_name' => 'Owner',
            'email' => 'contract-owner@example.com',
            'phone' => '01000000000',
            'hire_date' => now()->subYears(2),
            'status' => 'active',
            'department_id' => $department->id,
            'position_id' => $position->id,
            'employment_type_id' => $employmentType->id,
        ]);

        Contract::create([
            'employee_id' => $employee->id,
            'type' => 'permanent',
            'start_date' => now()->subYear(),
            'end_date' => now()->addMonths(6),
            'status' => 'active',
        ]);

        $user = User::firstOrCreate(
            ['email' => 'report@test.com'],
            [
                'name' => 'Report Runner',
                'password' => bcrypt('password123'),
            ]
        );
        $user->syncRoles([]);
        $user->givePermissionTo('reports.view');

        $service = app(ReportExportService::class);

        $result = $service->start('contract-status', [
            'export_format' => 'excel',
        ], 'excel', $user);

        $this->assertIsArray($result);
        $this->assertEquals(ReportExportService::STATUS_QUEUED, $result['status']);

        Queue::assertPushed(GenerateReportExport::class, function (GenerateReportExport $job) use ($service) {
            $job->handle($service);
            return true;
        });

        $state = $service->getStatus($result['correlation_id']);

        $this->assertEquals(ReportExportService::STATUS_COMPLETED, $state['status']);
        $this->assertNotEmpty($state['download_path']);
        Storage::disk('private')->assertExists($state['download_path']);
    }

    public function test_payroll_summary_export_is_queued_and_completes(): void
    {
        Storage::fake('private');
        Cache::flush();
        Queue::fake();

        $department = Department::firstOrCreate([
            'code' => 'DEP001',
        ], [
            'name_en' => 'Litigation',
            'name_ar' => 'Litigation',
        ]);

        $position = Position::firstOrCreate([
            'name_en' => 'Associate',
            'category' => 'lawyer',
        ], [
            'name_ar' => 'Associate',
        ]);

        $employmentType = EmploymentType::firstOrCreate([
            'name_en' => 'Full-time',
            'name_ar' => 'Full-time',
        ]);

        $employee = Employee::create([
            'code' => 'EMP' . Str::upper(Str::random(6)),
            'first_name' => 'Payroll',
            'last_name' => 'Analyst',
            'email' => 'payroll-analyst@example.com',
            'phone' => '01000000001',
            'hire_date' => now()->subYears(2),
            'status' => 'active',
            'department_id' => $department->id,
            'position_id' => $position->id,
            'employment_type_id' => $employmentType->id,
        ]);

        $user = User::firstOrCreate(
            ['email' => 'report@test.com'],
            [
                'name' => 'Report Runner',
                'password' => bcrypt('password123'),
            ]
        );
        $user->syncRoles([]);
        $user->givePermissionTo('reports.view');

        $payrollRun = PayrollRun::create([
            'title' => 'Test Payroll',
            'description' => 'Autogenerated for tests',
            'pay_period_start' => now()->startOfMonth(),
            'pay_period_end' => now()->endOfMonth(),
            'pay_date' => now()->endOfMonth(),
            'status' => 'approved',
            'currency' => 'USD',
            'total_employees' => 1,
            'total_gross' => 1000,
            'total_net' => 900,
            'total_deductions' => 100,
            'created_by' => $user->id,
        ]);

        Payslip::create([
            'payroll_run_id' => $payrollRun->id,
            'employee_id' => $employee->id,
            'employee_code' => $employee->code,
            'employee_name' => $employee->display_name,
            'pay_period_start' => now()->startOfMonth(),
            'pay_period_end' => now()->endOfMonth(),
            'pay_date' => now()->endOfMonth(),
            'gross_pay' => 1000,
            'net_pay' => 900,
            'total_deductions' => 100,
            'status' => 'generated',
        ]);

        $service = app(ReportExportService::class);

        $result = $service->start('payroll-summary', [
            'export_format' => 'excel',
            'month' => now()->format('Y-m'),
        ], 'excel', $user);

        $this->assertIsArray($result);
        $this->assertEquals(ReportExportService::STATUS_QUEUED, $result['status']);

        Queue::assertPushed(GenerateReportExport::class, function (GenerateReportExport $job) use ($service) {
            $job->handle($service);
            return true;
        });

        $state = $service->getStatus($result['correlation_id']);

        $this->assertEquals(ReportExportService::STATUS_COMPLETED, $state['status']);
        $this->assertNotEmpty($state['download_path']);
        Storage::disk('private')->assertExists($state['download_path']);
    }

}
