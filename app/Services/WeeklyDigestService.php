<?php

namespace App\Services;

use App\Models\Contract;
use App\Models\Document;
use App\Models\Employee;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Spatie\Activitylog\Models\Activity;

class WeeklyDigestService
{
    public function generateDigestData(): array
    {
        return [
            'contracts' => $this->getContractData(),
            'documents' => $this->getDocumentData(),
            'birthdays' => $this->getUpcomingBirthdays(),
            'new_hires' => $this->getNewHires(),
            'pending_approvals' => $this->getPendingApprovals(),
            'activity_summary' => $this->getActivitySummary()
        ];
    }

    protected function getContractData(): array
    {
        return [
            'expiring_urgently' => Contract::expiringUrgently()
                ->with(['employee'])
                ->get(),
            'expiring_critically' => Contract::expiringCritically()
                ->with(['employee'])
                ->get(),
            'expiring_soon' => Contract::expiringSoon()
                ->with(['employee'])
                ->get(),
        ];
    }

    protected function getDocumentData(): array
    {
        return [
            'expiring' => Document::expiringSoon(30)
                ->with(['employee', 'contract.employee'])
                ->get()
        ];
    }

    protected function getUpcomingBirthdays(): \Illuminate\Database\Eloquent\Collection
    {
        // Get employees with birthdays in the next 7 days
        $today = now();
        $nextWeek = now()->addDays(7);

        return Employee::whereNotNull('birth_date')
            ->where('employment_status', 'active')
            ->get()
            ->filter(function ($employee) use ($today, $nextWeek) {
                if (!$employee->birth_date) {
                    return false;
                }

                $thisYearBirthday = Carbon::createFromFormat(
                    'Y-m-d',
                    $today->year . '-' . $employee->birth_date->format('m-d')
                );

                // If birthday already passed this year, check next year
                if ($thisYearBirthday->lt($today)) {
                    $thisYearBirthday->addYear();
                }

                return $thisYearBirthday->between($today, $nextWeek);
            })
            ->sortBy(function ($employee) use ($today) {
                $thisYearBirthday = Carbon::createFromFormat(
                    'Y-m-d',
                    $today->year . '-' . $employee->birth_date->format('m-d')
                );

                if ($thisYearBirthday->lt($today)) {
                    $thisYearBirthday->addYear();
                }

                return $thisYearBirthday;
            });
    }

    protected function getNewHires(): \Illuminate\Database\Eloquent\Collection
    {
        $weekStart = now()->startOfWeek();

        return Employee::where('hire_date', '>=', $weekStart)
            ->where('employment_status', 'active')
            ->with(['position', 'department'])
            ->orderBy('hire_date', 'desc')
            ->get();
    }

    protected function getPendingApprovals(): array
    {
        // Placeholder for future approval workflow system
        // This would integrate with contracts awaiting approval, payroll runs, etc.
        return [];
    }

    protected function getActivitySummary(): array
    {
        $weekStart = now()->startOfWeek();

        return [
            'contracts_created' => Activity::where('log_name', 'contract')
                ->where('description', 'Contract created')
                ->where('created_at', '>=', $weekStart)
                ->count(),

            'employees_added' => Employee::where('created_at', '>=', $weekStart)
                ->count(),

            'documents_uploaded' => Activity::where('log_name', 'document')
                ->where('description', 'Document created')
                ->where('created_at', '>=', $weekStart)
                ->count(),

            'salary_changes' => Activity::where('log_name', 'salary_structure')
                ->where('created_at', '>=', $weekStart)
                ->count(),

            'critical_employee_changes' => Activity::where('log_name', 'employee_critical')
                ->where('created_at', '>=', $weekStart)
                ->count(),
        ];
    }

    public function getDigestRecipients(): \Illuminate\Database\Eloquent\Collection
    {
        // Send weekly digest to HR Admin, HR Coordinator, and IT Admin
        return User::whereHas('roles', function ($query) {
            $query->whereIn('name', ['HR_Admin_Manager', 'HR_Coordinator', 'IT_Admin']);
        })->get();
    }

    public function shouldSendDigest(): bool
    {
        $digestData = $this->generateDigestData();

        // Send digest if there are any of these conditions:
        // 1. Expiring contracts (urgent, critical, or soon)
        // 2. Expiring documents
        // 3. Upcoming birthdays
        // 4. New hires this week
        // 5. Significant activity (more than 5 total activities)

        $hasExpiringContracts = !empty($digestData['contracts']['expiring_urgently']) ||
                               !empty($digestData['contracts']['expiring_critically']) ||
                               !empty($digestData['contracts']['expiring_soon']);

        $hasExpiringDocuments = !empty($digestData['documents']['expiring']);
        $hasBirthdays = !empty($digestData['birthdays']);
        $hasNewHires = !empty($digestData['new_hires']);

        $totalActivity = array_sum($digestData['activity_summary']);
        $hasSignificantActivity = $totalActivity >= 5;

        return $hasExpiringContracts ||
               $hasExpiringDocuments ||
               $hasBirthdays ||
               $hasNewHires ||
               $hasSignificantActivity;
    }

    public function getDigestPreview(): array
    {
        $digestData = $this->generateDigestData();

        return [
            'data' => $digestData,
            'recipients' => $this->getDigestRecipients(),
            'should_send' => $this->shouldSendDigest(),
            'summary' => [
                'expiring_contracts_total' =>
                    count($digestData['contracts']['expiring_urgently']) +
                    count($digestData['contracts']['expiring_critically']) +
                    count($digestData['contracts']['expiring_soon']),
                'expiring_documents_total' => count($digestData['documents']['expiring']),
                'birthdays_total' => count($digestData['birthdays']),
                'new_hires_total' => count($digestData['new_hires']),
                'activity_total' => array_sum($digestData['activity_summary']),
            ]
        ];
    }
}