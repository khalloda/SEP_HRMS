<?php

namespace Tests\Unit\Payroll;

use App\Models\Employee;
use App\Models\SalaryComponent;
use App\Models\SalaryStructure;
use App\Models\SalaryStructureComponent;
use App\Services\SalaryHistoryService;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class SalaryHistoryServiceTest extends TestCase
{
    use DatabaseTransactions;

    /** @test */
    public function aggregates_earnings_and_deductions_and_masks_net_without_permission()
    {
        if (!config('payroll.use_salary_structure_history')) {
            $this->markTestSkipped('Flag disabled');
        }

        $employee = Employee::factory()->create();

        $earning = SalaryComponent::factory()->create(['comp_type' => 'earning']);
        $deduction = SalaryComponent::factory()->create(['comp_type' => 'deduction']);

        $structure = SalaryStructure::create([
            'employee_id' => $employee->id,
            'currency' => 'EGP',
            'effective_from' => now()->subMonth(),
            'effective_to' => now()->subDay(),
        ]);

        SalaryStructureComponent::create([
            'structure_id' => $structure->id,
            'component_id' => $earning->id,
            'value_numeric' => 1000,
            'priority_order' => 1,
        ]);
        SalaryStructureComponent::create([
            'structure_id' => $structure->id,
            'component_id' => $deduction->id,
            'value_numeric' => 200,
            'priority_order' => 2,
        ]);

        $service = app(SalaryHistoryService::class);
        $result = $service->fetch($employee, []);

        $this->assertNotEmpty($result['items']);
        $first = $result['items'][0];

        $this->assertEquals(1000.0, $first['totals']['earnings']);
        $this->assertEquals(200.0, $first['totals']['deductions']);
        // Without permission, these should be null
        $this->assertNull($first['totals']['gross']);
        $this->assertNull($first['totals']['net']);
    }
}
