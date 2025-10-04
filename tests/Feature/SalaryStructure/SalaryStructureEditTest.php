<?php

namespace Tests\Feature\SalaryStructure;

use App\Models\Employee;
use App\Models\SalaryComponent;
use App\Models\SalaryStructure;
use App\Models\SalaryStructureComponent;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Str;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class SalaryStructureEditTest extends TestCase
{
    use DatabaseTransactions;

    protected function setUp(): void
    {
        parent::setUp();

        Role::firstOrCreate(['name' => 'HR_Admin_Manager', 'guard_name' => 'web']);
    }

    private function createManager(): User
    {
        $user = User::create([
            'name' => 'HR Manager',
            'email' => 'hr-manager-' . uniqid() . '@example.com',
            'password' => bcrypt('password123'),
        ]);

        $user->assignRole('HR_Admin_Manager');

        return $user;
    }

    private function createEmployee(): Employee
    {
        return Employee::create([
            'code' => 'EMP-' . strtoupper(Str::random(6)),
            'first_name' => 'Edit',
            'last_name' => 'Check',
            'status' => 'active',
        ]);
    }

    public function test_edit_view_includes_seeded_component_payload(): void
    {
        config()->set('payroll.enabled', true);

        $manager = $this->createManager();
        $employee = $this->createEmployee();

        $basic = SalaryComponent::firstOrCreate([
            'code' => 'BASIC_SALARY',
        ], [
            'name_en' => 'Basic Salary',
            'name_ar' => 'الراتب الأساسي',
            'comp_type' => 'earning',
            'calc_mode' => 'fixed',
            'taxable' => true,
            'priority_order' => 1,
        ]);

        $housing = SalaryComponent::firstOrCreate([
            'code' => 'HOUSING_ALLOWANCE',
        ], [
            'name_en' => 'Housing Allowance',
            'name_ar' => 'بدل السكن',
            'comp_type' => 'earning',
            'calc_mode' => 'fixed',
            'taxable' => true,
            'priority_order' => 2,
        ]);

        $structure = SalaryStructure::create([
            'employee_id' => $employee->id,
            'currency' => 'EGP',
            'effective_from' => Carbon::parse('2099-01-01'),
            'effective_to' => null,
            'notes' => 'Testing structure',
        ]);

        SalaryStructureComponent::create([
            'structure_id' => $structure->id,
            'component_id' => $basic->id,
            'value_numeric' => 10000,
            'priority_order' => 1,
        ]);

        SalaryStructureComponent::create([
            'structure_id' => $structure->id,
            'component_id' => $housing->id,
            'value_numeric' => 2500,
            'priority_order' => 2,
        ]);

        $response = $this->actingAs($manager)->get(route('employees.salary-structures.edit', [$employee, $structure]));

        $response->assertOk();

        $parsed = json_decode($response->viewData('salaryStructure')->structureComponents->mapWithKeys(fn ($component) => [
            (string) $component->component_id => [
                'component_id' => $component->component_id,
                'value_numeric' => $component->value_numeric,
                'formula_expr' => $component->formula_expr,
                'priority_order' => $component->priority_order,
            ],
        ])->toJson(), true);

        $this->assertArrayHasKey((string) $basic->id, $parsed);
        $this->assertArrayHasKey((string) $housing->id, $parsed);
        $this->assertSame(10000.0, (float) $parsed[(string) $basic->id]['value_numeric']);
        $this->assertSame(2500.0, (float) $parsed[(string) $housing->id]['value_numeric']);
    }
}
