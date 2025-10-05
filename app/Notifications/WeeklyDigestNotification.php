<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\Contract;
use App\Models\Document;
use App\Models\Employee;
use Carbon\Carbon;

class WeeklyDigestNotification extends Notification implements ShouldQueue
{
    use Queueable;

    protected $digestData;

    /**
     * Create a new notification instance.
     */
    public function __construct(array $digestData)
    {
        $this->digestData = $digestData;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        $weekStart = now()->startOfWeek()->format('M j');
        $weekEnd = now()->endOfWeek()->format('M j, Y');
        $locale = app()->getLocale();

        $message = (new MailMessage)
            ->subject(__('Weekly HRMS Digest') . " - {$weekStart} to {$weekEnd}")
            ->greeting(__('Weekly HRMS Digest'))
            ->line(__('Here\'s your weekly summary of important HRMS activities and upcoming items that require attention.'));

        // Contract Expiries Section
        if (!empty($this->digestData['contracts']['expiring_urgently']) ||
            !empty($this->digestData['contracts']['expiring_critically']) ||
            !empty($this->digestData['contracts']['expiring_soon'])) {

            $message->line('## ' . __('Contract Expiries'));

            if (!empty($this->digestData['contracts']['expiring_urgently'])) {
                $message->line(__('**🔴 Urgent (≤7 days):**'));
                foreach ($this->digestData['contracts']['expiring_urgently'] as $contract) {
                    $daysLeft = now()->diffInDays($contract->end_date, false);
                    $message->line("• {$contract->employee->display_name} - {$contract->type_name} ({$daysLeft} " . __('days') . ")");
                }
            }

            if (!empty($this->digestData['contracts']['expiring_critically'])) {
                $message->line(__('**🟠 Critical (8-15 days):**'));
                foreach ($this->digestData['contracts']['expiring_critically'] as $contract) {
                    $daysLeft = now()->diffInDays($contract->end_date, false);
                    $message->line("• {$contract->employee->display_name} - {$contract->type_name} ({$daysLeft} " . __('days') . ")");
                }
            }

            if (!empty($this->digestData['contracts']['expiring_soon'])) {
                $message->line(__('**🟡 Soon (16-30 days):**'));
                foreach ($this->digestData['contracts']['expiring_soon'] as $contract) {
                    $daysLeft = now()->diffInDays($contract->end_date, false);
                    $message->line("• {$contract->employee->display_name} - {$contract->type_name} ({$daysLeft} " . __('days') . ")");
                }
            }

            $message->action(__('View Contract Management'), route('contracts.index'));
        }

        // Document Expiries Section
        if (!empty($this->digestData['documents']['expiring'])) {
            $message->line('## ' . __('Document Expiries'));

            foreach ($this->digestData['documents']['expiring'] as $document) {
                $daysLeft = $document->expires_at ? now()->diffInDays($document->expires_at, false) : 0;
                $message->line("• {$document->owner_name} - {$document->type_display_name} ({$daysLeft} " . __('days') . ")");
            }

            $message->action(__('View Document Management'), route('documents.index'));
        }

        // Birthdays Section
        if (!empty($this->digestData['birthdays'])) {
            $message->line('## ' . __('Upcoming Birthdays') . ' 🎂');

            foreach ($this->digestData['birthdays'] as $employee) {
                $birthdayDate = Carbon::createFromFormat('m-d', $employee->birth_date->format('m-d'));
                if ($birthdayDate->isPast()) {
                    $birthdayDate->addYear();
                }
                $daysUntil = now()->diffInDays($birthdayDate, false);
                $message->line("• {$employee->display_name} - {$birthdayDate->format('M j')} ({$daysUntil} " . __('days') . ")");
            }
        }

        // New Hires Section
        if (!empty($this->digestData['new_hires'])) {
            $message->line('## ' . __('New Hires This Week') . ' 👋');

            foreach ($this->digestData['new_hires'] as $employee) {
                $message->line("• {$employee->display_name} - {$employee->position->name} ({$employee->hire_date->format('M j')})");
            }
        }

        // Pending Approvals Section (placeholder for future workflow system)
        if (!empty($this->digestData['pending_approvals'])) {
            $message->line('## ' . __('Pending Approvals'));
            $message->line(__('You have') . ' ' . count($this->digestData['pending_approvals']) . ' ' . __('items awaiting approval.'));
            $message->action(__('Review Pending Items'), route('dashboard'));
        }

        // Activity Summary
        if (!empty($this->digestData['activity_summary'])) {
            $message->line('## ' . __('Weekly Activity Summary'));
            $summary = $this->digestData['activity_summary'];

            if ($summary['contracts_created'] > 0) {
                $message->line("• {$summary['contracts_created']} " . __('new contracts created'));
            }
            if ($summary['employees_added'] > 0) {
                $message->line("• {$summary['employees_added']} " . __('new employees added'));
            }
            if ($summary['documents_uploaded'] > 0) {
                $message->line("• {$summary['documents_uploaded']} " . __('documents uploaded'));
            }
        }

        $message->line(__('Thank you for using our HRMS system!'))
                ->salutation(__('Best regards,') . "\n" . config('app.name'));

        return $message;
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'digest_data' => $this->digestData,
            'week_start' => now()->startOfWeek()->toDateString(),
            'week_end' => now()->endOfWeek()->toDateString(),
        ];
    }
}
