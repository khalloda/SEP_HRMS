<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class EnsureEmployeePhotoColumns extends Command
{
    /**
     * The name and signature of the console command.
     */
    protected $signature = 'hrms:ensure-employee-photo-columns {--dry-run : Show actions without applying changes}';

    /**
     * The console command description.
     */
    protected $description = 'Idempotently add employee photo columns if missing (safe; no drops)';

    public function handle(): int
    {
        if (! app()->environment(['local','testing','production'])) {
            $this->warn('Environment not allowed.');
            return self::FAILURE;
        }

        if (! Schema::hasTable('employees')) {
            $this->error('employees table not found. Aborting.');
            return self::FAILURE;
        }

        $columns = [
            'photo_path' => 'ALTER TABLE employees ADD COLUMN photo_path VARCHAR(255) NULL AFTER salary_visibility_flag',
            'photo_original_name' => 'ALTER TABLE employees ADD COLUMN photo_original_name VARCHAR(255) NULL AFTER photo_path',
            'photo_size' => 'ALTER TABLE employees ADD COLUMN photo_size INT NULL AFTER photo_original_name',
            'photo_mime_type' => 'ALTER TABLE employees ADD COLUMN photo_mime_type VARCHAR(100) NULL AFTER photo_size',
            'photo_uploaded_at' => 'ALTER TABLE employees ADD COLUMN photo_uploaded_at TIMESTAMP NULL AFTER photo_mime_type',
        ];

        $dryRun = (bool) $this->option('dry-run');
        $added = [];

        foreach ($columns as $name => $sql) {
            if (! Schema::hasColumn('employees', $name)) {
                if ($dryRun) {
                    $this->line("DRY-RUN: would execute -> $sql");
                } else {
                    DB::statement($sql);
                }
                $added[] = $name;
            }
        }

        if (empty($added)) {
            $this->info('No changes needed. All employee photo columns already exist.');
        } else {
            $this->info('Added columns: ' . implode(', ', $added));
        }

        return self::SUCCESS;
    }
}
