<?php

namespace App\Http\Controllers;

use App\Models\SavedReport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SavedReportController extends Controller
{
    public function index()
    {
        $this->authorize('viewAny', SavedReport::class);
        $reports = SavedReport::where('user_id', Auth::id())->orderBy('name')->get();
        return view('reports.saved.index', compact('reports'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:191',
            'report_key' => 'required|string|max:100',
            'params' => 'nullable',
            'format' => 'nullable|in:csv,xlsx,pdf',
            'schedule' => 'nullable|string|max:50',
            'recipients' => 'nullable|string',
        ]);
        $data['user_id'] = Auth::id();
        if (is_string($data['params'] ?? null)) {
            $decoded = json_decode($data['params'], true);
            if (json_last_error() === JSON_ERROR_NONE) { $data['params'] = $decoded; }
        }
        SavedReport::create($data);
        return back()->with('success', __('Report saved'));
    }

    public function destroy(SavedReport $savedReport)
    {
        abort_unless($savedReport->user_id === Auth::id(), 403);
        $savedReport->delete();
        return back()->with('success', __('Report deleted'));
    }
}

