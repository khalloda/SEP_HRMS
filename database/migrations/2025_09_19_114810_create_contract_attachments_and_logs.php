<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('contract_attachments')) {
            Schema::create('contract_attachments', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('contract_id')->index();
                $table->string('type', 64)->nullable();
                $table->string('path');
                $table->json('meta')->nullable();
                $table->timestamps();
            });
        }
        if (!Schema::hasTable('contract_workflow_logs')) {
            Schema::create('contract_workflow_logs', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('contract_id')->index();
                $table->string('from_status', 32)->nullable();
                $table->string('to_status', 32);
                $table->unsignedBigInteger('by_user_id')->nullable()->index();
                $table->text('note')->nullable();
                $table->timestamps();
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('contract_workflow_logs');
        Schema::dropIfExists('contract_attachments');
    }
};

