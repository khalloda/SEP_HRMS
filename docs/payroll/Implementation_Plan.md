# Payroll Implementation Plan

## Planning Principles
- Deliver in guarded increments behind new feature flags (`config/payroll.php` + `PAYROLL_V2_ENABLED` env) so production can toggle.
- Keep tasks ?2 files when possible; sequence to unblock UI quickly while maintaining calc safety.
- Each checkpoint should leave system in deployable state with flags default-off until validated.

## Epic A: Platform Readiness & Safety Nets
### Story A1: Feature Flag & Config Hygiene
- **Task A1.1**
  - Purpose: Introduce payroll module config + feature flag scaffold controlling routes/controllers/views.
  - Files: `config/payroll.php`, `.env.example`, `app/Providers/AppServiceProvider.php` (bind config helper).
  - Acceptance: Flag defaults false; helper `payrollEnabled()` returns bool; no runtime errors.
  - Tests: PHPUnit config test (new `tests/Unit/Config/PayrollConfigTest.php`).
  - Rollback: `git revert <commit>`; remove config file; delete test.
- **Task A1.2**
  - Purpose: Repair `config/reports.php` syntax so payroll summary definition loads.
  - Files: `config/reports.php` only.
  - Acceptance: `config('reports.definitions.payroll-summary')` returns array; `php artisan config:cache` succeeds.
  - Tests: Extend PHPUnit config test verifying structure.
  - Rollback: `git revert`; re-run `php artisan config:clear`.

### Story A2: Routing Guards
- **Task A2.1**
  - Purpose: Wrap payroll routes/controllers behind flag + middleware check.
  - Files: `routes/web.php`, `app/Http/Middleware/EnsurePayrollEnabled.php`.
  - Acceptance: When flag false, payroll routes 404 with friendly message; when true, accessible.
  - Tests: Feature test toggling flag hitting `/payroll`.
  - Rollback: `git revert` + remove middleware class + artisan route:clear.

## Epic B: Data Model Alignment
### Story B1: Schema & Model Sync
- **Task B1.1**
  - Purpose: Align `PayslipLine` model fillables with migration fields.
  - Files: `app/Models/PayslipLine.php`.
  - Acceptance: Fillables reflect actual columns; no mass-assignment errors during calc test.
  - Tests: New unit test ensuring mass assignment works.
  - Rollback: revert file.
- **Task B1.2**
  - Purpose: Adjust migrations or create follow-up migration to add missing columns (component_code, names) for historical data.
  - Files: `database/migrations/2025_09_13_114025_create_payslip_lines_table.php` (if safe) or new migration.
  - Acceptance: Schema matches model; migrate:fresh succeeds.
  - Tests: Schema assertion in migration test.
  - Rollback: revert migration; run `php artisan migrate:rollback`.

### Story B2: Seed/Defaults Fixes
- **Task B2.1**
  - Purpose: Fix corrupted Arabic strings + add payroll sample data seeder behind flag.
  - Files: `app/Models/SalaryComponent.php`, `database/seeders/PayrollDemoSeeder.php`.
  - Acceptance: Strings corrected; demo seeder optional via artisan command.
  - Tests: Seeder test verifying inserted components.
  - Rollback: revert files; `php artisan db:seed --class=PayrollDemoSeeder --rollback` (manual).

## Epic C: Calculation Engine Hardening
### Story C1: Deterministic Formula Engine
- **Task C1.1**
  - Purpose: Replace `eval` with safe expression parser (e.g., league/math-expr) behind flag.
  - Files: `composer.json`, `app/Services/Payroll/ExpressionEvaluator.php`, `app/Services/PayrollCalculationService.php`.
  - Acceptance: Calculations use new evaluator when flag on; fallback to old when off.
  - Tests: Unit tests covering operators, invalid input; regression test for basic formulas.
  - Rollback: `composer remove`, revert service; `composer dump-autoload`.
- **Task C1.2**
  - Purpose: Implement component dependency graph + detection for circular references.
  - Files: `app/Services/PayrollCalculationService.php` (small additions), `tests/Unit/Payroll/FormulaGraphTest.php`.
  - Acceptance: Circular formulas throw descriptive error; logged with correlation ID.
  - Tests: New unit tests.
  - Rollback: revert file + delete test.

### Story C2: Performance & Observability
- **Task C2.1**
  - Purpose: Introduce chunked employee processing + queueable job option (feature flag default off).
  - Files: `app/Services/PayrollCalculationService.php`, `app/Jobs/ProcessPayrollRun.php`.
  - Acceptance: Config toggle enables job dispatch; logs include correlation ID per run.
  - Tests: Feature test simulating queue path (sync driver); log assertion.
  - Rollback: revert files; clear queue.

## Epic D: UI & UX Delivery
### Story D1: Payroll Run Screens
- **Task D1.1**
  - Purpose: Scaffold `payroll.index` Blade with summary table (read-only) using Tailwind/Bootstrap.
  - Files: `resources/views/payroll/index.blade.php`, `app/Http/Controllers/PayrollController.php` (compact adjustments).
  - Acceptance: Page renders under flag; pagination works.
  - Tests: Laravel feature test asserting view, Playwright smoke nav.
  - Rollback: revert files.
- **Task D1.2**
  - Purpose: Add `payroll.show` with lifecycle actions (buttons disabled based on policy).
  - Files: `resources/views/payroll/show.blade.php`, `resources/views/payroll/partials/_actions.blade.php`.
  - Acceptance: Buttons respect policy; flash messages appear.
  - Tests: Feature test verifying button visibility; Playwright action stub.
  - Rollback: revert new views.

### Story D2: Payslip Management
- **Task D2.1**
  - Purpose: Create `payslips.index` listing with department filter + net visibility handling.
  - Files: `resources/views/payslips/index.blade.php`, `app/Http/Controllers/PayslipController.php` (view data tweaks).
  - Acceptance: HR sees net amounts; restricted roles get masked values.
  - Tests: Feature test for policy gate; Playwright coverage with HR + coordinator accounts.
  - Rollback: revert files.
- **Task D2.2**
  - Purpose: Implement `payslips.show` with earnings/deductions tables and RTL-ready headings.
  - Files: `resources/views/payslips/show.blade.php`, `resources/views/payslips/partials/_lines.blade.php`.
  - Acceptance: Arabic locale displays RTL layout; totals accurate.
  - Tests: Feature test toggling locale; snapshot of HTML; Playwright visual diff.
  - Rollback: revert views.

## Epic E: Export & Document Automation
### Story E1: Controller Export Implementations
- **Task E1.1**
  - Purpose: Implement PayrollController Excel export using `PayrollSummaryReport` adapter.
  - Files: `app/Http/Controllers/PayrollController.php`, `app/Exports/ArrayExport.php` (helper updates).
  - Acceptance: Export route returns file; respects permissions.
  - Tests: Feature test hitting route (faking storage), ensures file saved; Playwright download scenario.
  - Rollback: revert files; delete generated artifacts.
- **Task E1.2**
  - Purpose: Implement PayslipController PDF/CSV exports leveraging `PayslipPdfService` + new CSV helper.
  - Files: `app/Http/Controllers/PayslipController.php`, `app/Exports/PayslipCsvExport.php`.
  - Acceptance: Bulk export works for selected run; CSV columns validated.
  - Tests: Feature test with fake storage; Playwright verifying download link.
  - Rollback: revert files; clear storage.

### Story E2: PDF Templates & RTL
- **Task E2.1**
  - Purpose: Add missing `pdf.payroll-report` template with bilingual support.
  - Files: `resources/views/pdf/payroll-report.blade.php`.
  - Acceptance: `generatePayrollReportPdf` succeeds; totals correct; passes RTL check.
  - Tests: Unit test rendering view, asserting Arabic headings when locale=ar.
  - Rollback: delete template.
- **Task E2.2**
  - Purpose: Ensure payslip PDF header/footer use localized labels sourced from lang files.
  - Files: `resources/views/pdf/payslip*.blade.php`, new `lang/en/payroll.php`, `lang/ar/payroll.php`.
  - Acceptance: PDFs render without mojibake; translations loaded.
  - Tests: View test verifying translation strings; Playwright PDF smoke (via API or snapshot).
  - Rollback: revert files.

## Epic F: Permissions, Auditing, & Error Handling
### Story F1: Permission Alignment
- **Task F1.1**
  - Purpose: Map Spatie permissions (`payroll.view`, `payroll.manage`, `payroll.export`) to policy checks.
  - Files: `app/Policies/PayrollPolicy.php`.
  - Acceptance: Policy methods use `$user->can(...)`; roles seeded accordingly.
  - Tests: Policy test matrix covering roles/permissions.
  - Rollback: revert file.
- **Task F1.2**
  - Purpose: Update `RolesAndPermissionsSeeder` to seed new permissions + adjustments.
  - Files: `database/seeders/RolesAndPermissionsSeeder.php`.
  - Acceptance: Seeder assigns least-privilege; docs updated.
  - Tests: Seeder test verifying assignments; artisan `db:seed` dry run.
  - Rollback: revert seeder; re-seed.

### Story F2: Observability Enhancements
- **Task F2.1**
  - Purpose: Introduce correlation ID for payroll run operations (calc/exports) propagated to logs.
  - Files: `app/Services/PayrollCalculationService.php`, `app/Http/Controllers/PayrollController.php` (set request attribute), `config/logging.php` (context helper optional).
  - Acceptance: Logs include `payroll_run_id` + `correlation_id`; errors bubble to UI with reference.
  - Tests: Unit test capturing logs via `Log::spy()`.
  - Rollback: revert files.

## Epic G: Automated Testing & QA
### Story G1: PHPUnit Coverage
- **Task G1.1**
  - Purpose: Add unit tests for payroll calculation scenarios (fixed, formula, attendance adjustments).
  - Files: `tests/Feature/Payroll/PayrollCalculationTest.php`, fixtures under `database/factories` if needed.
  - Acceptance: Tests cover success + failure; pass with new engine.
  - Tests: Already defined.
  - Rollback: delete tests.
- **Task G1.2**
  - Purpose: Add feature tests for permissions (view net vs masked) and lifecycle transitions.
  - Files: `tests/Feature/Payroll/PayrollPolicyTest.php`, `tests/Feature/Payroll/PayrollLifecycleTest.php`.
  - Acceptance: All new tests pass; guard regressions.
  - Rollback: delete tests.

### Story G2: Playwright Coverage
- **Task G2.1**
  - Purpose: Extend Playwright spec for payroll run overview + payslip detail flows (EN + AR locales).
  - Files: `tests/Playwright/payroll-run.spec.ts`, update config to seed feature flag.
  - Acceptance: Test navigates to index/show/payslip detail; asserts translation toggles.
  - Rollback: delete spec, revert config.
- **Task G2.2**
  - Purpose: Add visual regression for PDF downloads (baseline images/byte checks).
  - Files: `tests/Playwright/payroll-pdf.spec.ts`, `tests/Playwright/artifacts/.gitignore` update.
  - Acceptance: Test verifies PDF generated and contains Arabic headings (text assertions).
  - Rollback: remove spec.

## Dependencies & Checkpoints
1. **Flag & Config (Epic A)** unlocks safe iteration (must precede others).
2. **Data Model Alignment (Epic B)** required before formula fixes & UI to avoid schema mismatches.
3. **Calculation Hardening (Epic C)** depends on B1 tasks; observer/performance improvements rely on new evaluator.
4. **UI Delivery (Epic D)** can start once flag + base schema in place; show view depends on exports optionally but mostly independent.
5. **Export Automation (Epic E)** requires C1 (accurate data) and D1 (UI triggers) for holistic value.
6. **Permissions & Observability (Epic F)** overlays across features; F1 should precede D/E go-live; F2 after engine to avoid churn.
7. **Testing (Epic G)** follows each corresponding epic to lock regression before progressing.

Each epic concludes with a deployable checkpoint:
- Checkpoint 1: Feature flag + safe routes (A completed).
- Checkpoint 2: Schema/model alignment with demo data (B completed).
- Checkpoint 3: New evaluator + queued calc optional (C completed) enabling limited beta.
- Checkpoint 4: UI/UX basic flows (D completed) with flag on in staging.
- Checkpoint 5: Export suite + PDF parity (E completed).
- Checkpoint 6: Permissions/logging hardened (F completed).
- Checkpoint 7: Test suite expansion (G completed) prior to flag rollout.

