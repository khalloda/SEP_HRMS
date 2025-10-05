<?php

namespace Tests\Feature\Payroll;

use App\Models\PayrollRun;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class PayrollPolicyTest extends TestCase
{
    use DatabaseTransactions;

    protected function setUp(): void
    {
        parent::setUp();

        Role::firstOrCreate(['name' => 'HR_Admin_Manager', 'guard_name' => 'web']);
        Role::firstOrCreate(['name' => 'Accounting_Manager', 'guard_name' => 'web']);
        Role::firstOrCreate(['name' => 'HR_Coordinator', 'guard_name' => 'web']);
    }

    private function makeDraftRun(User $creator): PayrollRun
    {
        return PayrollRun::create([
            'title' => 'Policy Test Payroll',
            'description' => 'Policy test baseline',
            'pay_period_start' => Carbon::parse('2099-08-01'),
            'pay_period_end' => Carbon::parse('2099-08-31'),
            'pay_date' => Carbon::parse('2099-09-05'),
            'status' => PayrollRun::STATUS_DRAFT,
            'currency' => 'EGP',
            'total_employees' => 0,
            'total_gross' => 0,
            'total_net' => 0,
            'total_deductions' => 0,
            'created_by' => $creator->id,
        ]);
    }

    public function test_hr_admin_can_update_draft_payroll(): void
    {
        config()->set('payroll.enabled', true);

        $user = User::factory()->create();
        $user->assignRole('HR_Admin_Manager');

        $payrollRun = $this->makeDraftRun($user);

        $this->assertTrue($user->can('update', $payrollRun));
    }

    public function test_accounting_manager_can_update_draft_payroll(): void
    {
        config()->set('payroll.enabled', true);

        $creator = User::factory()->create();
        $creator->assignRole('HR_Admin_Manager');

        $accountingManager = User::factory()->create();
        $accountingManager->assignRole('Accounting_Manager');

        $payrollRun = $this->makeDraftRun($creator);

        $this->assertTrue($accountingManager->can('update', $payrollRun));
    }

    public function test_hr_coordinator_cannot_update_draft_payroll(): void
    {
        config()->set('payroll.enabled', true);

        $creator = User::factory()->create();
        $creator->assignRole('HR_Admin_Manager');

        $coordinator = User::factory()->create();
        $coordinator->assignRole('HR_Coordinator');

        $payrollRun = $this->makeDraftRun($creator);

        $this->assertFalse($coordinator->can('update', $payrollRun));
    }

    public function test_authorized_user_cannot_update_locked_payroll(): void
    {
        config()->set('payroll.enabled', true);

        $user = User::factory()->create();
        $user->assignRole('HR_Admin_Manager');

        $payrollRun = $this->makeDraftRun($user);
        $payrollRun->status = PayrollRun::STATUS_LOCKED;
        $payrollRun->save();

        $this->assertFalse($user->can('update', $payrollRun));
    }
}
