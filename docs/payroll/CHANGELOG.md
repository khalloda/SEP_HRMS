# Payroll Module Changelog

## 2025-10-02
- Added migration `2025_10_01_205500_add_metadata_columns_to_payslips_table.php` to snapshot salary structure IDs and localized employee metadata on payslips.
- Implemented payslip listing/detail blades with RTL-aware layouts and masking of restricted salary figures.
- Updated `PayslipController` eager loads for department/position relationships.
- Expanded feature coverage via `PayslipIndexViewTest` and `PayslipShowViewTest`.
- Verified with `php artisan test --filter=Payslip` (8 tests / 22 assertions passing).
- Implemented payroll run Excel export wired to PayrollSummaryReport via `PayrollController::exportToExcel`, with coverage in `tests/Feature/Payroll/PayrollExportTest.php` (passes `php artisan test --filter=PayrollExportTest`).

## 2025-10-02 (PM refresh)
- Surfaced cancellation details on payroll.show, reused status banners on payslip screens, added Playwright smoke test for lifecycle actions, and expanded negative authorization coverage.

## 2025-10-02 (Late PM)
- Added payroll create Blade with inline validation feedback and default period suggestions so HR can launch new runs on the guarded route.
- Expanded view coverage via `PayrollCreateViewTest` and re-ran `PayrollIndexViewTest` to confirm routing under the feature flag.

## 2025-10-02 (Late PM+1)
- Added `approval_required` column to `payroll_runs` to align schema with controller expectations.
- Introduced `PayrollStoreTest` to cover happy-path creation and overlapping period validation (passes `php artisan test --filter=PayrollStoreTest`).
\r\n## 2025-10-02 (Late PM+2)\r\n- Set the payroll create form and validation defaults to EGP and updated feature coverage to assert the selection.

## 2025-10-03
- Renamed payroll task documentation files to the unified `TASK_phase-YY-step-XX-…` naming scheme for easier sequencing.
- Added `payroll.edit` Blade view leveraging centralized currency helpers.
- Introduced feature coverage for the edit page and update endpoint currency validation.
- Added policy regression tests ensuring only authorized roles can update draft payroll runs and that locked runs remain read-only.
- Scaffolded employee salary structure views and added feature/unit coverage for view rendering and currency configuration.

## 2025-10-03 (Late AM)
- Enhanced salary structure create/edit forms with drag-and-drop component ordering, helper-driven currencies, and inline validation hints.
