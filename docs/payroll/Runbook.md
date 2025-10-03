# Payroll Module Runbook

## Local Setup & Data Seeding
- Ensure `.env` has valid DB + queue settings (`QUEUE_CONNECTION=database` recommended). Run `php artisan migrate --seed` (includes roles/permissions). Optional payroll demo data: `php artisan db:seed --class=PayrollDemoSeeder` (only available after Epic B2.1).
- Set locale for RTL validation via `APP_LOCALE=ar` (temporarily) or run `app()->setLocale('ar')` within tinker when testing PDFs.
- Storage prep: run `php artisan storage:link` and ensure `storage/app/private` exists for PDF/export artifacts.
- For queued operations, start worker: `php artisan queue:work --queue=reports,payroll --tries=3` (use `--queue=payroll` after Epic C2.1 introduces job).

## Feature Flags & Configuration
- Primary toggle: `PAYROLL_V2_ENABLED` in `.env` (mirrored in `config/payroll.php`). Set to `true` for staging once Epic A completes. Clear config cache after change (`php artisan config:clear`).
- Calculation engine toggle (post Epic C1): `PAYROLL_EXPRESSION_ENGINE=new` to enable parser; fallback `legacy` retains eval behavior.
- Queue mode toggle (post Epic C2): `PAYROLL_CALC_QUEUE=true` dispatches `ProcessPayrollRun` job instead of synchronous processing.
- PDF/Export settings: `config/reports.php` defines adapters; adjust `REPORTS_USE_NEW_EXPORTS` (true for new pipeline). Payroll-specific template options added in `config/payroll.php` as epics land.

## Running Tests & Viewing Artifacts
- PHPUnit/Pest: `php artisan test --testsuite=Unit` or `php artisan test --filter=Payroll` after adding suites. Code coverage optional with `php -d xdebug.mode=coverage vendor/bin/phpunit` (ensure Xdebug enabled).
- Playwright: `npx playwright test --project=chromium --grep="@payroll"` (Run from project root; ensure `PAYROLL_V2_ENABLED=true` in `.env.testing`). Artifacts (screenshots, downloads, traces) stored under `tests/Playwright/artifacts/`.
- PDF smoke: After generating via UI/API, files saved to `storage/app/private/payslips` or `storage/app/private/reports`. Use `php artisan payroll:generate-report --run=<id>` (to be added) or call service in tinker.
- Log review: tail `storage/logs/laravel.log` filtering on `correlation_id` once observability tasks implemented.

- **Cancellation Flow (Post Step 07)**: Show view exposes a modal requiring a cancellation reason before POSTing to `/payroll/{id}/cancel`; QA should verify the textarea is mandatory, flash messages return to the show page, and activity logs capture the reason. Include this in pre-release checklists.
- **QA Checklist Update**: Add Playwright smoke `npx playwright test --grep "Payroll Run Show Lifecycles"` to the regression suite when the feature flag is enabled.
