# Reports Export Foundation

This document outlines the initial infrastructure for the new reports export pipeline.

## Highlights

- `config/reports.php` introduces a feature flag (`REPORTS_USE_NEW_EXPORTS`) and report catalog definitions.
- `App\Services\Reports\ReportExportService` handles queuing, status tracking, and file generation (Excel/PDF) for the pilot Employee Directory report.
- `GenerateReportExport` job executes the export asynchronously using the configured queue connection.
- `ReportExportsController` exposes `/reports/exports/*` routes for starting exports, polling status, and downloading results.
- A status view (`resources/views/reports/export-status.blade.php`) provides progress updates via polling.
- Playwright scaffolding (`tests/playwright/report-export.spec.ts`) is added for end-to-end coverage.
- Laravel feature tests ensure queue dispatching and completion logic work as expected.

## Next Steps

- Integrate additional reports incrementally, reusing the service and adapter structure.
- Add more granular telemetry and UI polish as subsequent PRs migrate legacy exports.
- Extend Playwright suites once the UI toggles are wired to the feature flag.


\n\n## Paused\n\n- Attendance Summary migration is pending because the attendance aggregation feature is not complete; the report still uses the legacy synchronous export.
