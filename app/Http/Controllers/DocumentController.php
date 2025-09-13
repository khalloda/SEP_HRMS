<?php

namespace App\Http\Controllers;

use App\Models\Document;
use App\Models\Tag;
use App\Models\Employee;
use App\Models\Contract;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Http\Response;

class DocumentController extends Controller
{
    /**
     * Display a listing of documents.
     */
    public function index(Request $request)
    {
        Gate::authorize('viewAny', Document::class);

        $query = Document::with(['employee', 'contract', 'tags'])->latest();

        // Apply filters
        if ($request->filled('search')) {
            $query->search($request->get('search'));
        }

        if ($request->filled('type')) {
            $query->byType($request->get('type'));
        }

        if ($request->filled('employee_id')) {
            $query->forEmployee($request->get('employee_id'));
        }

        if ($request->filled('visibility')) {
            $query->where('visibility', $request->get('visibility'));
        }

        if ($request->filled('expired')) {
            if ($request->get('expired') === '1') {
                $query->expired();
            } elseif ($request->get('expired') === '0') {
                $query->where(function ($q) {
                    $q->whereNull('expires_at')->orWhere('expires_at', '>=', now());
                });
            }
        }

        if ($request->filled('expiring_soon')) {
            $days = (int) $request->get('expiring_days', 30);
            $query->expiringSoon($days);
        }

        // Filter documents based on user permissions
        $user = auth()->user();
        if (!$user->hasAnyRole(['HR_Admin_Manager', 'IT_Admin'])) {
            if ($user->hasRole('HR_Coordinator')) {
                // HR Coordinators cannot see payslips of others
                $query->where(function ($q) use ($user) {
                    $q->where('type', '!=', 'payslip_pdf')
                      ->orWhere('employee_id', $user->employee_id);
                });
            } elseif ($user->hasAnyRole(['Accounting_Manager', 'Accountant'])) {
                // Accounting can only see payslips and contracts
                $query->whereIn('type', ['payslip_pdf', 'contract_pdf']);
            } else {
                // Employees can only see their own documents
                $query->where('employee_id', $user->employee_id);
            }
        }

        $perPage = $request->get('per_page', 15);
        $documents = $query->paginate($perPage)->appends($request->query());

        // Get filter options
        $documentTypes = collect(Document::TYPES)->map(function ($type, $key) {
            return [
                'value' => $key,
                'label' => $type[app()->getLocale() === 'ar' ? 'name_ar' : 'name_en']
            ];
        });

        $employees = Employee::active()
            ->ordered()
            ->get(['id', 'first_name', 'last_name', 'code']);

        $tags = Tag::ordered()->get();

        return view('documents.index', compact(
            'documents', 'documentTypes', 'employees', 'tags'
        ));
    }

    /**
     * Show the form for creating a new document.
     */
    public function create(Request $request)
    {
        Gate::authorize('create', Document::class);

        $employees = Employee::active()
            ->ordered()
            ->get(['id', 'first_name', 'last_name', 'code']);

        $contracts = [];
        if ($request->filled('employee_id')) {
            $contracts = Contract::where('employee_id', $request->get('employee_id'))
                ->active()
                ->orderBy('start_date', 'desc')
                ->get(['id', 'title', 'start_date', 'end_date']);
        }

        $documentTypes = Document::TYPES;
        $tags = Tag::ordered()->get();

        return view('documents.create', compact(
            'employees', 'contracts', 'documentTypes', 'tags'
        ));
    }

    /**
     * Store a newly created document.
     */
    public function store(Request $request)
    {
        Gate::authorize('create', Document::class);

        $validated = $request->validate([
            'employee_id' => 'nullable|exists:employees,id',
            'contract_id' => 'nullable|exists:contracts,id',
            'type' => 'required|string|in:' . implode(',', array_keys(Document::TYPES)),
            'file' => 'required|file|max:10240', // 10MB max
            'visibility' => 'required|in:private,shared',
            'expires_at' => 'nullable|date|after:today',
            'watermark_note' => 'nullable|string|max:120',
            'tags' => 'nullable|array',
            'tags.*' => 'exists:tags,id',
        ]);

        if (!$validated['employee_id'] && !$validated['contract_id']) {
            return back()->withErrors(['employee_id' => __('Either employee or contract must be selected.')]);
        }

        DB::transaction(function () use ($validated, $request) {
            $file = $request->file('file');
            
            // Generate unique filename
            $filename = time() . '_' . Str::uuid() . '.' . $file->getClientOriginalExtension();
            $path = $file->storeAs('documents', $filename, 'private');

            // Calculate checksum
            $checksum = hash_file('sha256', $file->getRealPath());

            $document = Document::create([
                'employee_id' => $validated['employee_id'],
                'contract_id' => $validated['contract_id'],
                'type' => $validated['type'],
                'path' => $path,
                'original_name' => $file->getClientOriginalName(),
                'mime' => $file->getMimeType(),
                'checksum' => $checksum,
                'visibility' => $validated['visibility'],
                'expires_at' => $validated['expires_at'],
                'watermark_note' => $validated['watermark_note'],
            ]);

            // Create first version
            $document->versions()->create([
                'version_no' => 1,
                'path' => $path,
                'checksum' => $checksum,
            ]);

            // Attach tags
            if (!empty($validated['tags'])) {
                $document->tags()->sync($validated['tags']);
            }

            // Log the creation
            activity('document')
                ->performedOn($document)
                ->log('Document uploaded');
        });

        return redirect()->route('documents.index')
            ->with('success', __('hrms.document.created_successfully'));
    }

    /**
     * Display the specified document.
     */
    public function show(Document $document)
    {
        Gate::authorize('view', $document);

        if (!$document->canView()) {
            abort(403, __('You do not have permission to view this document.'));
        }

        $document->load(['employee', 'contract', 'tags', 'versions']);

        // Get recent activity
        $activities = \Spatie\Activitylog\Models\Activity::where('subject_type', \App\Models\Document::class)
            ->where('subject_id', $document->id)
            ->latest()
            ->limit(10)
            ->get();

        return view('documents.show', compact('document', 'activities'));
    }

    /**
     * Show the form for editing the specified document.
     */
    public function edit(Document $document)
    {
        Gate::authorize('update', $document);

        $employees = Employee::active()
            ->ordered()
            ->get(['id', 'first_name', 'last_name', 'code']);

        $contracts = [];
        if ($document->employee_id) {
            $contracts = Contract::where('employee_id', $document->employee_id)
                ->orderBy('start_date', 'desc')
                ->get(['id', 'title', 'start_date', 'end_date']);
        }

        $documentTypes = Document::TYPES;
        $tags = Tag::ordered()->get();

        return view('documents.edit', compact(
            'document', 'employees', 'contracts', 'documentTypes', 'tags'
        ));
    }

    /**
     * Update the specified document.
     */
    public function update(Request $request, Document $document)
    {
        Gate::authorize('update', $document);

        $validated = $request->validate([
            'employee_id' => 'nullable|exists:employees,id',
            'contract_id' => 'nullable|exists:contracts,id',
            'type' => 'required|string|in:' . implode(',', array_keys(Document::TYPES)),
            'visibility' => 'required|in:private,shared',
            'expires_at' => 'nullable|date|after:today',
            'watermark_note' => 'nullable|string|max:120',
            'tags' => 'nullable|array',
            'tags.*' => 'exists:tags,id',
        ]);

        if (!$validated['employee_id'] && !$validated['contract_id']) {
            return back()->withErrors(['employee_id' => __('Either employee or contract must be selected.')]);
        }

        DB::transaction(function () use ($validated, $document) {
            $document->update($validated);

            // Update tags
            if (isset($validated['tags'])) {
                $document->tags()->sync($validated['tags']);
            } else {
                $document->tags()->detach();
            }

            // Log the update
            activity('document')
                ->performedOn($document)
                ->log('Document updated');
        });

        return redirect()->route('documents.show', $document)
            ->with('success', __('hrms.document.updated_successfully'));
    }

    /**
     * Remove the specified document.
     */
    public function destroy(Document $document)
    {
        Gate::authorize('delete', $document);

        DB::transaction(function () use ($document) {
            // Delete all versions from storage
            foreach ($document->versions as $version) {
                if (Storage::disk('private')->exists($version->path)) {
                    Storage::disk('private')->delete($version->path);
                }
            }

            // Delete main file from storage
            if (Storage::disk('private')->exists($document->path)) {
                Storage::disk('private')->delete($document->path);
            }

            // Log the deletion
            activity('document')
                ->performedOn($document)
                ->log('Document deleted');

            $document->delete();
        });

        return redirect()->route('documents.index')
            ->with('success', __('hrms.document.deleted_successfully'));
    }

    /**
     * Download the document file.
     */
    public function download(Document $document)
    {
        Gate::authorize('download', $document);

        if (!$document->canView()) {
            abort(403, __('You do not have permission to download this document.'));
        }

        if (!Storage::disk('private')->exists($document->path)) {
            abort(404, __('Document file not found.'));
        }

        // Log the download
        activity('document')
            ->performedOn($document)
            ->log('Document downloaded');

        return Storage::disk('private')->download($document->path, $document->original_name);
    }

    /**
     * Upload a new version of the document.
     */
    public function uploadVersion(Request $request, Document $document)
    {
        Gate::authorize('update', $document);

        $validated = $request->validate([
            'file' => 'required|file|max:10240', // 10MB max
        ]);

        DB::transaction(function () use ($validated, $request, $document) {
            $file = $request->file('file');
            
            // Generate unique filename
            $filename = time() . '_' . Str::uuid() . '.' . $file->getClientOriginalExtension();
            $path = $file->storeAs('documents', $filename, 'private');

            // Calculate checksum
            $checksum = hash_file('sha256', $file->getRealPath());

            // Create new version
            $newVersionNo = $document->version_current + 1;
            $document->versions()->create([
                'version_no' => $newVersionNo,
                'path' => $path,
                'checksum' => $checksum,
            ]);

            // Update document to point to new version
            $document->update([
                'path' => $path,
                'original_name' => $file->getClientOriginalName(),
                'mime' => $file->getMimeType(),
                'checksum' => $checksum,
                'version_current' => $newVersionNo,
            ]);

            // Log the version upload
            activity('document')
                ->performedOn($document)
                ->log("Document version {$newVersionNo} uploaded");
        });

        return redirect()->route('documents.show', $document)
            ->with('success', __('hrms.document.version_uploaded_successfully'));
    }

    /**
     * Get contracts for an employee (AJAX).
     */
    public function getEmployeeContracts(Request $request)
    {
        $employeeId = $request->get('employee_id');
        
        $contracts = Contract::where('employee_id', $employeeId)
            ->orderBy('start_date', 'desc')
            ->get(['id', 'title', 'start_date', 'end_date'])
            ->map(function ($contract) {
                return [
                    'id' => $contract->id,
                    'title' => $contract->title,
                    'period' => $contract->start_date->format('Y-m-d') . ' - ' . 
                               ($contract->end_date ? $contract->end_date->format('Y-m-d') : 'Ongoing')
                ];
            });

        return response()->json($contracts);
    }
}