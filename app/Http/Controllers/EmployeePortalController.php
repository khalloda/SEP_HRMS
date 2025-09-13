<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\Contract;
use App\Models\Document;
use App\Models\Payslip;
use App\Models\AttendanceSummary;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class EmployeePortalController extends Controller
{
    /**
     * Employee dashboard with personalized widgets.
     */
    public function dashboard()
    {
        $user = Auth::user();
        $employee = $user->employee;

        if (!$employee) {
            return redirect()->route('profile')->with('warning', __('Please complete your employee profile first.'));
        }

        $dashboardData = [
            'employee' => $employee,
            'profile_completion' => $this->calculateProfileCompletion($employee),
            'recent_documents' => $this->getRecentDocuments($employee),
            'active_contract' => $this->getActiveContract($employee),
            'recent_payslips' => $this->getRecentPayslips($employee),
            'attendance_summary' => $this->getAttendanceSummary($employee),
            'upcoming_events' => $this->getUpcomingEvents($employee),
            'quick_actions' => $this->getQuickActions($employee),
        ];

        return view('employee-portal.dashboard', $dashboardData);
    }

    /**
     * Employee profile management.
     */
    public function profile()
    {
        $user = Auth::user();
        $employee = $user->employee;

        if (!$employee) {
            return redirect()->route('profile')->with('warning', __('Employee profile not found.'));
        }

        return view('employee-portal.profile', compact('employee'));
    }

    /**
     * Update employee profile (limited fields).
     */
    public function updateProfile(Request $request)
    {
        $user = Auth::user();
        $employee = $user->employee;

        if (!$employee) {
            return redirect()->back()->with('error', __('Employee profile not found.'));
        }

        $request->validate([
            'phone' => 'nullable|string|max:20',
            'personal_email' => 'nullable|email|max:255',
            'emergency_contact_name' => 'nullable|string|max:255',
            'emergency_contact_phone' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:500',
        ]);

        $employee->update($request->only([
            'phone',
            'personal_email',
            'emergency_contact_name',
            'emergency_contact_phone',
            'address'
        ]));

        return redirect()->back()->with('success', __('Profile updated successfully.'));
    }

    /**
     * Employee documents portal with enhanced filtering and search.
     */
    public function documents(Request $request)
    {
        $user = Auth::user();
        $employee = $user->employee;

        if (!$employee) {
            return redirect()->route('profile')->with('warning', __('Employee profile not found.'));
        }

        // Build base query
        $query = Document::where('employee_id', $employee->id)
            ->where(function ($q) use ($user) {
                $q->where('visibility', 'shared')
                  ->orWhere('visibility', 'private');
            })
            ->with(['tags', 'versions', 'createdBy']);

        // Apply filters
        if ($request->has('type') && $request->type != '') {
            $query->where('type', $request->type);
        }

        if ($request->has('status') && $request->status != '') {
            switch ($request->status) {
                case 'expired':
                    $query->where('expires_at', '<', now());
                    break;
                case 'expiring_soon':
                    $query->whereBetween('expires_at', [now(), now()->addDays(30)]);
                    break;
                case 'valid':
                    $query->where(function ($q) {
                        $q->whereNull('expires_at')
                          ->orWhere('expires_at', '>', now()->addDays(30));
                    });
                    break;
            }
        }

        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('original_name', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%")
                  ->orWhereHas('tags', function ($tagQuery) use ($search) {
                      $tagQuery->where('name', 'like', "%{$search}%");
                  });
            });
        }

        // Sort options
        $sortBy = $request->get('sort', 'created_at');
        $sortDirection = $request->get('direction', 'desc');
        $query->orderBy($sortBy, $sortDirection);

        $documents = $query->paginate(15);

        // Enhanced statistics
        $documentStats = [
            'total' => Document::where('employee_id', $employee->id)->count(),
            'by_type' => Document::where('employee_id', $employee->id)
                ->selectRaw('type, COUNT(*) as count')
                ->groupBy('type')
                ->pluck('count', 'type')
                ->toArray(),
            'expiring_soon' => Document::where('employee_id', $employee->id)
                ->expiringSoon(30)
                ->count(),
            'expired' => Document::where('employee_id', $employee->id)
                ->where('expires_at', '<', now())
                ->count(),
            'this_month' => Document::where('employee_id', $employee->id)
                ->whereMonth('created_at', now()->month)
                ->whereYear('created_at', now()->year)
                ->count(),
        ];

        // Get HR Letters for this employee
        $hrLetters = \App\Models\GeneratedLetter::where('employee_id', $employee->id)
            ->whereIn('status', ['approved', 'sent'])
            ->with(['letterTemplate', 'generatedBy'])
            ->latest()
            ->limit(5)
            ->get();

        // Document types for filter dropdown
        $documentTypes = Document::TYPES;

        return view('employee-portal.documents', compact(
            'documents',
            'documentStats',
            'hrLetters',
            'documentTypes'
        ));
    }

    /**
     * Bulk download selected documents.
     */
    public function bulkDownloadDocuments(Request $request)
    {
        $user = Auth::user();
        $employee = $user->employee;

        if (!$employee) {
            return redirect()->back()->with('error', __('Employee profile not found.'));
        }

        $request->validate([
            'document_ids' => 'required|array|min:1',
            'document_ids.*' => 'exists:documents,id'
        ]);

        $documents = Document::where('employee_id', $employee->id)
            ->whereIn('id', $request->document_ids)
            ->where(function ($query) {
                $query->where('visibility', 'shared')
                      ->orWhere('visibility', 'private');
            })
            ->get();

        if ($documents->isEmpty()) {
            return redirect()->back()->with('error', __('No valid documents found for download.'));
        }

        // Create a ZIP file with selected documents
        $zipFileName = 'documents_' . $employee->code . '_' . now()->format('Y_m_d_H_i_s') . '.zip';
        $zipPath = storage_path('app/temp/' . $zipFileName);

        // Ensure temp directory exists
        if (!file_exists(storage_path('app/temp'))) {
            mkdir(storage_path('app/temp'), 0755, true);
        }

        $zip = new \ZipArchive();
        if ($zip->open($zipPath, \ZipArchive::CREATE) !== TRUE) {
            return redirect()->back()->with('error', __('Could not create download archive.'));
        }

        foreach ($documents as $document) {
            $filePath = storage_path('app/' . $document->file_path);
            if (file_exists($filePath)) {
                $zip->addFile($filePath, $document->original_name);
            }
        }

        $zip->close();

        // Log the bulk download activity
        activity('bulk_document_download')
            ->performedOn($employee)
            ->causedBy($user)
            ->withProperties([
                'document_count' => $documents->count(),
                'document_names' => $documents->pluck('original_name')->toArray(),
            ])
            ->log('Bulk document download');

        return response()->download($zipPath)->deleteFileAfterSend(true);
    }

    /**
     * Request document update from HR.
     */
    public function requestDocumentUpdate(Request $request)
    {
        $user = Auth::user();
        $employee = $user->employee;

        if (!$employee) {
            return redirect()->back()->with('error', __('Employee profile not found.'));
        }

        $request->validate([
            'document_type' => 'required|string',
            'reason' => 'required|string|max:1000',
            'notes' => 'nullable|string|max:2000',
        ]);

        // Log the document update request
        activity('document_update_request')
            ->performedOn($employee)
            ->causedBy($user)
            ->withProperties([
                'document_type' => $request->document_type,
                'reason' => $request->reason,
                'notes' => $request->notes,
                'requested_at' => now(),
            ])
            ->log('Document update requested');

        return redirect()->back()->with('success', __('Document update request submitted successfully. HR will contact you within 2-3 business days.'));
    }

    /**
     * Employee payslips portal.
     */
    public function payslips()
    {
        $user = Auth::user();
        $employee = $user->employee;

        if (!$employee) {
            return redirect()->route('profile')->with('warning', __('Employee profile not found.'));
        }

        $payslips = Payslip::where('employee_id', $employee->id)
            ->with(['payrollRun'])
            ->orderBy('pay_period_start', 'desc')
            ->paginate(12);

        $payslipStats = [
            'total_payslips' => $payslips->total(),
            'current_year' => Payslip::where('employee_id', $employee->id)
                ->whereYear('pay_period_start', now()->year)
                ->count(),
            'last_payslip' => Payslip::where('employee_id', $employee->id)
                ->latest('pay_period_start')
                ->first(),
        ];

        return view('employee-portal.payslips', compact('payslips', 'payslipStats'));
    }

    /**
     * Employee attendance portal.
     */
    public function attendance()
    {
        $user = Auth::user();
        $employee = $user->employee;

        if (!$employee) {
            return redirect()->route('profile')->with('warning', __('Employee profile not found.'));
        }

        $currentMonth = now()->format('Y-m');
        $attendanceSummaries = AttendanceSummary::where('employee_id', $employee->id)
            ->where('date', '>=', now()->startOfYear())
            ->orderBy('date', 'desc')
            ->paginate(31);

        $attendanceStats = [
            'current_month_days' => AttendanceSummary::where('employee_id', $employee->id)
                ->whereMonth('date', now()->month)
                ->whereYear('date', now()->year)
                ->sum('work_hours'),
            'current_month_present' => AttendanceSummary::where('employee_id', $employee->id)
                ->whereMonth('date', now()->month)
                ->whereYear('date', now()->year)
                ->where('status', 'present')
                ->count(),
            'current_month_late' => AttendanceSummary::where('employee_id', $employee->id)
                ->whereMonth('date', now()->month)
                ->whereYear('date', now()->year)
                ->where('late_minutes', '>', 0)
                ->count(),
            'current_month_overtime' => AttendanceSummary::where('employee_id', $employee->id)
                ->whereMonth('date', now()->month)
                ->whereYear('date', now()->year)
                ->sum('overtime_hours'),
        ];

        return view('employee-portal.attendance', compact('attendanceSummaries', 'attendanceStats'));
    }

    /**
     * Request HR letter.
     */
    public function requestLetter()
    {
        $user = Auth::user();
        $employee = $user->employee;

        if (!$employee) {
            return redirect()->route('profile')->with('warning', __('Employee profile not found.'));
        }

        return view('employee-portal.request-letter', compact('employee'));
    }

    /**
     * Submit HR letter request.
     */
    public function submitLetterRequest(Request $request)
    {
        $user = Auth::user();
        $employee = $user->employee;

        if (!$employee) {
            return redirect()->back()->with('error', __('Employee profile not found.'));
        }

        $request->validate([
            'letter_type' => 'required|in:employment_verification,salary_certificate,experience_letter,custom',
            'purpose' => 'required|string|max:500',
            'custom_requirements' => 'nullable|string|max:1000',
        ]);

        // For now, we'll log this as an activity - in future this could integrate with a ticketing system
        activity('hr_letter_request')
            ->performedOn($employee)
            ->causedBy($user)
            ->withProperties([
                'letter_type' => $request->letter_type,
                'purpose' => $request->purpose,
                'custom_requirements' => $request->custom_requirements,
                'requested_at' => now(),
            ])
            ->log('HR letter requested');

        return redirect()->back()->with('success', __('HR letter request submitted successfully. You will be contacted by HR within 2-3 business days.'));
    }

    /**
     * Calculate profile completion percentage.
     */
    private function calculateProfileCompletion(Employee $employee): int
    {
        $fields = [
            'phone', 'personal_email', 'birth_date', 'address',
            'emergency_contact_name', 'emergency_contact_phone',
            'marital_status', 'photo'
        ];

        $completed = 0;
        foreach ($fields as $field) {
            if (!empty($employee->$field)) {
                $completed++;
            }
        }

        return round(($completed / count($fields)) * 100);
    }

    /**
     * Get recent documents for dashboard.
     */
    private function getRecentDocuments(Employee $employee)
    {
        return Document::where('employee_id', $employee->id)
            ->where('visibility', '!=', 'private')
            ->latest()
            ->limit(5)
            ->get();
    }

    /**
     * Get active contract.
     */
    private function getActiveContract(Employee $employee)
    {
        return Contract::where('employee_id', $employee->id)
            ->where('status', 'active')
            ->latest()
            ->first();
    }

    /**
     * Get recent payslips.
     */
    private function getRecentPayslips(Employee $employee)
    {
        return Payslip::where('employee_id', $employee->id)
            ->latest('pay_period_start')
            ->limit(3)
            ->get();
    }

    /**
     * Get attendance summary for current month.
     */
    private function getAttendanceSummary(Employee $employee)
    {
        return AttendanceSummary::where('employee_id', $employee->id)
            ->whereMonth('date', now()->month)
            ->whereYear('date', now()->year)
            ->selectRaw('
                SUM(work_hours) as total_hours,
                SUM(overtime_hours) as total_overtime,
                COUNT(CASE WHEN status = "present" THEN 1 END) as present_days,
                COUNT(CASE WHEN late_minutes > 0 THEN 1 END) as late_days
            ')
            ->first();
    }

    /**
     * Get upcoming events (birthdays, contract renewals, etc.).
     */
    private function getUpcomingEvents(Employee $employee)
    {
        $events = [];

        // Upcoming birthday
        if ($employee->birth_date) {
            $nextBirthday = Carbon::createFromFormat('Y-m-d', now()->year . '-' . $employee->birth_date->format('m-d'));
            if ($nextBirthday->isPast()) {
                $nextBirthday->addYear();
            }
            if ($nextBirthday->diffInDays(now()) <= 30) {
                $events[] = [
                    'type' => 'birthday',
                    'title' => __('Your Birthday'),
                    'date' => $nextBirthday,
                    'icon' => 'fas fa-birthday-cake',
                    'color' => 'info'
                ];
            }
        }

        // Contract expiry
        $activeContract = $this->getActiveContract($employee);
        if ($activeContract && $activeContract->end_date) {
            $daysUntilExpiry = now()->diffInDays($activeContract->end_date, false);
            if ($daysUntilExpiry <= 60 && $daysUntilExpiry >= 0) {
                $events[] = [
                    'type' => 'contract_expiry',
                    'title' => __('Contract Renewal'),
                    'date' => $activeContract->end_date,
                    'icon' => 'fas fa-file-contract',
                    'color' => $daysUntilExpiry <= 30 ? 'warning' : 'primary'
                ];
            }
        }

        return collect($events)->sortBy('date');
    }

    /**
     * Get quick actions for employee.
     */
    private function getQuickActions(Employee $employee)
    {
        return [
            [
                'title' => __('View Documents'),
                'url' => route('employee-portal.documents'),
                'icon' => 'fas fa-folder-open',
                'color' => 'primary'
            ],
            [
                'title' => __('Download Payslips'),
                'url' => route('employee-portal.payslips'),
                'icon' => 'fas fa-file-invoice-dollar',
                'color' => 'success'
            ],
            [
                'title' => __('View Attendance'),
                'url' => route('employee-portal.attendance'),
                'icon' => 'fas fa-clock',
                'color' => 'info'
            ],
            [
                'title' => __('Request HR Letter'),
                'url' => route('employee-portal.request-letter'),
                'icon' => 'fas fa-envelope',
                'color' => 'warning'
            ],
            [
                'title' => __('Update Profile'),
                'url' => route('employee-portal.profile'),
                'icon' => 'fas fa-user-edit',
                'color' => 'secondary'
            ]
        ];
    }
}