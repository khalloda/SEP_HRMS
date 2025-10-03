<?php

namespace Tests\Feature\Payroll;

use App\Models\PayrollRun;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class PayrollStoreTest extends TestCase
{
    use DatabaseTransactions;

    protected function setUp(): void
    {
        parent::setUp();

        Role::firstOrCreate(['name' => 'HR_Admin_Manager', 'guard_name' => 'web']);
    }

    public function test_store_creates_payroll_run_when_flag_enabled(): void
    {
        config()->set('payroll.enabled', true);

        $user = User::create([
            'name' => 'Payroll Creator',
            'email' => 'payroll-store-' . uniqid() . '@example.com',
            'password' => bcrypt('password123'),
        ]);
        $user->assignRole('HR_Admin_Manager');

        $baselineRuns = PayrollRun::count();

        $payload = [
            'title' => 'January 2099 Payroll',
            'description' => 'Automated test payroll creation.',
            'pay_period_start' => Carbon::parse('2099-01-01')->toDateString(),
            'pay_period_end' => Carbon::parse('2099-01-31')->toDateString(),
            'pay_date' => Carbon::parse('2099-02-05')->toDateString(),
            'currency' => 'EGP',
            'approval_required' => 1,
            'notes' => 'Generated via feature test.',
        ];

        $response = $this->actingAs($user)->post(route('payroll.store'), $payload);

        $response->assertSessionHasNoErrors();
        $createdRun = PayrollRun::latest('id')->first();
        $this->assertNotNull($createdRun, 'Payroll run should be created');
        $this->assertEquals($baselineRuns + 1, PayrollRun::count());

        $response->assertRedirectToRoute('payroll.show', $createdRun);
        $this->assertSame('draft', $createdRun->status);
        $this->assertSame($payload['currency'], $createdRun->currency);
        $this->assertSame($user->id, $createdRun->created_by);
        $this->assertTrue($createdRun->approval_required);
    }

    public function test_store_rejects_overlapping_payroll_runs(): void
    {
        config()->set('payroll.enabled', true);

        $user = User::create([
            'name' => 'Payroll Creator',
            'email' => 'payroll-overlap-' . uniqid() . '@example.com',
            'password' => bcrypt('password123'),
        ]);
        $user->assignRole('HR_Admin_Manager');

        $baselineRuns = PayrollRun::count();

        PayrollRun::create([
            'title' => 'February 2026 Payroll',
            'description' => 'Existing payroll run',
            'pay_period_start' => Carbon::parse('2026-02-01'),
            'pay_period_end' => Carbon::parse('2026-02-29'),
            'pay_date' => Carbon::parse('2026-03-05'),
            'status' => PayrollRun::STATUS_DRAFT,
            'currency' => 'EGP',
            'total_employees' => 0,
            'total_gross' => 0,
            'total_net' => 0,
            'total_deductions' => 0,
            'created_by' => $user->id,
        ]);

        $payload = [
            'title' => 'Overlap Payroll',
            'description' => 'Attempt overlapping period',
            'pay_period_start' => Carbon::parse('2026-02-15')->toDateString(),
            'pay_period_end' => Carbon::parse('2026-03-15')->toDateString(),
            'pay_date' => Carbon::parse('2026-03-20')->toDateString(),
            'currency' => 'EGP',
            'approval_required' => 0,
            'notes' => 'Should be rejected.',
        ];

        $response = $this->actingAs($user)->from(route('payroll.create'))->post(route('payroll.store'), $payload);

        $response->assertRedirect(route('payroll.create'));
        $response->assertSessionHasErrors('pay_period_start');
        $this->assertEquals($baselineRuns + 1, PayrollRun::count());
    }
}
