<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\WeeklyDigestService;
use App\Notifications\WeeklyDigestNotification;
use Illuminate\Support\Facades\Notification;

class SendWeeklyDigest extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'hrms:send-weekly-digest {--preview : Show digest preview without sending} {--force : Send digest even if criteria not met}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send weekly HRMS digest emails to administrators';

    protected WeeklyDigestService $digestService;

    public function __construct(WeeklyDigestService $digestService)
    {
        parent::__construct();
        $this->digestService = $digestService;
    }

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('🔄 Generating weekly HRMS digest...');

        $digestData = $this->digestService->generateDigestData();
        $recipients = $this->digestService->getDigestRecipients();
        $shouldSend = $this->digestService->shouldSendDigest();

        // Show preview if requested
        if ($this->option('preview')) {
            $this->showDigestPreview($digestData, $recipients, $shouldSend);
            return 0;
        }

        // Check if we should send the digest
        if (!$shouldSend && !$this->option('force')) {
            $this->info('📊 No significant activities or expiring items found.');
            $this->info('💡 Use --preview to see what would be included or --force to send anyway.');
            return 0;
        }

        if ($recipients->isEmpty()) {
            $this->error('❌ No recipients found. Make sure HR Admin, HR Coordinator, or IT Admin roles exist.');
            return 1;
        }

        // Send the digest
        $this->info('📧 Sending weekly digest to ' . $recipients->count() . ' recipients...');

        $sentCount = 0;
        foreach ($recipients as $user) {
            try {
                $user->notify(new WeeklyDigestNotification($digestData));
                $this->info("   ✅ Sent to: {$user->name} ({$user->email})");
                $sentCount++;
            } catch (\Exception $e) {
                $this->error("   ❌ Failed to send to {$user->name}: " . $e->getMessage());
            }
        }

        $this->info("🎉 Weekly digest sent successfully to {$sentCount}/{$recipients->count()} recipients!");

        // Log the digest sending
        activity('system')
            ->withProperties([
                'recipients_count' => $sentCount,
                'total_recipients' => $recipients->count(),
                'digest_summary' => [
                    'contracts_expiring' =>
                        count($digestData['contracts']['expiring_urgently']) +
                        count($digestData['contracts']['expiring_critically']) +
                        count($digestData['contracts']['expiring_soon']),
                    'documents_expiring' => count($digestData['documents']['expiring']),
                    'birthdays' => count($digestData['birthdays']),
                    'new_hires' => count($digestData['new_hires']),
                    'activity_total' => array_sum($digestData['activity_summary'])
                ]
            ])
            ->log('Weekly digest email sent');

        return 0;
    }

    protected function showDigestPreview(array $digestData, $recipients, bool $shouldSend): void
    {
        $this->info('📋 Weekly Digest Preview');
        $this->info('========================');

        // Recipients
        $this->info("\n👥 Recipients ({$recipients->count()}):");
        foreach ($recipients as $user) {
            $this->info("   • {$user->name} ({$user->email}) - {$user->roles->pluck('name')->join(', ')}");
        }

        // Should send check
        $this->info("\n🤔 Send Status: " . ($shouldSend ? '✅ Will send (criteria met)' : '❌ Will not send (criteria not met)'));

        // Contract expiries
        $contractsTotal = count($digestData['contracts']['expiring_urgently']) +
                         count($digestData['contracts']['expiring_critically']) +
                         count($digestData['contracts']['expiring_soon']);

        if ($contractsTotal > 0) {
            $this->info("\n📄 Contract Expiries ({$contractsTotal}):");
            if (!empty($digestData['contracts']['expiring_urgently'])) {
                $this->error("   🔴 Urgent (≤7 days): " . count($digestData['contracts']['expiring_urgently']));
                foreach ($digestData['contracts']['expiring_urgently'] as $contract) {
                    $days = now()->diffInDays($contract->end_date, false);
                    $this->error("      • {$contract->employee->display_name} - {$contract->type_name} ({$days} days)");
                }
            }

            if (!empty($digestData['contracts']['expiring_critically'])) {
                $this->warn("   🟠 Critical (8-15 days): " . count($digestData['contracts']['expiring_critically']));
                foreach ($digestData['contracts']['expiring_critically'] as $contract) {
                    $days = now()->diffInDays($contract->end_date, false);
                    $this->warn("      • {$contract->employee->display_name} - {$contract->type_name} ({$days} days)");
                }
            }

            if (!empty($digestData['contracts']['expiring_soon'])) {
                $this->info("   🟡 Soon (16-30 days): " . count($digestData['contracts']['expiring_soon']));
                foreach ($digestData['contracts']['expiring_soon'] as $contract) {
                    $days = now()->diffInDays($contract->end_date, false);
                    $this->info("      • {$contract->employee->display_name} - {$contract->type_name} ({$days} days)");
                }
            }
        } else {
            $this->info("\n📄 Contract Expiries: None");
        }

        // Document expiries
        if (!empty($digestData['documents']['expiring'])) {
            $this->info("\n📋 Document Expiries (" . count($digestData['documents']['expiring']) . "):");
            foreach ($digestData['documents']['expiring'] as $document) {
                $days = $document->expires_at ? now()->diffInDays($document->expires_at, false) : 0;
                $this->info("   • {$document->owner_name} - {$document->type_display_name} ({$days} days)");
            }
        } else {
            $this->info("\n📋 Document Expiries: None");
        }

        // Birthdays
        if (!empty($digestData['birthdays'])) {
            $this->info("\n🎂 Upcoming Birthdays (" . count($digestData['birthdays']) . "):");
            foreach ($digestData['birthdays'] as $employee) {
                $this->info("   • {$employee->display_name} - {$employee->birth_date->format('M j')}");
            }
        } else {
            $this->info("\n🎂 Upcoming Birthdays: None");
        }

        // New hires
        if (!empty($digestData['new_hires'])) {
            $this->info("\n👋 New Hires This Week (" . count($digestData['new_hires']) . "):");
            foreach ($digestData['new_hires'] as $employee) {
                $this->info("   • {$employee->display_name} - {$employee->position->name} ({$employee->hire_date->format('M j')})");
            }
        } else {
            $this->info("\n👋 New Hires This Week: None");
        }

        // Activity summary
        $this->info("\n📊 Weekly Activity Summary:");
        $summary = $digestData['activity_summary'];
        $totalActivity = array_sum($summary);

        if ($totalActivity > 0) {
            foreach ($summary as $type => $count) {
                if ($count > 0) {
                    $this->info("   • " . ucfirst(str_replace('_', ' ', $type)) . ": {$count}");
                }
            }
            $this->info("   📈 Total activities: {$totalActivity}");
        } else {
            $this->info("   No significant activity this week.");
        }

        $this->info("\n💡 Use --force to send the digest regardless of criteria.");
    }
}
