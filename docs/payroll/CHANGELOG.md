# Payroll Module Changelog

## 2025-10-02
- Added migration `2025_10_01_205500_add_metadata_columns_to_payslips_table.php` to snapshot salary structure IDs and localized employee metadata on payslips.
- Implemented payslip listing/detail blades with RTL-aware layouts and masking of restricted salary figures.
- Updated `PayslipController` eager loads for department/position relationships.
- Expanded feature coverage via `PayslipIndexViewTest` and `PayslipShowViewTest`.
- Verified with `php artisan test --filter=Payslip` (8 tests / 22 assertions passing).
- Implemented payroll run Excel export wired to PayrollSummaryReport via PayrollController::exportToExcel, with coverage in 	ests/Feature/Payroll/PayrollExportTest.php (passes php artisan test --filter=PayrollExportTest).

## 2025-10-02 (PM refresh)
- Surfaced cancellation details on payroll.show, reused status banners on payslip screens, added Playwright smoke test for lifecycle actions, and expanded negative authorization coverage.
\r\n## 2025-10-02 (Late PM)\r\n- Added payroll create Blade with inline validation feedback and default period suggestions so HR can launch new runs on the guarded route.\r\n- Verified route scaffolding by running php artisan test --filter=PayrollIndexViewTest.
