<?php

namespace Tests\Feature;

use App\Models\SalaryComponent;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class SalaryComponentFormTest extends TestCase
{
    use DatabaseTransactions;

    protected function setUp(): void
    {
        parent::setUp();

        SalaryComponent::truncate();
    }

    public function test_index_view_lists_components(): void
    {
        $admin = $this->makeAdmin();

        SalaryComponent::create([
            'code' => 'TRANSPORT_ALLOWANCE',
            'name_en' => 'Transport Allowance',
            'name_ar' => 'بدل مواصلات',
            'comp_type' => 'earning',
            'calc_mode' => 'fixed',
            'taxable' => true,
            'priority_order' => 3,
        ]);

        $response = $this->actingAs($admin)->get(route('salary-components.index'));

        $response->assertOk();
        $response->assertSee('Transport Allowance');
        $response->assertSee('Add Component');
    }

    public function test_create_view_renders_form(): void
    {
        $admin = $this->makeAdmin();

        $response = $this->actingAs($admin)->get(route('salary-components.create'));

        $response->assertOk();
        $response->assertSee('Add Salary Component');
        $response->assertSee('Component Code');
    }

    public function test_show_view_displays_component_details(): void
    {
        $admin = $this->makeAdmin();
        $component = SalaryComponent::create([
            'code' => 'OVERTIME',
            'name_en' => 'Overtime',
            'name_ar' => 'ساعات إضافية',
            'comp_type' => 'earning',
            'calc_mode' => 'variable_net_based',
            'taxable' => true,
            'priority_order' => 7,
        ]);

        $response = $this->actingAs($admin)->get(route('salary-components.show', $component));

        $response->assertOk();
        $response->assertSee('Overtime');
        $response->assertSee('Variable (net-based)');
    }

    private function makeAdmin(): User
    {
        $user = User::factory()->create(['email' => 'admin-' . uniqid() . '@example.com']);
        $user->assignRole('HR_Admin_Manager');

        return $user;
    }
}
