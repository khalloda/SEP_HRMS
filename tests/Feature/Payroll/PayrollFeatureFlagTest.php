<?php

namespace Tests\Feature\Payroll;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;
use Tests\TestCase;

class PayrollFeatureFlagTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        app(PermissionRegistrar::class)->forgetCachedPermissions();
        Role::create(['name' => 'HR_Admin_Manager', 'guard_name' => 'web']);
    }

    public function test_payroll_routes_return_not_found_when_flag_disabled(): void
    {
        config()->set('payroll.enabled', false);

        $response = $this->actingAs($this->makePayrollUser())
            ->get(route('payroll.statistics'));

        $response->assertNotFound();
    }

    public function test_payroll_routes_accessible_when_flag_enabled(): void
    {
        config()->set('payroll.enabled', true);

        $response = $this->actingAs($this->makePayrollUser())
            ->get(route('payroll.statistics'));

        $response->assertOk()
            ->assertJsonStructure([
                'total_runs',
                'draft_runs',
                'pending_approval',
                'posted_this_year',
                'total_payslips_this_year',
                'monthly_totals',
            ]);
    }

    protected function makePayrollUser(): User
    {
        $user = User::factory()->create();
        $user->assignRole('HR_Admin_Manager');

        return $user;
    }
}
