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
        Schema::table('employees', function (Blueprint $table) {
            $table->string('photo_path')->nullable()->after('salary_visibility_flag');
            $table->string('photo_original_name')->nullable()->after('photo_path');
            $table->unsignedInteger('photo_size')->nullable()->after('photo_original_name');
            $table->string('photo_mime_type')->nullable()->after('photo_size');
            $table->timestamp('photo_uploaded_at')->nullable()->after('photo_mime_type');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('employees', function (Blueprint $table) {
            $table->dropColumn([
                'photo_path',
                'photo_original_name', 
                'photo_size',
                'photo_mime_type',
                'photo_uploaded_at'
            ]);
        });
    }
};
