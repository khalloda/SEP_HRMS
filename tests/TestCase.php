<?php

namespace Tests;

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use RuntimeException;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;

    protected static bool $schemaLoaded = false;

    protected function setUp(): void
    {
        parent::setUp();

        $this->bootstrapBaseSchema();
        $this->ensurePermissionSchemaUpToDate();
    }

    protected function bootstrapBaseSchema(): void
    {
        if (static::$schemaLoaded) {
            return;
        }

        if (! Schema::hasTable('departments')) {
            $dumpPath = base_path('sep_hrms.sql');

            if (! file_exists($dumpPath)) {
                throw new RuntimeException('Test schema dump not found at ' . $dumpPath);
            }

            DB::statement('SET FOREIGN_KEY_CHECKS=0');
            DB::unprepared(file_get_contents($dumpPath));
            DB::statement('SET FOREIGN_KEY_CHECKS=1');
        }

        static::$schemaLoaded = true;
    }

    protected function ensurePermissionSchemaUpToDate(): void
    {
        if (Schema::hasTable('permissions') && ! Schema::hasColumn('permissions', 'guard_name')) {
            Schema::table('permissions', function (Blueprint $table) {
                $table->string('guard_name', 50)->default('web')->after('name');
            });
        }

        if (Schema::hasTable('roles') && ! Schema::hasColumn('roles', 'guard_name')) {
            Schema::table('roles', function (Blueprint $table) {
                $table->string('guard_name', 50)->default('web')->after('name');
            });
        }
    }
}
