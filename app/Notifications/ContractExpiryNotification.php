<?php

namespace App\Notifications;

use App\Models\Contract;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ContractExpiryNotification extends Notification implements ShouldQueue
{
    use Queueable;

    /**
     * The contract instance.
     */
    protected Contract $contract;

    /**
     * The notification type (urgent, critical, soon).
     */
    protected string $notificationType;

    /**
     * Create a new notification instance.
     */
    public function __construct(Contract $contract, string $notificationType)
    {
        $this->contract = $contract;
        $this->notificationType = $notificationType;
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
        $employee = $this->contract->employee;
        $daysUntilExpiry = $this->contract->days_until_expiry;
        $subject = $this->getSubject();
        $greeting = $this->getGreeting();

        $message = (new MailMessage)
            ->subject($subject)
            ->greeting($greeting)
            ->line($this->getIntroLine())
            ->line("**Employee:** {$employee->display_name} ({$employee->code})")
            ->line("**Contract Type:** {$this->contract->type_name}")
            ->line("**End Date:** {$this->contract->end_date->format('F j, Y')}")
            ->line("**Days Remaining:** {$daysUntilExpiry} days")
            ->action('View Contract Details', route('contracts.show', $this->contract))
            ->line($this->getActionLine());

        // Add urgency styling based on type
        if ($this->notificationType === 'urgent') {
            $message->error();
        } elseif ($this->notificationType === 'critical') {
            $message->level('warning');
        }

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
            'contract_id' => $this->contract->id,
            'employee_name' => $this->contract->employee->display_name,
            'employee_code' => $this->contract->employee->code,
            'contract_type' => $this->contract->type,
            'end_date' => $this->contract->end_date->toDateString(),
            'days_until_expiry' => $this->contract->days_until_expiry,
            'notification_type' => $this->notificationType,
            'urgency_level' => $this->contract->expiry_urgency_level,
        ];
    }

    /**
     * Get the email subject based on notification type.
     */
    protected function getSubject(): string
    {
        $employee = $this->contract->employee;
        
        return match($this->notificationType) {
            'urgent' => "🚨 URGENT: Contract expires in {$this->contract->days_until_expiry} days - {$employee->display_name}",
            'critical' => "⚠️ CRITICAL: Contract expires in {$this->contract->days_until_expiry} days - {$employee->display_name}",
            'soon' => "📅 Contract expires in {$this->contract->days_until_expiry} days - {$employee->display_name}",
            default => "Contract Expiry Notice - {$employee->display_name}"
        };
    }

    /**
     * Get the email greeting based on notification type.
     */
    protected function getGreeting(): string
    {
        return match($this->notificationType) {
            'urgent' => 'Urgent Action Required!',
            'critical' => 'Critical Notice',
            'soon' => 'Important Reminder',
            default => 'Contract Expiry Notice'
        };
    }

    /**
     * Get the introduction line based on notification type.
     */
    protected function getIntroLine(): string
    {
        $daysUntilExpiry = $this->contract->days_until_expiry;
        
        return match($this->notificationType) {
            'urgent' => "A contract is expiring in {$daysUntilExpiry} days and requires immediate attention. Please take action to renew or terminate this contract.",
            'critical' => "A contract is expiring in {$daysUntilExpiry} days. Please review and take appropriate action soon.",
            'soon' => "This is a reminder that a contract will expire in {$daysUntilExpiry} days. Please review and plan accordingly.",
            default => "A contract expiry notification."
        };
    }

    /**
     * Get the action line based on notification type.
     */
    protected function getActionLine(): string
    {
        return match($this->notificationType) {
            'urgent' => 'Please review this contract immediately and take appropriate action (renew or terminate).',
            'critical' => 'Please review this contract and prepare for renewal or termination.',
            'soon' => 'Please review this contract and begin planning for renewal or termination.',
            default => 'Please review this contract at your earliest convenience.'
        };
    }

    /**
     * Get the notification's unique ID for deduplication.
     */
    public function uniqueId(): string
    {
        return "contract_expiry_{$this->contract->id}_{$this->notificationType}_" . now()->toDateString();
    }
}
