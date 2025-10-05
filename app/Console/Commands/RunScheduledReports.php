<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;
use App\Models\SavedReport;
use App\Services\ReportExportService;

class RunScheduledReports extends Command
{
    protected $signature = 'reports:run-scheduled';
    protected $description = 'Run and email scheduled saved reports';

    public function handle(): int
    {
        $due = SavedReport::whereNotNull('schedule')->get();
        foreach ($due as $rep) {
            // Simplified schedule: run daily only
            if (!in_array($rep->schedule, ['daily','weekly'])) continue;

            $toList = array_filter(array_map('trim', explode(',', (string)$rep->recipients)));
            if ($toList) {
                try {
                    $attachment = ReportExportService::generateAttachment($rep->toArray());
                    Mail::raw('Scheduled report "'.$rep->name.'" is attached. Report key: '.$rep->report_key, function($m) use ($toList, $rep, $attachment) {
                        $m->to($toList)->subject('Scheduled report: '.$rep->name);
                        if ($attachment) { $m->attach($attachment['path'], ['as'=>$attachment['filename']]); }
                    });
                } catch (\Throwable $e) {
                    $this->error('Mail send failed for report ID '.$rep->id.': '.$e->getMessage());
                }
            }
        }
        $this->info('Scheduled reports processed.');
        return self::SUCCESS;
    }
}
