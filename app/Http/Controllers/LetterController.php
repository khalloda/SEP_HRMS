<?php

namespace App\Http\Controllers;

use App\Models\LetterTemplate;
use App\Models\GeneratedLetter;
use App\Models\Employee;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Mpdf\Mpdf;
use Carbon\Carbon;

class LetterController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display letter templates
     */
    public function index()
    {
        $this->authorize('viewAny', LetterTemplate::class);

        $templates = LetterTemplate::with(['createdBy', 'updatedBy'])
            ->when(request('type'), function ($query, $type) {
                return $query->byType($type);
            })
            ->when(request('language'), function ($query, $language) {
                return $query->byLanguage($language);
            })
            ->when(request('category'), function ($query, $category) {
                return $query->byCategory($category);
            })
            ->when(request('search'), function ($query, $search) {
                return $query->where(function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                      ->orWhere('subject', 'like', "%{$search}%")
                      ->orWhere('type', 'like', "%{$search}%");
                });
            })
            ->latest()
            ->paginate(15);

        $types = LetterTemplate::TYPES;
        $categories = LetterTemplate::CATEGORIES;

        return view('letters.templates.index', compact('templates', 'types', 'categories'));
    }

    /**
     * Show template creation form
     */
    public function createTemplate()
    {
        $this->authorize('create', LetterTemplate::class);

        $types = LetterTemplate::TYPES;
        $categories = LetterTemplate::CATEGORIES;
        $defaultVariables = LetterTemplate::DEFAULT_VARIABLES;

        return view('letters.templates.create', compact('types', 'categories', 'defaultVariables'));
    }

    /**
     * Store new template
     */
    public function storeTemplate(Request $request)
    {
        $this->authorize('create', LetterTemplate::class);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|string|max:100',
            'category' => 'required|string|max:100',
            'language' => 'required|in:en,ar',
            'subject' => 'required|string|max:500',
            'content' => 'required|string',
            'variables' => 'nullable|array',
            'is_active' => 'boolean'
        ]);

        $validated['created_by'] = Auth::id();
        $validated['updated_by'] = Auth::id();

        $template = LetterTemplate::create($validated);

        return redirect()->route('letters.templates.show', $template)
            ->with('success', __('Letter template created successfully.'));
    }

    /**
     * Show template details
     */
    public function showTemplate(LetterTemplate $template)
    {
        $this->authorize('view', $template);

        $template->load(['createdBy', 'updatedBy', 'generatedLetters.employee']);

        $recentLetters = $template->generatedLetters()
            ->with(['employee', 'generatedBy'])
            ->latest()
            ->take(10)
            ->get();

        return view('letters.templates.show', compact('template', 'recentLetters'));
    }

    /**
     * Edit template
     */
    public function editTemplate(LetterTemplate $template)
    {
        $this->authorize('update', $template);

        $types = LetterTemplate::TYPES;
        $categories = LetterTemplate::CATEGORIES;
        $defaultVariables = LetterTemplate::DEFAULT_VARIABLES;

        return view('letters.templates.edit', compact('template', 'types', 'categories', 'defaultVariables'));
    }

    /**
     * Update template
     */
    public function updateTemplate(Request $request, LetterTemplate $template)
    {
        $this->authorize('update', $template);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|string|max:100',
            'category' => 'required|string|max:100',
            'language' => 'required|in:en,ar',
            'subject' => 'required|string|max:500',
            'content' => 'required|string',
            'variables' => 'nullable|array',
            'is_active' => 'boolean'
        ]);

        $validated['updated_by'] = Auth::id();

        $template->update($validated);

        return redirect()->route('letters.templates.show', $template)
            ->with('success', __('Letter template updated successfully.'));
    }

    /**
     * Delete template
     */
    public function destroyTemplate(LetterTemplate $template)
    {
        $this->authorize('delete', $template);

        $template->delete();

        return redirect()->route('letters.templates.index')
            ->with('success', __('Letter template deleted successfully.'));
    }

    /**
     * Generate letter form
     */
    public function generateForm()
    {
        $this->authorize('create', GeneratedLetter::class);

        $employees = Employee::active()
            ->with(['department', 'position'])
            ->orderBy('display_name')
            ->get();

        $templates = LetterTemplate::active()
            ->orderBy('name')
            ->get()
            ->groupBy('category');

        return view('letters.generate', compact('employees', 'templates'));
    }

    /**
     * Preview letter before generating
     */
    public function preview(Request $request)
    {
        $this->authorize('create', GeneratedLetter::class);

        $validated = $request->validate([
            'template_id' => 'required|exists:letter_templates,id',
            'employee_id' => 'required|exists:employees,id',
            'additional_data' => 'nullable|array'
        ]);

        $template = LetterTemplate::findOrFail($validated['template_id']);
        $employee = Employee::with(['department', 'position', 'manager'])->findOrFail($validated['employee_id']);

        $additionalData = $validated['additional_data'] ?? [];

        $processedContent = $template->processContent($employee, $additionalData);
        $processedSubject = $this->processSubject($template->subject, $employee, $additionalData);

        return response()->json([
            'subject' => $processedSubject,
            'content' => $processedContent,
            'template' => $template->only(['name', 'type', 'language'])
        ]);
    }

    /**
     * Generate and store letter
     */
    public function generate(Request $request)
    {
        $this->authorize('create', GeneratedLetter::class);

        $validated = $request->validate([
            'template_id' => 'required|exists:letter_templates,id',
            'employee_id' => 'required|exists:employees,id',
            'additional_data' => 'nullable|array',
            'status' => 'in:draft,pending_approval'
        ]);

        $template = LetterTemplate::findOrFail($validated['template_id']);
        $employee = Employee::with(['department', 'position', 'manager'])->findOrFail($validated['employee_id']);

        $additionalData = $validated['additional_data'] ?? [];

        $processedContent = $template->processContent($employee, $additionalData);
        $processedSubject = $this->processSubject($template->subject, $employee, $additionalData);

        $generatedLetter = GeneratedLetter::create([
            'letter_template_id' => $template->id,
            'employee_id' => $employee->id,
            'subject' => $processedSubject,
            'content' => $processedContent,
            'status' => $validated['status'] ?? GeneratedLetter::STATUS_DRAFT,
            'generated_by' => Auth::id(),
            'additional_data' => $additionalData
        ]);

        return redirect()->route('letters.show', $generatedLetter)
            ->with('success', __('Letter generated successfully.'));
    }

    /**
     * Show generated letters list
     */
    public function generatedLetters()
    {
        $this->authorize('viewAny', GeneratedLetter::class);

        $letters = GeneratedLetter::with(['letterTemplate', 'employee', 'generatedBy', 'approvedBy'])
            ->when(request('status'), function ($query, $status) {
                return $query->byStatus($status);
            })
            ->when(request('employee_id'), function ($query, $employeeId) {
                return $query->byEmployee($employeeId);
            })
            ->when(request('search'), function ($query, $search) {
                return $query->where(function ($q) use ($search) {
                    $q->where('reference_number', 'like', "%{$search}%")
                      ->orWhere('subject', 'like', "%{$search}%")
                      ->orWhereHas('employee', function ($eq) use ($search) {
                          $eq->where('display_name', 'like', "%{$search}%")
                             ->orWhere('code', 'like', "%{$search}%");
                      });
                });
            })
            ->latest()
            ->paginate(20);

        $employees = Employee::active()->orderBy('display_name')->get();
        $statuses = GeneratedLetter::STATUSES;

        return view('letters.generated.index', compact('letters', 'employees', 'statuses'));
    }

    /**
     * Show generated letter
     */
    public function show(GeneratedLetter $letter)
    {
        $this->authorize('view', $letter);

        $letter->load(['letterTemplate', 'employee.department', 'employee.position', 'generatedBy', 'approvedBy']);

        return view('letters.generated.show', compact('letter'));
    }

    /**
     * Approve letter
     */
    public function approve(GeneratedLetter $letter)
    {
        $this->authorize('approve', $letter);

        $letter->approve(Auth::user());

        return back()->with('success', __('Letter approved successfully.'));
    }

    /**
     * Reject letter
     */
    public function reject(GeneratedLetter $letter)
    {
        $this->authorize('approve', $letter);

        $letter->reject(Auth::user());

        return back()->with('success', __('Letter rejected.'));
    }

    /**
     * Download letter as PDF
     */
    public function downloadPdf(GeneratedLetter $letter)
    {
        $this->authorize('view', $letter);

        $letter->load(['letterTemplate', 'employee.department', 'employee.position']);

        // Generate PDF
        $html = view('letters.pdf.template', compact('letter'))->render();

        $mpdf = new Mpdf([
            'mode' => 'utf-8',
            'format' => 'A4',
            'margin_left' => 15,
            'margin_right' => 15,
            'margin_top' => 16,
            'margin_bottom' => 16,
            'margin_header' => 9,
            'margin_footer' => 9
        ]);

        // Add letterhead if available
        $mpdf->SetHTMLHeader(view('letters.pdf.header')->render());
        $mpdf->SetHTMLFooter(view('letters.pdf.footer')->render());

        $mpdf->WriteHTML($html);

        $filename = 'HR-Letter-' . $letter->reference_number . '.pdf';

        return response($mpdf->Output($filename, 'S'))
            ->header('Content-Type', 'application/pdf')
            ->header('Content-Disposition', 'attachment; filename="' . $filename . '"');
    }

    /**
     * Seed default templates
     */
    public function seedTemplates()
    {
        $this->authorize('create', LetterTemplate::class);

        $this->createDefaultTemplates();

        return redirect()->route('letters.templates.index')
            ->with('success', __('Default letter templates created successfully.'));
    }

    /**
     * Helper method to process subject with variables
     */
    private function processSubject($subject, $employee, $additionalData = [])
    {
        $replacements = [
            'employee_name' => $employee->display_name,
            'employee_code' => $employee->code,
            'position' => $employee->position->name_en ?? '',
            'department' => $employee->department->name_en ?? '',
            'company_name' => 'Sarie Eldin & Partners Legal Advisors',
            'current_date' => now()->format('d/m/Y')
        ];

        $replacements = array_merge($replacements, $additionalData);

        foreach ($replacements as $variable => $value) {
            $subject = str_replace('{{' . $variable . '}}', $value, $subject);
        }

        return $subject;
    }

    /**
     * Create default letter templates
     */
    private function createDefaultTemplates()
    {
        $templates = [
            [
                'name' => 'Employment Certificate - English',
                'type' => 'employment_certificate',
                'category' => 'certificates',
                'language' => 'en',
                'subject' => 'Employment Certificate - {{employee_name}}',
                'content' => $this->getEmploymentCertificateTemplate('en'),
                'is_active' => true
            ],
            [
                'name' => 'Employment Certificate - Arabic',
                'type' => 'employment_certificate',
                'category' => 'certificates',
                'language' => 'ar',
                'subject' => 'شهادة عمل - {{employee_name}}',
                'content' => $this->getEmploymentCertificateTemplate('ar'),
                'is_active' => true
            ],
            [
                'name' => 'Salary Certificate - English',
                'type' => 'salary_certificate',
                'category' => 'certificates',
                'language' => 'en',
                'subject' => 'Salary Certificate - {{employee_name}}',
                'content' => $this->getSalaryCertificateTemplate('en'),
                'is_active' => true
            ],
            [
                'name' => 'Warning Letter - English',
                'type' => 'warning_letter',
                'category' => 'disciplinary',
                'language' => 'en',
                'subject' => 'Warning Letter - {{employee_name}}',
                'content' => $this->getWarningLetterTemplate('en'),
                'is_active' => true
            ]
        ];

        foreach ($templates as $templateData) {
            $templateData['created_by'] = Auth::id();
            $templateData['updated_by'] = Auth::id();

            LetterTemplate::updateOrCreate(
                ['name' => $templateData['name']],
                $templateData
            );
        }
    }

    private function getEmploymentCertificateTemplate($language)
    {
        if ($language === 'ar') {
            return '<div style="text-align: right; direction: rtl;">
                <h2 style="text-align: center;">شهادة عمل</h2>

                <p>تاريخ: {{current_date}}</p>

                <p>إلى من يهمه الأمر،</p>

                <p>نشهد بموجب هذه الوثيقة أن السيد/السيدة <strong>{{employee_name}}</strong> (رقم الموظف: {{employee_code}}) يعمل لدى {{company_name}} في منصب <strong>{{position}}</strong> بقسم {{department}} منذ تاريخ {{hire_date}}.</p>

                <p>الموظف المذكور يؤدي مهامه بكل أمانة ومسؤولية.</p>

                <p>أُعطيت له هذه الشهادة بناءً على طلبه دون أدنى مسؤولية على الشركة.</p>

                <div style="margin-top: 50px;">
                    <p>مع التحية،</p>
                    <p><strong>{{manager_name}}</strong><br>
                    قسم الموارد البشرية<br>
                    {{company_name}}</p>
                </div>
            </div>';
        }

        return '<div>
            <h2 style="text-align: center;">EMPLOYMENT CERTIFICATE</h2>

            <p>Date: {{current_date}}</p>

            <p>To Whom It May Concern,</p>

            <p>This is to certify that Mr./Ms. <strong>{{employee_name}}</strong> (Employee ID: {{employee_code}}) has been employed with {{company_name}} as <strong>{{position}}</strong> in the {{department}} department since {{hire_date}}.</p>

            <p>During the employment period, the above-mentioned employee has performed duties with dedication and responsibility.</p>

            <p>This certificate is issued upon the employee\'s request and without any liability on the company.</p>

            <div style="margin-top: 50px;">
                <p>Sincerely,</p>
                <p><strong>{{manager_name}}</strong><br>
                Human Resources Department<br>
                {{company_name}}</p>
            </div>
        </div>';
    }

    private function getSalaryCertificateTemplate($language)
    {
        return '<div>
            <h2 style="text-align: center;">SALARY CERTIFICATE</h2>

            <p>Date: {{current_date}}</p>

            <p>To Whom It May Concern,</p>

            <p>This is to certify that Mr./Ms. <strong>{{employee_name}}</strong> (Employee ID: {{employee_code}}) is employed with {{company_name}} as <strong>{{position}}</strong> in the {{department}} department.</p>

            <p>The current monthly salary of the above-mentioned employee is <strong>{{salary}} SAR</strong>.</p>

            <p>This certificate is issued upon the employee\'s request for official purposes.</p>

            <div style="margin-top: 50px;">
                <p>Sincerely,</p>
                <p><strong>{{manager_name}}</strong><br>
                Human Resources Department<br>
                {{company_name}}</p>
            </div>
        </div>';
    }

    private function getWarningLetterTemplate($language)
    {
        return '<div>
            <h2 style="text-align: center;">WARNING LETTER</h2>

            <p>Date: {{current_date}}</p>

            <p>Dear {{employee_name}},</p>

            <p>This letter serves as a formal warning regarding your conduct/performance in the workplace.</p>

            <p><strong>Issue:</strong> {{warning_reason}}</p>
            <p><strong>Date of Incident:</strong> {{incident_date}}</p>

            <p>This behavior is unacceptable and goes against our company policies. Immediate improvement is expected.</p>

            <p>Please be advised that failure to improve may result in further disciplinary action, up to and including termination of employment.</p>

            <p>We expect your full cooperation in resolving this matter.</p>

            <div style="margin-top: 50px;">
                <p>Sincerely,</p>
                <p><strong>{{manager_name}}</strong><br>
                Human Resources Department<br>
                {{company_name}}</p>
            </div>
        </div>';
    }
}