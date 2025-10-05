<?php

namespace Tests\Feature\Payroll;

use App\Models\PayrollRun;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class PayrollUpdateTest extends TestCase
{
    use DatabaseTransactions;

    protected function setUp(): void
    {
        parent::setUp();

        Role::firstOrCreate(['name' => 'HR_Admin_Manager', 'guard_name' => 'web']);
    }

    public function test_update_accepts_configured_currency(): void
    {
        config()->set('payroll.enabled', true);

        $user = User::factory()->create();
        $user->assignRole('HR_Admin_Manager');

        $payrollRun = PayrollRun::create([
            'title' => 'May 2099 Payroll',
            'description' => 'Editable payroll run',
            'pay_period_start' => Carbon::parse('2099-05-01'),
            'pay_period_end' => Carbon::parse('2099-05-31'),
            'pay_date' => Carbon::parse('2099-06-05'),
            'status' => PayrollRun::STATUS_DRAFT,
            'currency' => 'EGP',
            'total_employees' => 0,
            'total_gross' => 0,
            'total_net' => 0,
            'total_deductions' => 0,
            'created_by' => $user->id,
        ]);

        $payload = [
            'title' => 'May 2099 Payroll - Updated',
            'description' => 'Updated description',
            'pay_period_start' => '2099-05-01',
            'pay_period_end' => '2099-05-31',
            'pay_date' => '2099-06-05',
            'currency' => 'USD',
            'approval_required' => 1,
            'notes' => 'Updated via feature test.',
        ];

        $response = $this->actingAs($user)->put(route('payroll.update', $payrollRun), $payload);

        $response->assertSessionHasNoErrors();
        $response->assertRedirect(route('payroll.show', $payrollRun));
        $this->assertSame('USD', $payrollRun->fresh()->currency);
    }

    public function test_update_rejects_invalid_currency(): void
    {
        config()->set('payroll.enabled', true);

        $user = User::factory()->create();
        $user->assignRole('HR_Admin_Manager');

        $payrollRun = PayrollRun::create([
            'title' => 'June 2099 Payroll',
            'description' => 'Invalid currency scenario',
            'pay_period_start' => Carbon::parse('2099-06-01'),
            'pay_period_end' => Carbon::parse('2099-06-30'),
            'pay_date' => Carbon::parse('2099-07-05'),
            'status' => PayrollRun::STATUS_DRAFT,
            'currency' => 'EGP',
            'total_employees' => 0,
            'total_gross' => 0,
            'total_net' => 0,
            'total_deductions' => 0,
            'created_by' => $user->id,
        ]);

        $payload = [
            'title' => 'June 2099 Payroll',
            'description' => 'Invalid currency attempt',
            'pay_period_start' => '2099-06-01',
            'pay_period_end' => '2099-06-30',
            'pay_date' => '2099-07-05',
            'currency' => 'JPY',
            'approval_required' => 0,
            'notes' => 'Should fail validation.',
        ];

        $response = $this->actingAs($user)->from(route('payroll.edit', $payrollRun))->put(route('payroll.update', $payrollRun), $payload);

        $response->assertRedirect(route('payroll.edit', $payrollRun));
        $response->assertSessionHasErrors('currency');
        $this->assertSame('EGP', $payrollRun->fresh()->currency);
    }
}
