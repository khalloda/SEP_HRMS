<?php

namespace Tests\Feature\SalaryStructure;

use App\Models\Employee;
use App\Models\SalaryStructure;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class SalaryStructureViewTest extends TestCase
{
    use DatabaseTransactions;

    protected function setUp(): void
    {
        parent::setUp();

        Role::firstOrCreate(['name' => 'HR_Admin_Manager', 'guard_name' => 'web']);
        Role::firstOrCreate(['name' => 'HR_Coordinator', 'guard_name' => 'web']);
    }

    public function test_index_renders_structures_for_employee(): void
    {
        config()->set('payroll.enabled', true);

        $user = User::factory()->create();
        $user->assignRole('HR_Admin_Manager');

        $employee = Employee::factory()->create();

        SalaryStructure::factory()->create([
            'employee_id' => $employee->id,
            'currency' => 'EGP',
            'effective_from' => Carbon::parse('2099-01-01'),
            'effective_to' => null,
        ]);

        $response = $this->actingAs($user)->get(route('employees.salary-structures.index', $employee));

        $response->assertOk();
        $response->assertSee($employee->display_name);
        $response->assertSee('Salary Structures');
    }

    public function test_create_view_uses_currency_helper_defaults(): void
    {
        config()->set('payroll.enabled', true);
        config()->set('payroll.currencies', ['EGP' => 'EGP', 'USD' => 'USD']);

        $user = User::factory()->create();
        $user->assignRole('HR_Admin_Manager');
        $employee = Employee::factory()->create();

        $response = $this->actingAs($user)->get(route('employees.salary-structures.create', $employee));

        $response->assertOk();
        foreach (array_keys(payrollCurrencies()) as $code) {
            $response->assertSee(sprintf('value="%s"', $code), false);
        }
    }
}
