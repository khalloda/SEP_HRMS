
## 2025-10-02 Functional Readiness Review

- **Data Model**: Core tables (payroll_runs, payslips, payslip_lines) now align with model fillables; remaining gaps include missing soft deletes and lack of history tables for approvals/cancellations.
- **Calculations**: New evaluator + dependency graph in place, but attendance enrichment still synchronous and formula error surfacing limited to logs. Need retry/reporting for per-employee failures.
- **UI**: Index/show/payslip screens scaffolded; still missing bulk status dashboards, wizard for draft creation, and RTL polish for new modal. Alerts consistent via shared partials but breadcrumbs absent.
- **Exports**: Payroll Excel export implemented; PDF/CSV variants still stubbed. Payslip bulk exports rely on PayslipPdfService but lack UI hooks/testing.
- **Permissions**: Policy tightened for approvals; still role-based instead of permission-based (Spatie perms seeded but unused). Need granular gates for export/queue actions.
- **Auditing**: Activity logging fires for calc/lock/post/cancel but correlation IDs not persisted to DB. Cancellation reason captured yet not surfaced in activity feed.
- **Testing**: Feature suite now covers lifecycle happy paths; still missing negative cases (e.g., unauthorized actions) and integration coverage for queue mode + exports.
- **Operational Gaps**: No smoke automation for show page (Playwright pending). Runbook lacks updated cancellation workflow + QA checklist. Config flag defaults remain off; ensure env docs updated before enabling.

# Payroll Module Deep Analysis

## Current State Inventory

### Models
- `app/Models/PayrollRun.php`: lifecycle helpers (lock/approve/post/cancel), aggregates totals, Spatie activity logging; expects statuses and creator relationships.
- `app/Models/Payslip.php`: stores per-employee payroll details, status helpers, PDF tracking, visibility checks, activity logs; relies on relationships to employees, payroll run, salary structure, lines.
- `app/Models/PayslipLine.php`: line-item storage with component metadata; mismatch between fillable fields and migration columns (e.g. `component_name_en` vs `component_name`).
- `app/Models/SalaryStructure.php`: employee salary templates with component pivot data; formula calculations for components not implemented (returns null for formula/variable modes).
- `app/Models/SalaryComponent.php`: catalog of components; predefined Arabic labels stored with mojibake; permits calc modes incl. formula.
- Supporting models intertwined with payroll: `Employee` (current salary structure, salary visibility flag), `AttendanceSummary` (attendance data used in calculations), `Department`, `Position`.

### Services
- `app/Services/PayrollCalculationService.php`: core engine for payroll run + payslip generation, attendance enrichment, formula evaluation (`safeEvaluate` uses `eval` on sanitized string), logging.
- `app/Services/PayslipPdfService.php`: generates mPDF payslips, bulk PDFs, payroll reports (expects view `pdf.payroll-report` which is missing), handles RTL direction and storage.
- `app/Services/Reports/ReportExportService.php`: generic export pipeline powering payroll summary exports via adapters; uses queues, cache, mPDF.
- `app/Services/DashboardService.php`: surfaces payroll insights widgets and metrics to dashboard.
- `app/Services/AuditTrailService.php`: logs salary structure changes.

### Controllers
- `app/Http/Controllers/PayrollController.php`: full CRUD + actions (calculate/lock/approve/post/export) but depends on non-existent Blade views (`payroll.*`) and unimplemented export helpers.
- `app/Http/Controllers/PayslipController.php`: listing/detail/export/email flows; expects `payslips.*` views and export methods that are currently stubs; some logic assumes queue and storage for PDFs.
- `app/Http/Controllers/ReportsController.php` & `ReportExportsController` include payroll summary UI/export endpoints.

### Views
- No HTML views exist for payroll run or payslip management (`resources/views/payroll` and `resources/views/payslips` missing) despite controllers referencing them.
- PDF templates present for payslips (`resources/views/pdf/payslip*.blade.php`), but consolidated payroll report template (`pdf.payroll-report`) missing.
- Reports UI (`resources/views/reports/payroll-summary.blade.php`) provides payroll analytics export page; dashboard widgets show limited payroll data.

### Jobs & Queues
- No dedicated payroll jobs; calculations executed synchronously within controllers.
- Report exports use `app/Jobs/GenerateReportExport.php` queued on configurable connection/queue.

### Policies & Permissions (Spatie)
- `app/Policies/PayrollPolicy.php` governs payroll runs and payslips; relies on role checks rather than granular Spatie permissions.
- `database/seeders/RolesAndPermissionsSeeder.php` only seeds `payroll.view` / `payroll.manage`, not referenced by policy methods; separation-of-duties (approve vs calculate) hard-coded by role.
- Auth provider maps `Payslip` to `PayrollPolicy`, so same rules apply.

### Config
- `config/reports.php` defines report adapters; syntax issues present (escaped `\n`, misplaced brackets) likely from bad merge; includes payroll summary definition but structure broken.
- No dedicated `config/payroll.php`; currencies enumerated in controller differ from migration defaults (`USD/EUR/EGP` vs `SAR`).

### Seeds
- No payroll-specific seeders; demo payroll data absent.
- Roles/permissions seeder lacks payroll manage granularity (no export/calc specific permissions).

### Migrations
- `database/migrations/2025_09_13_113922_create_payroll_runs_table.php`: defines payroll run schema, totals, status, JSON summary.
- `2025_09_13_113953_create_payslips_table.php`: stores payslip header data, status, PDF metadata.
- `2025_09_13_114025_create_payslip_lines_table.php`: line items with limited fields; does not align with model fillables (no `component_code`, etc.).

### Exports (Excel/PDF)
- Payslip PDF generation via `PayslipPdfService` (single, bulk, tax certificate) uses mPDF, supports RTL via locale check but lacks consolidated payroll template.
- PayrollController export stubs (Excel/PDF/CSV) not implemented; PayslipController export helpers also missing.
- Report export pipeline supports payroll summary Excel/PDF via adapter; Playwright test downloads Excel artifact.

### Routes
- `routes/web.php` registers resource routes for payroll runs and payslips, action routes (calculate/lock/etc.), bulk PDF/email endpoints, payroll stats/export endpoints.
- Routes rely on middleware stack from `RouteServiceProvider` (auth + verified); no dedicated feature flags.

## Formula Engine Assessment
- `PayrollCalculationService::safeEvaluate()` strips characters then uses `eval("return $expression;")`; mitigates some injection but still risky (no whitelist for functions, fails on division-by-zero, no error context).
- Supported operators: basic arithmetic `+ - * /`, parentheses, decimals; no power/modulo functions.
- Variable substitution: replaces component codes (raw string replace), attendance metrics (WORK_DAYS, etc.), and common tokens `GROSS`, `NET` (NET hard-coded to 0). No safeguards for overlapping codes or negative values.
- No support for conditional logic, min/max, rounding, or referencing previous components safely; order of evaluation depends on iteration order.
- Audit/logging for failed formulas logs message but omits correlation IDs; users not notified of invalid formulas beyond generic message.

## Data Dependencies & Persistence
- Employees: payroll engine pulls `Employee::active()` with `currentSalaryStructure`; payslip stores code/name/department at generation time, but employee changes post-run not tracked.
- Salary Structures & Components: pivot fields `formula_expr`, `value_numeric` used; formula-based components currently calculated via eval; pivot data schema inconsistent with models.
- Attendance: `AttendanceSummary::forEmployee()->dateRange()` used to enrich payslips; assumes summaries exist for period; absence defaults to zeros.
- Leaves/Allowances/Deductions: no integration with leave balances or manual adjustments; `PayslipLine` generation limited to salary structure components; ad-hoc allowances/deductions not handled.
- Persistence: totals stored on `payroll_runs` (`total_gross`, etc.), `payslips` hold per-employee totals, `payslip_lines` detail components. No snapshot of calculation summary saved aside from optional JSON column.

## UI / UX Gaps
- Payroll index/create/show/payslip views referenced but missing -> users hit 500 errors; no wizard for run lifecycle.
- No payslip listing/detail Blade files -> employees/HR cannot view payslips in UI despite controller logic.
- Workflow gaps: calculate/lock/approve/post flows require clear status banners, but no UI; approvals lack audit comments; no re-run UI for failed employees.
- Dashboard cards show limited insights, but navigation includes payroll dropdown linking to missing pages.
- No UI for bulk PDF/email operations despite routes.

## Exports & RTL/Arabic Readiness
- Payslip PDF uses dejavu font and direction flag; header/footer templates exist, Arabic labels rely on corrupted strings from `SalaryComponent` seed data.
- Consolidated payroll PDF template absent (`pdf.payroll-report`), so `generatePayrollReportPdf` will throw view not found.
- Excel/CSV exports for payroll runs/payslips unimplemented; controllers still return placeholders.
- Report export adapter supports Excel/PDF output but PDF template path contains escaped `\n` in config, likely breaking PDF export for payroll summary.
- No validation that Arabic locale triggers RTL content; front-end lacks locale toggle for payroll views.

## Auditing, Logging, Error Handling, Performance
- Spatie activity logging enabled on PayrollRun/Payslip, but actions in controller (calculate/lock) rely on model methods to emit logs.
- No correlation IDs or structured logs in payroll calculations; errors logged via `Log::error` but without request/run context beyond IDs.
- Exception handling: calculation wraps DB transaction but swallows per-employee errors, accumulates messages; fatal error resets status to draft.
- Export jobs log start/completion with correlation IDs in cache state but not included in UI.
- Performance: payroll calculation loops employees sequentially, eager loads limited relations; no chunking or queue; attendance queries executed per payslip.
- Bulk PDF generation processes sequentially in memory; risk for large payrolls, no queue/streaming.
- No caching of salary structures or precomputed components; repeated lookups for each component.

## Test Coverage Map
- PHPUnit: single feature test `tests/Feature/ReportExportsTest.php` covers report export queuing/completion (including payroll summary). No unit tests for payroll calculations, permissions, payslip visibility, formula evaluation, exports.
- Playwright: `tests/Playwright/report-export.spec.ts` validates payroll summary export UI + download; no tests for payroll run management, payslip UI, Arabic/RTL rendering, or PDF outputs.
- No tests for payroll policies/authorization, status transitions, bulk operations, or error handling.

