<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    public function up(): void
    {
        if (Schema::hasTable('salary_structures')) {
            if (! $this->indexExistsMysql('salary_structures', 'idx_salary_structures_emp_from')) {
                DB::statement('CREATE INDEX idx_salary_structures_emp_from ON salary_structures (employee_id, effective_from)');
            }
        }

        if (Schema::hasTable('salary_structure_components')) {
            if (! $this->indexExistsMysql('salary_structure_components', 'idx_ssc_structure_order')) {
                DB::statement('CREATE INDEX idx_ssc_structure_order ON salary_structure_components (structure_id, priority_order)');
            }
        }
    }

    public function down(): void
    {
        // Non-destructive policy: do not drop indexes automatically on down without approval
    }

    private function indexExistsMysql(string $table, string $index): bool
    {
        $schema = DB::getDatabaseName();
        $result = DB::select(
            'SELECT COUNT(1) AS cnt FROM information_schema.statistics WHERE table_schema = ? AND table_name = ? AND index_name = ? LIMIT 1',
            [$schema, $table, $index]
        );
        return !empty($result) && ((int) ($result[0]->cnt ?? 0) > 0);
    }
};
