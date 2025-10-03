<?php

namespace Tests\Feature\Payroll;

use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class PayrollCreateViewTest extends TestCase
{
    use DatabaseTransactions;

    protected function setUp(): void
    {
        parent::setUp();

        Role::firstOrCreate(['name' => 'HR_Admin_Manager', 'guard_name' => 'web']);
    }

    public function test_create_page_renders_when_flag_enabled(): void
    {
        config()->set('payroll.enabled', true);

        $user = User::create([
            'name' => 'Payroll Creator',
            'email' => 'payroll-create-' . uniqid() . '@example.com',
            'password' => bcrypt('password123'),
        ]);
        $user->assignRole('HR_Admin_Manager');

        $response = $this->actingAs($user)->get(route('payroll.create'));

        $response->assertOk();
        $response->assertSee('Create Payroll Run');
        $response->assertSee('Payroll Details');
        $response->assertSee('value="EGP" selected', false);

        $configuredCurrencies = config('payroll.currencies');
        foreach ($configuredCurrencies as $code => $label) {
            $response->assertSee(sprintf('value="%s"', $code), false);
        }

        $response->assertSee('pay_period_start');
    }

    public function test_create_page_requires_feature_flag(): void
    {
        config()->set('payroll.enabled', false);

        $user = User::create([
            'name' => 'Payroll Creator',
            'email' => 'payroll-create-' . uniqid() . '@example.com',
            'password' => bcrypt('password123'),
        ]);
        $user->assignRole('HR_Admin_Manager');

        $this->actingAs($user)
            ->get(route('payroll.create'))
            ->assertNotFound();
    }
}
