<?php

namespace App\Console\Commands;

use App\Models\Contract;
use App\Models\ContractExpiryNotification;
use App\Models\User;
use App\Notifications\ContractExpiryNotification as ContractExpiryNotificationClass;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Notification;
use Exception;

class SendContractExpiryNotifications extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'hrms:send-contract-notifications 
                            {--type=all : Notification type to send (urgent, critical, soon, all)}
                            {--dry-run : Show what would be sent without actually sending}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send contract expiry notifications to HR and management staff';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Starting contract expiry notification process...');

        // First, expire any overdue contracts
        $expiredCount = Contract::expireOverdueContracts();
        if ($expiredCount > 0) {
            $this->info("Automatically expired {$expiredCount} overdue contracts.");
        }

        $type = $this->option('type');
        $isDryRun = $this->option('dry-run');

        $notifications = [
            'urgent' => ['contracts' => [], 'sent' => 0, 'failed' => 0],
            'critical' => ['contracts' => [], 'sent' => 0, 'failed' => 0],
            'soon' => ['contracts' => [], 'sent' => 0, 'failed' => 0]
        ];

        // Get contracts requiring attention
        $contractsRequiringAttention = Contract::getContractsRequiringAttention();

        foreach ($contractsRequiringAttention as $notificationType => $contracts) {
            if ($type !== 'all' && $type !== $notificationType) {
                continue;
            }

            foreach ($contracts as $contract) {
                // Skip if we already sent this notification today
                if (ContractExpiryNotification::exists($contract, $notificationType)) {
                    $this->info("  ⏭️  Skipping {$contract->employee->display_name} ({$notificationType}) - already notified today");
                    continue;
                }

                $notifications[$notificationType]['contracts'][] = $contract;
                
                if (!$isDryRun) {
                    try {
                        $this->sendNotification($contract, $notificationType);
                        $notifications[$notificationType]['sent']++;
                        $this->info("  ✅ Sent {$notificationType} notification for {$contract->employee->display_name}");
                    } catch (Exception $e) {
                        $notifications[$notificationType]['failed']++;
                        $this->error("  ❌ Failed to send {$notificationType} notification for {$contract->employee->display_name}: {$e->getMessage()}");
                    }
                } else {
                    $this->info("  📧 Would send {$notificationType} notification for {$contract->employee->display_name} ({$contract->days_until_expiry} days)");
                }
            }
        }

        $this->displaySummary($notifications, $isDryRun);

        return Command::SUCCESS;
    }

    /**
     * Send notification for a contract.
     */
    protected function sendNotification(Contract $contract, string $notificationType)
    {
        // Get recipients based on roles
        $recipients = $this->getNotificationRecipients();

        if (empty($recipients)) {
            throw new Exception('No recipients found for notifications');
        }

        // Create the Laravel notification
        $notification = new ContractExpiryNotificationClass($contract, $notificationType);

        // Send to all recipients
        Notification::send($recipients, $notification);

        // Create notification record in our database
        $message = $this->buildNotificationMessage($contract, $notificationType);
        $recipientEmails = $recipients->pluck('email')->toArray();

        ContractExpiryNotification::createNotification(
            $contract,
            $notificationType,
            $recipientEmails,
            $message
        )->markAsSent();

        // Log activity
        activity('notification')
            ->performedOn($contract)
            ->withProperties([
                'notification_type' => $notificationType,
                'recipients_count' => count($recipientEmails),
                'days_until_expiry' => $contract->days_until_expiry,
            ])
            ->log("Contract expiry notification sent");
    }

    /**
     * Get users who should receive notifications.
     */
    protected function getNotificationRecipients()
    {
        // Send to HR Admin Manager and Accounting Manager roles
        return User::whereHas('roles', function ($query) {
            $query->whereIn('name', ['HR Admin Manager', 'Accounting Manager']);
        })->whereNotNull('email')->get();
    }

    /**
     * Build notification message.
     */
    protected function buildNotificationMessage(Contract $contract, string $notificationType): string
    {
        $employee = $contract->employee;
        $daysUntilExpiry = $contract->days_until_expiry;

        $urgencyText = match($notificationType) {
            'urgent' => 'URGENT',
            'critical' => 'CRITICAL',
            'soon' => 'REMINDER',
            default => 'NOTICE'
        };

        return "{$urgencyText}: Contract for {$employee->display_name} ({$employee->code}) expires in {$daysUntilExpiry} days on {$contract->end_date->format('Y-m-d')}.";
    }

    /**
     * Display summary of notifications.
     */
    protected function displaySummary(array $notifications, bool $isDryRun)
    {
        $this->newLine();
        $this->info('📊 Notification Summary:');
        $this->newLine();

        $totalSent = 0;
        $totalFailed = 0;
        $totalContracts = 0;

        foreach ($notifications as $type => $data) {
            $contractCount = count($data['contracts']);
            $sent = $data['sent'];
            $failed = $data['failed'];

            $totalContracts += $contractCount;
            $totalSent += $sent;
            $totalFailed += $failed;

            if ($contractCount > 0) {
                $statusIcon = $isDryRun ? '📋' : ($failed > 0 ? '⚠️' : '✅');
                $action = $isDryRun ? 'Would notify' : 'Sent';
                
                $this->line("  {$statusIcon} {$type}: {$contractCount} contracts, {$action}: {$sent}" . ($failed > 0 ? ", Failed: {$failed}" : ''));
                
                foreach ($data['contracts'] as $contract) {
                    $this->line("    • {$contract->employee->display_name} ({$contract->employee->code}) - {$contract->days_until_expiry} days");
                }
            }
        }

        $this->newLine();
        
        if ($isDryRun) {
            $this->info("🔍 DRY RUN SUMMARY: {$totalContracts} contracts would receive notifications");
        } else {
            $successIcon = $totalFailed === 0 ? '🎉' : '⚠️';
            $this->info("{$successIcon} FINAL SUMMARY: {$totalSent} notifications sent" . ($totalFailed > 0 ? ", {$totalFailed} failed" : ''));
        }
    }
}
