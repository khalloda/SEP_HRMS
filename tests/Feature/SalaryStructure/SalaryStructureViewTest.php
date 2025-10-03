<?php

namespace Tests\Feature\SalaryStructure;

use App\Models\Employee;
use App\Models\SalaryStructure;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Str;
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

    private function createUserWithRole(string $role): User
    {
        $user = User::create([
            'name' => ucfirst($role) . ' User',
            'email' => $role . '-' . uniqid() . '@example.com',
            'password' => bcrypt('password123'),
        ]);
        $user->assignRole($role);

        return $user;
    }

    private function createEmployee(): Employee
    {
        return Employee::create([
            'code' => 'EMP-' . strtoupper(Str::random(6)),
            'first_name' => 'Test',
            'last_name' => 'Employee',
            'status' => 'active',
        ]);
    }

    public function test_index_renders_structures_for_employee(): void
    {
        config()->set('payroll.enabled', true);

        $user = $this->createUserWithRole('HR_Admin_Manager');
        $employee = $this->createEmployee();

        SalaryStructure::create([
            'employee_id' => $employee->id,
            'currency' => 'EGP',
            'effective_from' => Carbon::parse('2099-01-01'),
            'effective_to' => null,
            'notes' => 'Baseline structure',
        ]);

        $response = $this->actingAs($user)->get(route('employees.salary-structures.index', $employee));

        $response->assertOk();
        $response->assertSee($employee->full_name ?? $employee->first_name);
        $response->assertSee('Salary Structures');
    }

    public function test_create_view_uses_currency_helper_defaults(): void
    {
        config()->set('payroll.enabled', true);
        config()->set('payroll.currencies', ['EGP' => 'EGP', 'USD' => 'USD']);

        $user = $this->createUserWithRole('HR_Admin_Manager');
        $employee = $this->createEmployee();

        $response = $this->actingAs($user)->get(route('employees.salary-structures.create', $employee));

        $response->assertOk();
        foreach (array_keys(payrollCurrencies()) as $code) {
            $response->assertSee(sprintf('value="%s"', $code), false);
        }
    }
}
