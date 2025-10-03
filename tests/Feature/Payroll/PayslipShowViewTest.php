<?php

namespace Tests\Feature\Payroll;

use App\Models\Department;
use App\Models\Employee;
use App\Models\EmploymentType;
use App\Models\PayrollRun;
use App\Models\Payslip;
use App\Models\PayslipLine;
use App\Models\Position;
use App\Models\SalaryComponent;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class PayslipShowViewTest extends TestCase
{
    use DatabaseTransactions;

    protected function setUp(): void
    {
        parent::setUp();

        Role::firstOrCreate(['name' => 'HR_Admin_Manager', 'guard_name' => 'web']);
        Role::firstOrCreate(['name' => 'HR_Coordinator', 'guard_name' => 'web']);
    }

    public function test_admin_views_payslip_details_with_lines(): void
    {
        config()->set('payroll.enabled', true);

        $user = $this->createUserWithRole('HR_Admin_Manager', 'admin');

        $context = $this->createPayslipWithLines($user);

        $response = $this->actingAs($user)->get(route('payslips.show', $context['payslip']));

        $response->assertOk();
        $response->assertSee('Basic Salary');
        $response->assertSee(number_format(4500, 2));
        $response->assertSee('Earnings');
        $response->assertSee('Deductions');
    }

    public function test_coordinator_sees_masked_totals(): void
    {
        config()->set('payroll.enabled', true);

        $user = $this->createUserWithRole('HR_Coordinator', 'coord');

        $context = $this->createPayslipWithLines();

        $response = $this->actingAs($user)->get(route('payslips.show', $context['payslip']));

        $response->assertOk();
        $response->assertSee('Restricted');
        $response->assertDontSee(number_format(4500, 2));
    }

    public function test_view_uses_rtl_direction_for_arabic_locale(): void
    {
        config()->set('payroll.enabled', true);
        app()->setLocale('ar');

        $user = $this->createUserWithRole('HR_Admin_Manager', 'rtl');

        $context = $this->createPayslipWithLines($user);

        $response = $this->actingAs($user)->get(route('payslips.show', $context['payslip']));

        $response->assertOk();
        $response->assertSee('dir="rtl"', false);
    }

    private function createPayslipWithLines(?User $creator = null): array
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

        $payslip = Payslip::create([
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

        $earningComponent = SalaryComponent::create([
            'code' => 'BASIC',
            'name_en' => 'Basic Salary',
            'name_ar' => 'Basic Salary AR',
            'comp_type' => 'earning',
            'calc_mode' => 'fixed',
            'taxable' => true,
            'visible_to_roles' => ['HR_Admin_Manager'],
            'priority_order' => 1,
        ]);

        $deductionComponent = SalaryComponent::create([
            'code' => 'TAX',
            'name_en' => 'Tax Deduction',
            'name_ar' => 'Tax Deduction AR',
            'comp_type' => 'deduction',
            'calc_mode' => 'fixed',
            'taxable' => false,
            'visible_to_roles' => ['HR_Admin_Manager'],
            'priority_order' => 2,
        ]);

        $infoComponent = SalaryComponent::create([
            'code' => 'INFO',
            'name_en' => 'Information Note',
            'name_ar' => 'Information Note AR',
            'comp_type' => 'info',
            'calc_mode' => 'formula',
            'taxable' => false,
            'visible_to_roles' => ['HR_Admin_Manager'],
            'priority_order' => 3,
        ]);

        PayslipLine::create([
            'payslip_id' => $payslip->id,
            'salary_component_id' => $earningComponent->id,
            'component_code' => 'BASIC',
            'component_name_en' => 'Basic Salary',
            'component_name_ar' => 'Basic Salary AR',
            'component_type' => 'earning',
            'component_name' => 'Basic Salary',
            'formula_used' => 'Fixed amount',
            'amount' => 3000,
            'include_in_gross' => true,
            'taxable' => true,
            'priority_order' => 1,
        ]);

        PayslipLine::create([
            'payslip_id' => $payslip->id,
            'salary_component_id' => $deductionComponent->id,
            'component_code' => 'TAX',
            'component_name_en' => 'Tax Deduction',
            'component_name_ar' => 'Tax Deduction AR',
            'component_type' => 'deduction',
            'component_name' => 'Tax Deduction',
            'formula_used' => 'Fixed amount',
            'amount' => 500,
            'include_in_gross' => false,
            'taxable' => false,
            'priority_order' => 2,
        ]);

        PayslipLine::create([
            'payslip_id' => $payslip->id,
            'salary_component_id' => $infoComponent->id,
            'component_code' => 'INFO',
            'component_name_en' => 'Information Note',
            'component_name_ar' => 'Information Note AR',
            'component_type' => 'info',
            'component_name' => 'Information Note',
            'calculation_notes' => 'Overtime hours recorded for reference.',
            'amount' => 0,
            'include_in_gross' => false,
            'taxable' => false,
            'priority_order' => 3,
        ]);

        return [
            'payslip' => $payslip,
            'employee' => $employee,
        ];
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



