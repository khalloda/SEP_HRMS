<?php

namespace Database\Seeders;

use App\Models\PayrollRun;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class PayrollDemoSeeder extends Seeder
{
    public function run(): void
    {
        if (! function_exists('payrollEnabled') || ! payrollEnabled()) {
            $this->command?->warn('PayrollDemoSeeder skipped: payroll module disabled.');
            return;
        }

        $creator = User::query()->first();

        if (! $creator) {
            $creator = User::create([
                'name' => 'Payroll Demo HR',
                'email' => 'payroll-demo-' . Str::uuid() . '@example.com',
                'password' => bcrypt(Str::random(32)),
            ]);

            $this->command?->warn('Created temporary payroll demo user: ' . $creator->email);
        }

        $periodEnd = Carbon::now()->endOfMonth();
        $periodStart = (clone $periodEnd)->startOfMonth();

        $run = PayrollRun::firstOrCreate(
            [
                'pay_period_start' => $periodStart->toDateString(),
                'pay_period_end' => $periodEnd->toDateString(),
            ],
            [
                'title' => 'Demo Payroll - ' . $periodEnd->format('F Y'),
                'description' => 'Sample payroll run seeded for demo purposes.',
                'pay_date' => (clone $periodEnd)->addDays(5),
                'status' => PayrollRun::STATUS_CALCULATED,
                'currency' => 'SAR',
                'total_employees' => 0,
                'total_gross' => 0,
                'total_net' => 0,
                'total_deductions' => 0,
                'created_by' => $creator->id,
            ]
        );

        if ($run->wasRecentlyCreated) {
            Log::info('PayrollDemoSeeder created demo payroll run.', ['payroll_run_id' => $run->id]);
            $this->command?->info('Demo payroll run created: ' . $run->title);
        } else {
            $this->command?->line('Demo payroll run already exists for ' . $periodEnd->format('F Y') . '.');
        }
    }
}
