<?php

namespace Tests\Feature;

use App\Models\SalaryComponent;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class SalaryComponentCrudTest extends TestCase
{
    use DatabaseTransactions;

    protected function setUp(): void
    {
        parent::setUp();

        SalaryComponent::truncate();
    }

    public function test_admin_can_create_component(): void
    {
        $admin = $this->makeAdmin();

        $response = $this->actingAs($admin)->post(route('salary-components.store'), [
            'code' => 'HOUSING_ALLOWANCE',
            'name_en' => 'Housing Allowance',
            'name_ar' => 'بدل السكن',
            'comp_type' => 'earning',
            'calc_mode' => 'fixed',
            'taxable' => 1,
            'priority_order' => 5,
        ]);

        $response->assertRedirect(route('salary-components.index'));
        $this->assertDatabaseHas('salary_components', ['code' => 'HOUSING_ALLOWANCE']);
    }

    public function test_admin_can_update_component(): void
    {
        $admin = $this->makeAdmin();
        $component = SalaryComponent::create([
            'code' => 'MEAL_ALLOWANCE',
            'name_en' => 'Meal Allowance',
            'name_ar' => 'بدل وجبة',
            'comp_type' => 'earning',
            'calc_mode' => 'fixed',
            'taxable' => false,
            'priority_order' => 10,
        ]);

        $response = $this->actingAs($admin)->put(route('salary-components.update', $component), [
            'code' => 'MEAL_ALLOWANCE',
            'name_en' => 'Meal Allowance Updated',
            'name_ar' => 'بدل وجبة محدّث',
            'comp_type' => 'earning',
            'calc_mode' => 'fixed',
            'taxable' => true,
            'priority_order' => 11,
        ]);

        $response->assertRedirect(route('salary-components.show', $component));
        $this->assertSame('Meal Allowance Updated', $component->fresh()->name_en);
    }

    public function test_admin_can_delete_component_without_usage(): void
    {
        $admin = $this->makeAdmin();
        $component = SalaryComponent::create([
            'code' => 'BONUS_TEMP',
            'name_en' => 'Temporary Bonus',
            'name_ar' => 'مكافأة مؤقتة',
            'comp_type' => 'earning',
            'calc_mode' => 'fixed',
            'taxable' => true,
            'priority_order' => 20,
        ]);

        $response = $this->actingAs($admin)->delete(route('salary-components.destroy', $component));

        $response->assertRedirect(route('salary-components.index'));
        $this->assertDatabaseMissing('salary_components', ['id' => $component->id]);
    }

    private function makeAdmin(): User
    {
        $user = User::factory()->create(['email' => 'admin-' . uniqid() . '@example.com']);
        $user->assignRole('HR_Admin_Manager');

        return $user;
    }
}
