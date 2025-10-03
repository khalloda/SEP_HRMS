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
        Employee::query()->where('status', 'active')->update(['status' => 'inactive']);

        config()->set('payroll.enabled', true);
        config()->set('payroll.queue_enabled', false);
        config()->set('payroll.use_safe_engine_conditionals', false);

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

        $basicComponent = SalaryComponent::updateOrCreate(
            ['code' => 'BASIC_SALARY'],
            [
                'name_en' => 'Basic Salary',
                'name_ar' => 'الراتب الأساسي',
                'comp_type' => 'earning',
                'calc_mode' => 'fixed',
                'taxable' => true,
                'priority_order' => 1,
            ]
        );

        $taxComponent = SalaryComponent::updateOrCreate(
            ['code' => 'INCOME_TAX'],
            [
                'name_en' => 'Income Tax',
                'name_ar' => 'ضريبة الدخل',
                'comp_type' => 'deduction',
                'calc_mode' => 'formula',
                'taxable' => false,
                'priority_order' => 2,
            ]
        );

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
            'formula_expr' => 'BASIC_SALARY * 0.1',
            'depends_on' => ['BASIC_SALARY'],
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

        $result = $service->calculatePayrollRun($payrollRun);

        $this->assertTrue($result['success']);
        $this->assertSame(1, $result['employees_processed']);
        $this->assertEmpty($result['errors']);

        $payrollRun->refresh();
        $this->assertSame(PayrollRun::STATUS_CALCULATED, $payrollRun->status);
        $this->assertEquals(10000.0, (float) $payrollRun->total_gross);
        $this->assertEquals(1000.0, (float) $payrollRun->total_deductions);
        $this->assertEquals(9000.0, (float) $payrollRun->total_net);

        /** @var Payslip $payslip */
        $payslip = $payrollRun->payslips()->with('payslipLines')->first();
        $this->assertNotNull($payslip);
        $this->assertEquals(10000.0, (float) $payslip->gross_pay);
        $this->assertEquals(1000.0, (float) $payslip->total_deductions);
        $this->assertEquals(9000.0, (float) $payslip->net_pay);
        $this->assertEquals(10000.0, (float) $payslip->basic_salary);

        $lines = $payslip->payslipLines->pluck('amount', 'component_code');

        $this->assertEquals(10000.0, (float) $lines['BASIC_SALARY']);
        $this->assertEquals(1000.0, (float) $lines['INCOME_TAX']);
    }

    public function test_payroll_run_evaluates_conditional_formula_when_flag_enabled(): void
    {
        Employee::query()->where('status', 'active')->update(['status' => 'inactive']);

        config()->set('payroll.enabled', true);
        config()->set('payroll.queue_enabled', false);
        config()->set('payroll.use_safe_engine_conditionals', true);

        $creator = User::create([
            'name' => 'Payroll Creator Conditional',
            'email' => 'payroll-creator-' . uniqid() . '@example.com',
            'password' => bcrypt('password123'),
        ]);

        $employee = Employee::create([
            'code' => 'EMP-COND-' . uniqid(),
            'first_name' => 'Conditional',
            'last_name' => 'Employee',
            'email' => 'employee-conditional-' . uniqid() . '@example.com',
            'hire_date' => Carbon::now()->subYear()->toDateString(),
            'status' => 'active',
            'salary_visibility_flag' => true,
        ]);

        $basicComponent = SalaryComponent::updateOrCreate(
            ['code' => 'BASIC_SALARY'],
            [
                'name_en' => 'Basic Salary',
                'name_ar' => 'الراتب الأساسي',
                'comp_type' => 'earning',
                'calc_mode' => 'fixed',
                'taxable' => true,
                'priority_order' => 1,
            ]
        );

        $taxComponent = SalaryComponent::updateOrCreate(
            ['code' => 'INCOME_TAX'],
            [
                'name_en' => 'Income Tax',
                'name_ar' => 'ضريبة الدخل',
                'comp_type' => 'deduction',
                'calc_mode' => 'formula',
                'taxable' => false,
                'priority_order' => 2,
            ]
        );

        $structure = SalaryStructure::create([
            'employee_id' => $employee->id,
            'currency' => 'EGP',
            'effective_from' => Carbon::now()->subMonth()->toDateString(),
            'notes' => 'Conditional payroll regression structure',
        ]);

        SalaryStructureComponent::create([
            'structure_id' => $structure->id,
            'component_id' => $basicComponent->id,
            'value_numeric' => 15000,
            'priority_order' => 1,
        ]);

        SalaryStructureComponent::create([
            'structure_id' => $structure->id,
            'component_id' => $taxComponent->id,
            'formula_expr' => 'IF(BASIC_SALARY > 12000, BASIC_SALARY * 0.12, BASIC_SALARY * 0.05)',
            'depends_on' => ['BASIC_SALARY'],
            'priority_order' => 2,
        ]);

        $payrollRun = PayrollRun::create([
            'title' => 'Conditional Payroll',
            'status' => PayrollRun::STATUS_DRAFT,
            'pay_period_start' => Carbon::parse('2025-12-01'),
            'pay_period_end' => Carbon::parse('2025-12-31'),
            'pay_date' => Carbon::parse('2025-12-31'),
            'currency' => 'EGP',
            'created_by' => $creator->id,
        ]);

        /** @var PayrollCalculationService $service */
        $service = app(PayrollCalculationService::class);

        $result = $service->calculatePayrollRun($payrollRun);

        $this->assertTrue($result['success']);
        $this->assertSame(1, $result['employees_processed']);
        $this->assertEmpty($result['errors']);

        $payrollRun->refresh();
        $this->assertSame(PayrollRun::STATUS_CALCULATED, $payrollRun->status);
        $this->assertEquals(15000.0, (float) $payrollRun->total_gross);
        $this->assertEquals(1800.0, (float) $payrollRun->total_deductions);
        $this->assertEquals(13200.0, (float) $payrollRun->total_net);

        /** @var Payslip $payslip */
        $payslip = $payrollRun->payslips()->with('payslipLines')->first();
        $this->assertNotNull($payslip);
        $this->assertEquals(15000.0, (float) $payslip->gross_pay);
        $this->assertEquals(1800.0, (float) $payslip->total_deductions);
        $this->assertEquals(13200.0, (float) $payslip->net_pay);
        $this->assertEquals(15000.0, (float) $payslip->basic_salary);

        $lines = $payslip->payslipLines->pluck('amount', 'component_code');

        $this->assertEquals(15000.0, (float) $lines['BASIC_SALARY']);
        $this->assertEquals(1800.0, (float) $lines['INCOME_TAX']);
    }
}
