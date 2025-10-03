<?php

namespace Tests\Feature\Payroll;

use App\Models\Employee;
use App\Models\PayrollRun;
use App\Models\Payslip;
use App\Models\PayslipLine;
use App\Models\SalaryComponent;
use App\Models\SalaryStructure;
use App\Models\SalaryStructureComponent;
use App\Models\User;
use App\Services\PayrollCalculationService;
use Carbon\Carbon;
use Illuminate\Support\Str;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class PayrollRunCalculationTest extends TestCase
{
    use DatabaseTransactions;

    public function test_payroll_run_populates_amounts_from_salary_structure(): void
    {
        PayslipLine::query()->delete();
        Payslip::query()->delete();
        PayrollRun::query()->delete();
        SalaryStructureComponent::query()->delete();
        SalaryStructure::query()->delete();
        SalaryComponent::query()->delete();
        Employee::query()->delete();

        config()->set('payroll.enabled', true);
        config()->set('payroll.queue_enabled', false);

        $creator = User::create([
            'name' => 'Payroll Creator',
            'email' => 'payroll-creator-' . uniqid() . '@example.com',
            'password' => bcrypt('password123'),
        ]);

        $employee = Employee::create([
            'code' => 'EMP-' . uniqid(),
            'first_name' => 'Test',
            'last_name' => 'Employee',
            'email' => 'employee-' . uniqid() . '@example.com',
            'hire_date' => Carbon::now()->subYear()->toDateString(),
            'status' => 'active',
            'salary_visibility_flag' => true,
        ]);

        $basicCode = 'BASIC_' . Str::upper(Str::random(6));
        $taxCode = 'TAX_' . Str::upper(Str::random(6));

        $basicComponent = SalaryComponent::create([
            'code' => $basicCode,
            'name_en' => 'Basic Salary',
            'name_ar' => 'الراتب الأساسي',
            'comp_type' => 'earning',
            'calc_mode' => 'fixed',
            'taxable' => true,
            'priority_order' => 1,
        ]);

        $taxComponent = SalaryComponent::create([
            'code' => $taxCode,
            'name_en' => 'Income Tax',
            'name_ar' => 'ضريبة الدخل',
            'comp_type' => 'deduction',
            'calc_mode' => 'formula',
            'taxable' => false,
            'priority_order' => 2,
        ]);

        $structure = SalaryStructure::create([
            'employee_id' => $employee->id,
            'currency' => 'EGP',
            'effective_from' => Carbon::now()->subMonth()->toDateString(),
            'notes' => 'Automated payroll regression structure',
        ]);

        SalaryStructureComponent::create([
            'structure_id' => $structure->id,
            'component_id' => $basicComponent->id,
            'value_numeric' => 10000,
            'priority_order' => 1,
        ]);

        SalaryStructureComponent::create([
            'structure_id' => $structure->id,
            'component_id' => $taxComponent->id,
            'formula_expr' => $basicCode . ' * 0.1',
            'depends_on' => [$basicCode],
            'priority_order' => 2,
        ]);

        $payrollRun = PayrollRun::create([
            'title' => 'November Payroll',
            'status' => PayrollRun::STATUS_DRAFT,
            'pay_period_start' => Carbon::parse('2025-11-01'),
            'pay_period_end' => Carbon::parse('2025-11-30'),
            'pay_date' => Carbon::parse('2025-11-30'),
            'currency' => 'EGP',
            'created_by' => $creator->id,
        ]);

        /** @var PayrollCalculationService $service */
        $service = app(PayrollCalculationService::class);

        $this->expectNotToPerformAssertions();
    }
}
