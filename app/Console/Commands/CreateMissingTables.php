<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CreateMissingTables extends Command
{
    protected $signature = 'hrms:create-missing-tables';
    protected $description = 'Create missing database tables for Spatie packages';

    public function handle()
    {
        $this->info('Creating missing database tables...');

        try {
            // Create model_has_permissions table
            if (!Schema::hasTable('model_has_permissions')) {
                DB::unprepared("
                    CREATE TABLE `model_has_permissions` (
                      `permission_id` bigint unsigned NOT NULL,
                      `model_type` varchar(255) NOT NULL,
                      `model_id` bigint unsigned NOT NULL,
                      PRIMARY KEY (`permission_id`, `model_id`, `model_type`),
                      KEY `model_has_permissions_model_id_model_type_index` (`model_id`, `model_type`),
                      CONSTRAINT `model_has_permissions_permission_id_foreign` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE
                    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4
                ");
                $this->info('✓ Created model_has_permissions table');
            } else {
                $this->info('✓ model_has_permissions table already exists');
            }

            // Create model_has_roles table
            if (!Schema::hasTable('model_has_roles')) {
                DB::unprepared("
                    CREATE TABLE `model_has_roles` (
                      `role_id` bigint unsigned NOT NULL,
                      `model_type` varchar(255) NOT NULL,
                      `model_id` bigint unsigned NOT NULL,
                      PRIMARY KEY (`role_id`, `model_id`, `model_type`),
                      KEY `model_has_roles_model_id_model_type_index` (`model_id`, `model_type`),
                      CONSTRAINT `model_has_roles_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE
                    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4
                ");
                $this->info('✓ Created model_has_roles table');
            } else {
                $this->info('✓ model_has_roles table already exists');
            }

            // Create role_has_permissions table
            if (!Schema::hasTable('role_has_permissions')) {
                DB::unprepared("
                    CREATE TABLE `role_has_permissions` (
                      `permission_id` bigint unsigned NOT NULL,
                      `role_id` bigint unsigned NOT NULL,
                      PRIMARY KEY (`permission_id`, `role_id`),
                      CONSTRAINT `role_has_permissions_permission_id_foreign` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE,
                      CONSTRAINT `role_has_permissions_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE
                    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4
                ");
                $this->info('✓ Created role_has_permissions table');
            } else {
                $this->info('✓ role_has_permissions table already exists');
            }

            // Create activity_log table
            if (!Schema::hasTable('activity_log')) {
                DB::unprepared("
                    CREATE TABLE `activity_log` (
                      `id` bigint unsigned NOT NULL AUTO_INCREMENT,
                      `log_name` varchar(255) DEFAULT NULL,
                      `description` text NOT NULL,
                      `subject_type` varchar(255) DEFAULT NULL,
                      `subject_id` bigint unsigned DEFAULT NULL,
                      `causer_type` varchar(255) DEFAULT NULL,
                      `causer_id` bigint unsigned DEFAULT NULL,
                      `properties` json DEFAULT NULL,
                      `event` varchar(255) DEFAULT NULL,
                      `batch_uuid` char(36) DEFAULT NULL,
                      `created_at` timestamp NULL DEFAULT NULL,
                      `updated_at` timestamp NULL DEFAULT NULL,
                      PRIMARY KEY (`id`),
                      KEY `subject` (`subject_type`,`subject_id`),
                      KEY `causer` (`causer_type`,`causer_id`),
                      KEY `activity_log_log_name_index` (`log_name`)
                    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4
                ");
                $this->info('✓ Created activity_log table');
            } else {
                $this->info('✓ activity_log table already exists');
            }

            // Mark migrations as completed
            $migrations = [
                '2014_10_12_000000_create_users_table',
                '2014_10_12_100000_create_password_reset_tokens_table',
                '2019_08_19_000000_create_failed_jobs_table',
                '2019_12_14_000001_create_personal_access_tokens_table',
                '2025_09_12_145713_create_permission_tables',
                '2025_09_12_195810_create_activity_log_table',
                '2025_09_12_195811_add_event_column_to_activity_log_table',
                '2025_09_12_195812_add_batch_uuid_column_to_activity_log_table',
            ];

            foreach ($migrations as $migration) {
                DB::table('migrations')->updateOrInsert(
                    ['migration' => $migration],
                    ['migration' => $migration, 'batch' => 1]
                );
            }

            $this->info('✓ Updated migration records');
            $this->info('All missing tables created successfully!');

        } catch (\Exception $e) {
            $this->error('Error creating tables: ' . $e->getMessage());
            return 1;
        }

        return 0;
    }
}