# SEP HRMS Codebase Analysis (2025-09-30)

## Repository Snapshot
- Location: D:\Claude\SEP_HRMS
- Framework: Laravel 10 + PHP 8.4
- Key packages: sanctum, spatie/laravel-permission, spatie/laravel-activitylog, maatwebsite/excel, mpdf/mpdf, laravel/ui
- Data artifacts: DATABASE_DUMP\sep_hrms.sql (current dump), legacy sql snapshots in repo root

## Architecture Summary
### Backend Layers
- Modular controllers backed by rich Eloquent models and service classes (e.g. DashboardService, PayrollCalculationService, WeeklyDigestService).
- Sanctum API tokens and Spatie RBAC for authn/authz; policies registered for employees, contracts, payroll, audit logs.
- Activity logging embedded across critical models (employees, contracts, payroll, documents) for compliance traceability.
- Storage strategy uses local private disk for sensitive PDFs and documents.

### Domain Modules
- Employee lifecycle management with advanced filtering/search, stats, and audit logging (`app/Http/Controllers/EmployeeController.php`, `app/Models/Employee.php`).
- Contract lifecycle workflow including review/approval/sign and expiry monitoring with notifications (`app/Http/Controllers/ContractController.php`, `app/Console/Commands/SendContractExpiryNotifications.php`).
- Document management with versioning, tagging, watermark logic, and expiry analytics (`app/Models/Document.php`).
- Payroll engine linking salary structures, attendance summaries, payslip PDFs, and payroll run approvals (`app/Services/PayrollCalculationService.php`, `app/Models/PayrollRun.php`, `app/Services/PayslipPdfService.php`).
- Attendance integration accepts ZKTeco pushes with HMAC + API key checks, batch calculations, anomaly detection, and manual adjustments (`routes/api.php`, `app/Http/Controllers/AttendanceController.php`).
- HR letters module supports template CRUD, generation, approval, and PDF export (`app/Http/Controllers/LetterController.php`).
- Reporting stack with Excel/PDF exports, scheduled report emailing, and saved report definitions (`app/Http/Controllers/ReportsController.php`, `app/Services/ReportExportService.php`, `app/Console/Commands/RunScheduledReports.php`).
- Employee portal dashboards, profile updates, documents, payslip access, and attendance snapshots (`app/Http/Controllers/EmployeePortalController.php`).
- Weekly digest service collates expiring contracts/documents, birthdays, hires, and activity summaries, with artisan command and notifications (`app/Services/WeeklyDigestService.php`, `app/Console/Commands/SendWeeklyDigest.php`).

### Operations & Infrastructure
- Artisan scheduler triggers contract notifications, weekly digest, and scheduled reports (`app/Console/Kernel.php`).
- `app:bootstrap` command runs migrations and optional RBAC seeding; `hrms:create-missing-tables` backfills Spatie tables if migrations missing.
- Localization middleware + `/language/{locale}` route toggle English/Arabic with RTL layout and Cairo font (`app/Http/Middleware/SetLocale.php`, `routes/web.php`).
- Frontend UI built with Blade, Bootstrap 5.3, Font Awesome 6, and custom Sarie Eldin theme (`resources/views/layouts/app.blade.php`).

## Key Findings & Risks
1. **Report exports unfinished**: Several export helper methods in `ReportsController` return placeholder JSON responses (e.g. `exportDepartmentAnalysis`, `exportSalaryAnalysis`). Users selecting these report exports receive no file output.
2. **Formula evaluation uses eval**: `PayrollCalculationService::safeEvaluate` sanitizes expressions then calls `eval`. Malicious or malformed formulas could still execute unexpected code; a safe expression parser should replace eval.
3. **Permission gap for salary visibility**: `User::canViewNetGross` checks for a `payroll.view_net` permission that is never seeded, relying implicitly on hard-coded role names. Introduce the permission or adjust logic to avoid silent misconfiguration.
4. **Weak seeded credentials**: `DefaultUsersSeeder` seeds multiple high-privilege accounts with `password123`. This is acceptable only for local setup; production seeding must randomize or prompt for secure passwords.
5. **Attendance API spec mismatch**: OpenAPI definition restricts `type` to `[in, out]` while the controller validation expects `check_in`, `check_out`, `break_start`, `break_end`. Devices adhering to the spec will fail validation; update spec or controller.
6. **Dashboard cache flush too broad**: `DashboardService::clearUserCache` calls `Cache::flush()`, clearing the entire cache store instead of the user-specific keys. This can evict unrelated cached data.
7. **Encoding issues in console output**: Several artisan commands embed non-ASCII glyphs (likely copied icons/emojis). On some terminals these render as mojibake (e.g. `SendWeeklyDigest`, `CreateMissingTables`). Replace with ASCII for clarity.
8. **Arabic labels corrupted**: Arabic strings in `Document::TYPES` appear garbled, suggesting encoding or copy-paste issues; fix to ensure proper RTL display.
9. **Spatie medialibrary unused**: Package is installed but no model uses `InteractsWithMedia`; consider removing dependency or wiring up media features.
10. **Test coverage minimal**: Only Laravel stub tests exist (`tests/Feature/ExampleTest.php`). No automated coverage for payroll, attendance, RBAC, or reporting flows.

## Recommended Next Actions
1. Implement the pending report export methods and ensure they integrate with `ReportExportService`.
2. Swap `eval`-based formula handling with a vetted math expression parser or custom evaluator.
3. Define and seed explicit permissions for salary visibility or refactor visibility checks around roles.
4. Harden seeders for production (unique secure passwords, optional prompts, or disabled by default).
5. Align the OpenAPI attendance schema with controller expectations (update spec to `check_in` etc., or relax validation).
6. Make dashboard cache clearing target per-user cache keys rather than flushing the store.
7. Replace non-ASCII glyphs in artisan command output and ensure console messaging is ASCII-safe.
8. Correct Arabic translation strings and centralize them under language files for consistency.
9. Remove unused `spatie/laravel-medialibrary` or integrate it where document media handling is required.
10. Establish feature and integration tests for payroll calculations, attendance API, RBAC-sensitive operations, and critical workflows.
