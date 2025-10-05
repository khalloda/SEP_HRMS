# Functional Gap Remediation & Hardening Plan

## Repository Baseline
- Framework: Laravel 10.x (PHP ^8.1) with Sanctum, Spatie RBAC, Spatie Activitylog, Maatwebsite Excel, MPDF.
- Data: Import `DATABASE_DUMP/sep_hrms.sql` into dedicated dev DB(s) (`.env`, `.env.testing`, `.env.playwright`).
- New composer scripts: `composer test` → `php artisan test --parallel`.
- New npm scripts: `playwright:install`, `test:e2e`, `test:e2e:headed`.
- Ensure Playwright config under `tests/playwright/` with artifacts in `tests/playwright/artifacts/`.
- Add developer onboarding guide (`docs/developer-onboarding.md`) outlining environment bootstrap (env setup, DB import, seed data).

## Theme 1 – Reports Exports (`feat/reports-exports`)
### Discovery & Design Checklist
- [ ] Catalogue unfinished export methods in `app/Http/Controllers/ReportsController.php` (~L1061+). Capture:
  - Route name + permission (e.g., `reports.department.analysis`).
  - Filters (request params), data sources, sorting.
  - Required output: Excel (XLSX), PDF.
- [ ] Review `app/Services/ReportExportService.php` & `app/Exports/ArrayExport.php`; extend to support:
  - Named DTOs or transformers per report.
  - MPDF rendering via Blade views (new `resources/views/exports/*.blade.php`).
  - Chunked query support using `cursor()`/`chunk()` to avoid memory spikes.
- [ ] Design queued export jobs (`app/Jobs/GenerateReportExport.php`) leveraging Laravel queues (Redis/Horizon friendly) for large datasets; ensure user notification on completion.
- [ ] Provide an index/catalog of current report definitions (e.g., `config/reports.php` or DB table `report_catalog`) to centralize metadata and expose in admin UI.

### Implementation Tasks
- [ ] Create export views (EN + AR headings, RTL aware) under `resources/views/exports/`.
- [ ] Implement each export handler to call `ReportExportService::generateAttachment` or queue `GenerateReportExport`:
  - Excel via `Excel::download(new ...Export, $filename)` for small datasets; queued job streams large sets to `storage/app/private/reports/`.
  - PDF via MPDF, stored on `private` disk with chunked data retrieval.
- [ ] Add/verify Spatie permissions (`reports.export.*`) in `database/seeders/RolesAndPermissionsSeeder.php` (⚠️ do **not** change seeded passwords).
- [ ] Update routes/policies to guard exports and expose catalog endpoint/page.
- [ ] Notify user (database notification + flash message) when background export is ready; include download link (signed route).

### Acceptance Criteria
- [ ] All report endpoints return downloadable file or background export status.
- [ ] Queued exports handle large datasets without timeout (jobs dispatched, Horizon-compatible).
- [ ] Files include correct columns, translations, RTL layout.
- [ ] Unauthorized roles receive HTTP 403.
- [ ] Report catalog available for admins (UI or file) showing report descriptions and export options.

### Testing
- **PHPUnit/Pest**: feature tests for export routes (mock Excel/PDF responses, `Storage::fake('private')`, queue assertions with `Bus::fake()`).
- **Playwright**:
  - Login as HR admin, trigger Excel/PDF exports (instant + queued), verify download + file content snippet.
  - Negative RBAC scenario (employee role) → 403.
  - Verify catalog page lists report metadata.

## Theme 2 – Attendance API Alignment (`fix/attendance-openapi-alignment`)
### Discovery & Design
- [ ] Compare `_CODEBASE_AUDIT_2025-09-19/apis/openapi.yaml` with validation in `app/Http/Controllers/AttendanceController.php` (~L32+).
- [ ] Document v1 legacy schema (`type` in `[in,out]`) vs v2 extended schema (`check_in`, `check_out`, `break_start`, `break_end`).
- [ ] Design compatibility layer:
  - Middleware `app/Http/Middleware/AttendanceLegacyTransformer.php` normalizing payloads.
  - Route groups: `/api/v1/attendance/*` (legacy) vs `/api/v2/attendance/*` (native).
  - Response headers indicating version used.

### Implementation Tasks
- [ ] Add middleware + register in `app/Http/Kernel.php` for `api` group.
- [ ] Duplicate routes in `routes/api.php` with namespace separation.
- [ ] Update controller validation to accept enumerations via `Rule::in()` and maintain backward compatibility.
- [ ] Extend `OpenAPI` spec with `v1`, `v2` components, examples, deprecation notes, migration guidance.
- [ ] Update docs (`docs/API_CHANGELOG.md`, README) with versioning strategy and upgrade path.

### Acceptance Criteria
- [ ] Legacy devices succeed against `/api/v1/attendance/push` and receive warning header about deprecation timeline.
- [ ] `/api/v2/attendance/push` enforces new enums and returns 422 with localized errors otherwise.
- [ ] OpenAPI renders both schemas; README documents migration steps.

### Testing
- **PHPUnit/Pest**: controller tests for both routes, verifying DB inserts + validation errors.
- **Playwright API**: POST requests covering legacy & new payloads, plus failure cases.

## Theme 3 – Payroll Expression Engine (`refactor/payroll-expression-engine`)
### Discovery & Design
- [ ] Enumerate current formula usage from DB (salary structures) and UI references (e.g., `resources/views/salary-structures/*`).
- [ ] Identify operators/functions required (min/max/round/if, unit conversions, percentage calculations).
- [ ] Introduce `App\Support\Expression\Tokenizer`, `Parser`, `Evaluator` with:
  - Allowed tokens: numbers, percentages (`12.5%`), parentheses, operators `+ - * / %`, comparisons `== != > >= < <=`, logical `&& ||`, ternary if in use.
  - Allowed functions: `min`, `max`, `round`, `ceil`, `floor`, `abs`, `percentage(value, percent)` etc. Configurable via `config/payroll.php`.
  - Variable resolver from `$calculatedValues` and attendance stats maps.
  - Strict error handling; no arbitrary PHP execution.

### Implementation Tasks
- [ ] Replace `safeEvaluate()` in `app/Services/PayrollCalculationService.php` with new engine usage.
- [ ] Add config + feature flag for fallback (default to new engine).
- [ ] Implement UI error handling pathway:
  - Catch evaluation exceptions and surface user-friendly messages on payroll run UI (e.g., “Invalid formula near token `%` – see docs/payroll-expressions.md”).
  - Log raw error with correlation ID for support.
- [ ] Update services to flag formulas that produce NaN/INF and halt gracefully.
- [ ] Provide developer docs in `docs/payroll-expressions.md` (syntax, functions, variables, troubleshooting).

### Acceptance Criteria
- [ ] No `eval` calls remain.
- [ ] Formulas from seed data evaluate identically; new engine handles percentage literals and unit conversions.
- [ ] Invalid formulas surface descriptive UI messages without 500 errors.

### Testing
- **Unit (Pest)**: parser/evaluator tests (valid cases, precedence, function usage, percentage literals, error handling).
- **Feature**: payroll run calculation verifying totals and payslip generation.
- **Playwright**: UI scenario creating payroll run & verifying totals/PDF output; intentionally break formula to confirm friendly error message.

## Theme 4 – Targeted Cache Clearing (`chore/targeted-cache-clearing`)
### Tasks
- [ ] Introduce cache tagging (`cache()->tags(['dashboard', "user:$id"])`) in `DashboardService::getDashboardAnalytics()`.
- [ ] Replace `Cache::flush()` with helper `DashboardCache::forgetForUser($id)`.
- [ ] Ensure tagging strategy is compatible with Redis or other distributed cache stores; document requirements (e.g., Redis configured with `php artisan cache:table` fallback).
- [ ] Invoke helper post analytics updates (e.g., `DashboardController::clearCache`, profile update, role change).
- [ ] Document cache strategy in `docs/architecture/cache-strategy.md`, including Horizon/queue worker considerations.

### Acceptance Criteria
- [ ] Clearing dashboard cache affects only targeted user; works across multiple nodes/queue workers.
- [ ] Documentation references Redis/Horizon deployment notes.

### Testing
- **Unit**: use `Cache::spy()` to assert tags + forget usage; simulate Redis store if possible.
- **Feature**: call dashboard endpoints before/after update ensuring regeneration occurs only for affected user.

## Theme 5 – Arabic Encoding & Translations (`i18n/arabic-encoding-and-translations`)
### Tasks
- [ ] Create `lang/ar/*.php` (or `lang/ar.json`) entries for all Arabic strings currently hard-coded (e.g., `Document::TYPES[*]['name_ar']`).
- [ ] Replace mojibake strings with proper UTF-8 text; ensure files saved UTF-8 without BOM.
- [ ] Update Blade templates to reference `@lang` helpers and set `dir="rtl"` where `app()->getLocale() === 'ar'`.
- [ ] Review CSS (`resources/css`) for RTL adjustments; add relevant utility classes if missing.
- [ ] Update export Blade views to support RTL and Arabic headings.
- [ ] Add documentation for localization in `docs/localization.md`.

### Acceptance Criteria
- [ ] Arabic labels render correctly in UI + exported documents (Excel + PDF).
- [ ] Language switch persists and adjusts layout.

### Testing
- **Playwright**: switch to Arabic via `/language/ar`, capture screenshots for dashboard/reports/employees.
- **Playwright PDF validation**: open generated PDF exports/payslips, confirm Arabic headings in RTL (compare extracted text or PDF metadata/snapshot). Store artifacts under `tests/playwright/artifacts/`.

## Theme 6 – Critical Test Suite (`tests/minimal-critical-suite`)
### Setup Tasks
- [ ] Add Playwright project (`package.json` devDeps, `playwright.config.ts`).
- [ ] Create storage state for authenticated sessions (`tests/playwright/.auth/*.json`).
- [ ] Configure CI workflow (`.github/workflows/tests.yml`) running matrix builds:
  - PHP versions (e.g., 8.1, 8.2).
  - Database engines (MySQL 8, MariaDB 10.6) using services.
  - Steps: `composer install`, `composer test`, `npm ci`, `npm run playwright:install`, `npm run test:e2e`.
  - Upload Playwright artifacts (screenshots/videos, PDF samples).

### Test Coverage
1. **RBAC**: Playwright flow verifying restricted navigation for Employee role vs HR Admin.
2. **Attendance API**: Playwright API tests hitting v1/v2 endpoints.
3. **Reports Export**: Download Excel/PDF (queued + direct), inspect meta (sheet names, header text, check Arabic headings in PDFs).
4. **Payroll UI**: create payroll run, confirm totals, download payslip (PDF parse basic text/RTL validation).
5. **PHPUnit/Pest**: attendance validation, payroll engine (unit + feature), export authorization, cache tagging behavior.

### Documentation & Follow-ups
- [ ] Update `README.md` with commands for PHP/JS tests and onboarding references.
- [ ] Update `CHANGELOG.md` per theme.
- [ ] Update `docs/Security_Follow_ups.md` with deferred tasks:
  - `DEFERRED: Rotate default seeded credentials (passwords).`
  - `DEFERRED: Review seeded roles/permissions for least-privilege alignment.`
- [ ] Maintain ⚠️ reminder: do **not** change seeded/default passwords in this pass.

---

## PR Breakdown & Branch Naming
1. `feat/reports-exports`
2. `fix/attendance-openapi-alignment`
3. `refactor/payroll-expression-engine`
4. `chore/targeted-cache-clearing`
5. `i18n/arabic-encoding-and-translations`
6. `tests/minimal-critical-suite`

Each PR includes:
- Linked checklist referencing this plan.
- Updated tests & docs.
- CHANGELOG + README updates.
- Developer onboarding doc + security follow-up updates handled in relevant PR(s).
