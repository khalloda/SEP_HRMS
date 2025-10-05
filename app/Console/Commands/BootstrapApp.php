<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;

class BootstrapApp extends Command
{
    protected $signature = 'app:bootstrap {--rbac : Seed roles & permissions} {--fresh : Fresh migrate (DANGEROUS)}';
    protected $description = 'Run database migrations and optionally seed roles/permissions';

    public function handle(): int
    {
        $this->info('Running database migrations...');
        if ($this->option('fresh')) {
            Artisan::call('migrate:fresh', ['--force' => true]);
        } else {
            Artisan::call('migrate', ['--force' => true]);
        }
        $this->line(Artisan::output());

        if ($this->option('rbac')) {
            $this->info('Seeding roles & permissions...');
            Artisan::call('db:seed', [
                '--class' => \Database\Seeders\RolesAndPermissionsSeeder::class,
                '--force' => true,
            ]);
            $this->line(Artisan::output());
        }

        $this->info('Bootstrap complete.');
        return self::SUCCESS;
    }
}

