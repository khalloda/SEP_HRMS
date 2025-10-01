<?php

namespace Tests\Feature;

use App\Jobs\GenerateReportExport;
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
}



