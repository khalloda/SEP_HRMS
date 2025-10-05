<?php

namespace Tests\Feature\Payroll;

use App\Models\Department;
use App\Models\Employee;
use App\Models\EmploymentType;
use App\Models\PayrollRun;
use App\Models\Payslip;
use App\Models\Position;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class PayslipIndexViewTest extends TestCase
{
    use DatabaseTransactions;

    protected function setUp(): void
    {
        parent::setUp();

        Role::firstOrCreate(['name' => 'HR_Admin_Manager', 'guard_name' => 'web']);
        Role::firstOrCreate(['name' => 'HR_Coordinator', 'guard_name' => 'web']);
    }

    public function test_admin_sees_payslip_listing_with_totals(): void
    {
        config()->set('payroll.enabled', true);

        $user = $this->createUserWithRole('HR_Admin_Manager', 'admin');

        $this->createPayslip($user);

        $response = $this->actingAs($user)->get(route('payslips.index'));

        $response->assertOk();
        $response->assertSee('Payslips');
        $response->assertSee('Jane Doe');
        $response->assertSee(number_format(4500, 2));
    }

    public function test_coordinator_sees_masked_amounts(): void
    {
        config()->set('payroll.enabled', true);

        $user = $this->createUserWithRole('HR_Coordinator', 'coordinator');

        $this->createPayslip();

        $response = $this->actingAs($user)->get(route('payslips.index'));

        $response->assertOk();
        $response->assertSee('Restricted');
        $response->assertDontSee(number_format(4500, 2));
    }

    private function createPayslip(?User $creator = null): Payslip
    {
        $creator ??= $this->createUserWithRole('HR_Admin_Manager', 'creator');

        $department = Department::create([
            'code' => 'FIN',
            'name_en' => 'Finance',
            'name_ar' => 'Finance AR',
        ]);

        $position = Position::create([
            'name_en' => 'Accountant',
            'name_ar' => 'Accountant AR',
            'category' => 1,
            'grade_order' => 1,
        ]);

        $employmentType = EmploymentType::create([
            'name_en' => 'Permanent ' . uniqid(),
            'name_ar' => 'Permanent AR',
        ]);

        $employee = Employee::create([
            'code' => 'EMP-' . uniqid(),
            'first_name' => 'Jane',
            'last_name' => 'Doe',
            'arabic_name' => 'Jane Doe AR',
            'email' => 'employee-' . uniqid() . '@example.com',
            'phone' => '0100000000',
            'hire_date' => now()->subYear()->toDateString(),
            'status' => 'active',
            'department_id' => $department->id,
            'position_id' => $position->id,
            'employment_type_id' => $employmentType->id,
            'manager_id' => null,
            'national_id' => '12345678901234',
            'salary_visibility_flag' => true,
        ]);

        $payrollRun = PayrollRun::create([
            'title' => 'October Payroll',
            'description' => 'Monthly payroll run for testing.',
            'pay_period_start' => now()->startOfMonth()->toDateString(),
            'pay_period_end' => now()->endOfMonth()->toDateString(),
            'pay_date' => now()->endOfMonth()->addDays(5)->toDateString(),
            'status' => 'calculated',
            'currency' => 'SAR',
            'total_employees' => 1,
            'total_gross' => 5000,
            'total_net' => 4500,
            'total_deductions' => 500,
            'created_by' => $creator->id,
        ]);

        return Payslip::create([
            'payroll_run_id' => $payrollRun->id,
            'employee_id' => $employee->id,
            'salary_structure_id' => null,
            'employee_code' => $employee->code,
            'employee_name' => $employee->full_name,
            'employee_arabic_name' => $employee->arabic_name,
            'department_name' => $department->name_en,
            'position_name' => $position->name_en,
            'pay_period_start' => $payrollRun->pay_period_start,
            'pay_period_end' => $payrollRun->pay_period_end,
            'pay_date' => $payrollRun->pay_date,
            'currency' => $payrollRun->currency,
            'gross_pay' => 5000,
            'total_deductions' => 500,
            'net_pay' => 4500,
            'basic_salary' => 3000,
            'status' => 'calculated',
            'generated_at' => now(),
        ]);
    }

    private function createUserWithRole(string $role, string $prefix): User
    {
        $user = User::create([
            'name' => ucfirst($prefix) . ' User',
            'email' => $prefix . '-' . uniqid() . '@example.com',
            'password' => bcrypt('password123'),
        ]);

        $user->assignRole($role);

        return $user;
    }
}


