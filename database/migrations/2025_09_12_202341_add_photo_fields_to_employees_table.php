<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (! Schema::hasTable('employees')) {
            return;
        }

        Schema::table('employees', function (Blueprint $table) {
            if (! Schema::hasColumn('employees', 'photo_path')) {
                $table->string('photo_path')->nullable()->after('salary_visibility_flag');
            }
            if (! Schema::hasColumn('employees', 'photo_original_name')) {
                $table->string('photo_original_name')->nullable()->after('photo_path');
            }
            if (! Schema::hasColumn('employees', 'photo_size')) {
                $table->unsignedInteger('photo_size')->nullable()->after('photo_original_name');
            }
            if (! Schema::hasColumn('employees', 'photo_mime_type')) {
                $table->string('photo_mime_type')->nullable()->after('photo_size');
            }
            if (! Schema::hasColumn('employees', 'photo_uploaded_at')) {
                $table->timestamp('photo_uploaded_at')->nullable()->after('photo_mime_type');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (! Schema::hasTable('employees')) {
            return;
        }

        Schema::table('employees', function (Blueprint $table) {
            $columns = [
                'photo_path',
                'photo_original_name',
                'photo_size',
                'photo_mime_type',
                'photo_uploaded_at',
            ];

            $existing = array_filter($columns, fn ($column) => Schema::hasColumn('employees', $column));

            if (! empty($existing)) {
                $table->dropColumn($existing);
            }
        });
    }
};

