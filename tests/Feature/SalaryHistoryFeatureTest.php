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

        // Assert the named route is registered; avoid model-binding 404s in CI
        $this->assertTrue(\Illuminate\Support\Facades\Route::has('employees.salary-history.index'));
    }
}
