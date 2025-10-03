<?php

namespace Tests\Feature\Payroll;

use App\Models\PayrollRun;
use App\Models\User;
use Database\Seeders\PayrollDemoSeeder;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class PayrollDemoSeederTest extends TestCase
{
    use DatabaseTransactions;

    public function test_seeder_skips_when_payroll_disabled(): void
    {
        config()->set('payroll.enabled', false);

        PayrollRun::query()->delete();

        $this->artisan('db:seed', ['--class' => PayrollDemoSeeder::class])
            ->expectsOutputToContain('PayrollDemoSeeder skipped')
            ->assertExitCode(0);

        $this->assertDatabaseCount('payroll_runs', 0);
    }

    public function test_seeder_creates_demo_run_when_enabled(): void
    {
        config()->set('payroll.enabled', true);

        if (! User::query()->exists()) {
            User::factory()->create();
        }

        PayrollRun::query()->delete();

        $this->artisan('db:seed', ['--class' => PayrollDemoSeeder::class])
            ->assertExitCode(0);

        $this->assertDatabaseCount('payroll_runs', 1);

        $run = PayrollRun::first();
        $this->assertNotNull($run);
        $this->assertSame('SAR', $run->currency);
        $this->assertNotNull($run->created_by);
        $this->assertTrue(User::whereKey($run->created_by)->exists());
    }
}
