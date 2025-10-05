<?php

namespace Tests\Feature\Payroll;

use App\Models\PayrollRun;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class PayrollIndexViewTest extends TestCase
{
    use DatabaseTransactions;

    protected function setUp(): void
    {
        parent::setUp();

        Role::firstOrCreate(['name' => 'HR_Admin_Manager', 'guard_name' => 'web']);
    }

    public function test_index_page_renders_with_payroll_runs(): void
    {
        config()->set('payroll.enabled', true);

        $user = User::create([
            'name' => 'Payroll Admin',
            'email' => 'payroll-admin-' . uniqid() . '@example.com',
            'password' => bcrypt('password123'),
        ]);
        $user->assignRole('HR_Admin_Manager');

        $run = PayrollRun::create([
            'title' => 'October Payroll',
            'description' => 'Automated test payroll run.',
            'pay_period_start' => now()->startOfMonth()->toDateString(),
            'pay_period_end' => now()->endOfMonth()->toDateString(),
            'pay_date' => now()->endOfMonth()->addDays(5)->toDateString(),
            'status' => 'calculated',
            'currency' => 'SAR',
            'total_employees' => 5,
            'total_gross' => 50000,
            'total_net' => 42000,
            'total_deductions' => 8000,
            'created_by' => $user->id,
        ]);

        $response = $this->actingAs($user)->get(route('payroll.index'));

        $response->assertOk();
        $response->assertSee('Payroll Runs');
        $response->assertSee('October Payroll');
        $response->assertSee(number_format($run->total_gross, 2));
    }

    public function test_index_requires_flag(): void
    {
        config()->set('payroll.enabled', false);

        $user = User::create([
            'name' => 'Payroll Admin',
            'email' => 'payroll-admin-' . uniqid() . '@example.com',
            'password' => bcrypt('password123'),
        ]);
        $user->assignRole('HR_Admin_Manager');

        $this->actingAs($user)
            ->get(route('payroll.index'))
            ->assertNotFound();
    }
}
