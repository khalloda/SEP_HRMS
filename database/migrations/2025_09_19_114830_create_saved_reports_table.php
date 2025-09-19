<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('saved_reports')) {
            Schema::create('saved_reports', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('user_id')->index();
                $table->string('name');
                $table->string('report_key', 100)->index(); // e.g., reports.employee.list
                $table->json('params')->nullable();
                $table->string('format', 10)->default('xlsx'); // csv|xlsx|pdf
                $table->string('schedule', 50)->nullable(); // cron or preset (daily, weekly)
                $table->text('recipients')->nullable(); // comma emails or JSON
                $table->timestamps();
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('saved_reports');
    }
};

