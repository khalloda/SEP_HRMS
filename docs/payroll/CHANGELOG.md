# Payroll Module Changelog

## 2025-10-02
- Added migration `2025_10_01_205500_add_metadata_columns_to_payslips_table.php` to snapshot salary structure IDs and localized employee metadata on payslips.
- Implemented payslip listing/detail blades with RTL-aware layouts and masking of restricted salary figures.
- Updated `PayslipController` eager loads for department/position relationships.
- Expanded feature coverage via `PayslipIndexViewTest` and `PayslipShowViewTest`.
- Verified with `php artisan test --filter=Payslip` (8 tests / 22 assertions passing).
- Implemented payroll run Excel export wired to PayrollSummaryReport via PayrollController::exportToExcel, with coverage in 	ests/Feature/Payroll/PayrollExportTest.php (passes php artisan test --filter=PayrollExportTest).
