<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Contract;

class ExpireOverdueContracts extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'contracts:expire-overdue 
                            {--dry-run : Show what would be expired without actually updating}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Automatically expire contracts that have passed their end date';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Checking for overdue contracts...');

        // Get contracts that should be expired
        $overdueContracts = Contract::active()
            ->whereNotNull('end_date')
            ->where('end_date', '<', now()->toDateString())
            ->with('employee')
            ->get();

        if ($overdueContracts->isEmpty()) {
            $this->info('No overdue contracts found.');
            return Command::SUCCESS;
        }

        $this->warn("Found {$overdueContracts->count()} overdue contracts:");

        // Display the contracts that will be expired
        $this->table(
            ['Employee', 'Contract Type', 'End Date', 'Days Overdue'],
            $overdueContracts->map(function ($contract) {
                return [
                    $contract->employee->display_name,
                    $contract->type_name,
                    $contract->end_date->format('Y-m-d'),
                    $contract->end_date->diffInDays(now()) . ' days'
                ];
            })
        );

        if ($this->option('dry-run')) {
            $this->info('Dry run mode - no contracts were actually expired.');
            return Command::SUCCESS;
        }

        if (!$this->confirm('Do you want to expire these contracts?', true)) {
            $this->info('Operation cancelled.');
            return Command::SUCCESS;
        }

        // Expire the contracts
        $expiredCount = Contract::expireOverdueContracts();

        if ($expiredCount > 0) {
            $this->info("Successfully expired {$expiredCount} contracts.");
        } else {
            $this->error('No contracts were expired. There might be an issue.');
        }

        return Command::SUCCESS;
    }
}
