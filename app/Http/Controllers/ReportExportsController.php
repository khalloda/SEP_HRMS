<?php

namespace App\Http\Controllers;

use App\Services\Reports\ReportExportService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ReportExportsController extends Controller
{
    public function __construct(protected ReportExportService $exportService)
    {
    }

    public function store(Request $request, string $report): RedirectResponse|StreamedResponse|BinaryFileResponse
    {
        abort_unless(config('reports.use_new_exports'), 404);

        $definition = $this->exportService->definition($report);
        abort_unless($definition, 404);

        $adapter = $definition['adapter'];
        $rules = $adapter::validationRules();

        $validated = Validator::make($request->all(), $rules)->validate();
        $user = Auth::user();
        $adapter::authorize($user);

        $result = $this->exportService->start(
            $report,
            $validated,
            $validated['export_format'],
            $user
        );

        if ($result instanceof BinaryFileResponse || $result instanceof StreamedResponse) {
            return $result;
        }

        return redirect()
            ->route('reports.exports.show', $result['correlation_id'])
            ->with('status', __('Your export is being prepared.'));
    }

    public function show(string $correlationId)
    {
        abort_unless(config('reports.use_new_exports'), 404);

        $state = $this->exportService->getStatus($correlationId);
        abort_unless($state, 404);

        return view('reports.export-status', [
            'state' => $this->augmentState($state, $correlationId),
            'title' => __('Report Export Status'),
        ]);
    }

    public function status(string $correlationId): JsonResponse
    {
        abort_unless(config('reports.use_new_exports'), 404);

        $state = $this->exportService->getStatus($correlationId);
        abort_unless($state, 404);

        return response()->json($this->augmentState($state, $correlationId));
    }

    public function download(string $correlationId): BinaryFileResponse|StreamedResponse
    {
        abort_unless(config('reports.use_new_exports'), 404);

        return $this->exportService->download($correlationId);
    }

    protected function augmentState(array $state, string $correlationId): array
    {
        $state['download_url'] = ($state['status'] ?? null) === ReportExportService::STATUS_COMPLETED
            ? route('reports.exports.download', $correlationId)
            : null;

        $state['report_name'] = $state['report_name']
            ?? $state['report_slug']
            ?? __('Report');

        return $state;
    }
}

