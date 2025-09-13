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
        Schema::create('contract_expiry_notifications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('contract_id')->constrained()->onDelete('cascade');
            $table->enum('notification_type', ['urgent', 'critical', 'soon']); // 7, 15, 30 days
            $table->date('notification_date');
            $table->json('recipients'); // Array of email addresses that were notified
            $table->string('status', 20)->default('sent'); // sent, failed, pending
            $table->text('message')->nullable(); // Store the notification content
            $table->json('metadata')->nullable(); // Additional data like days_until_expiry, etc.
            $table->timestamps();

            // Ensure we don't send duplicate notifications for the same contract/type/date
            $table->unique(['contract_id', 'notification_type', 'notification_date'], 'unique_contract_notification');
            
            // Index for efficient queries (fix MySQL key length issue)
            $table->index('notification_date', 'idx_notification_date');
            $table->index('status', 'idx_status');
            $table->index(['contract_id', 'notification_type'], 'idx_contract_type');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('contract_expiry_notifications');
    }
};
