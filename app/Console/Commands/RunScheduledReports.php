<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;
use App\Models\SavedReport;

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

            // TODO: generate file based on $rep->report_key and $rep->params
            // For now, send a placeholder email if configured
            $toList = array_filter(array_map('trim', explode(',', (string)$rep->recipients)));
            if ($toList) {
                try {
                    Mail::raw('Scheduled report "'.$rep->name.'" is ready. Report key: '.$rep->report_key, function($m) use ($toList, $rep) {
                        $m->to($toList)->subject('Scheduled report: '.$rep->name);
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

