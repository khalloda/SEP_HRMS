<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class SalaryHistoryFeatureTest extends TestCase
{
    use WithFaker;

    /** @test */
    public function salary_history_route_is_registered_when_flag_enabled()
    {
        if (!config('payroll.use_salary_structure_history')) {
            $this->markTestSkipped('Salary history flag disabled.');
        }

        $user = User::first();
        if (!$user) {
            $this->markTestIncomplete('No user available to authenticate.');
        }

        $this->actingAs($user);

        $url = url('/employees/1/salary-history');
        $response = $this->get($url);

        // Route should exist (not 404). Authorization may vary by environment.
        $this->assertNotEquals(404, $response->getStatusCode());
    }
}


