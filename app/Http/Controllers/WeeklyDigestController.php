<?php

namespace App\Http\Controllers;

use App\Services\WeeklyDigestService;
use App\Notifications\WeeklyDigestNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Auth;

class WeeklyDigestController extends Controller
{
    protected WeeklyDigestService $digestService;

    public function __construct(WeeklyDigestService $digestService)
    {
        $this->digestService = $digestService;
    }

    /**
     * Show weekly digest settings and preview.
     */
    public function index()
    {
        $this->authorize('viewAny', \Spatie\Activitylog\Models\Activity::class);

        $preview = $this->digestService->getDigestPreview();

        return view('weekly-digest.index', compact('preview'));
    }

    /**
     * Preview weekly digest email for current user.
     */
    public function preview()
    {
        $this->authorize('viewAny', \Spatie\Activitylog\Models\Activity::class);

        $digestData = $this->digestService->generateDigestData();

        // Create notification instance to get mail preview
        $notification = new WeeklyDigestNotification($digestData);
        $mailMessage = $notification->toMail(Auth::user());

        return view('weekly-digest.preview', compact('mailMessage', 'digestData'));
    }

    /**
     * Send weekly digest manually.
     */
    public function send(Request $request)
    {
        $this->authorize('viewAny', \Spatie\Activitylog\Models\Activity::class);

        $force = $request->boolean('force', false);

        try {
            // Use Artisan command to send digest
            $exitCode = Artisan::call('hrms:send-weekly-digest', [
                '--force' => $force
            ]);

            $output = Artisan::output();

            if ($exitCode === 0) {
                return response()->json([
                    'success' => true,
                    'message' => __('Weekly digest sent successfully!'),
                    'output' => $output
                ]);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => __('Failed to send weekly digest.'),
                    'output' => $output
                ], 500);
            }
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => __('Error sending weekly digest: ') . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Test weekly digest by sending to current user only.
     */
    public function test()
    {
        $this->authorize('viewAny', \Spatie\Activitylog\Models\Activity::class);

        try {
            $digestData = $this->digestService->generateDigestData();
            $user = Auth::user();

            $user->notify(new WeeklyDigestNotification($digestData));

            return response()->json([
                'success' => true,
                'message' => __('Test digest sent to your email address!')
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => __('Error sending test digest: ') . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get weekly digest statistics API.
     */
    public function statistics()
    {
        $this->authorize('viewAny', \Spatie\Activitylog\Models\Activity::class);

        $preview = $this->digestService->getDigestPreview();

        return response()->json([
            'data' => $preview['data'],
            'summary' => $preview['summary'],
            'should_send' => $preview['should_send'],
            'recipients_count' => $preview['recipients']->count()
        ]);
    }
}