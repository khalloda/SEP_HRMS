<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     */
    protected function schedule(Schedule $schedule): void
    {
        // Send contract expiry notifications daily at 9:00 AM
        $schedule->command('hrms:send-contract-notifications')->dailyAt('09:00');
        
        // Send urgent notifications twice daily (9 AM and 3 PM)
        $schedule->command('hrms:send-contract-notifications --type=urgent')->twiceDaily(9, 15);
        
        // You can add more scheduled tasks here
        // $schedule->command('inspire')->hourly();
    }

    /**
     * Register the commands for the application.
     */
    protected function commands(): void
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
