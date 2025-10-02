# Task: Payslip Listing & Detail Views

## Problem
Payslip routes were wired but rendered 500 errors because the corresponding Blade views were missing. HR admins could not review generated payslips, and feature tests around permissions or locale behaviours did not exist.

## Approach
- Added `resources/views/payslips/index.blade.php` with filter controls, RTL support, and masking of sensitive amounts based on `PayrollPolicy::viewNetGross`.
- Added `resources/views/payslips/show.blade.php` plus shared partial `resources/views/payslips/partials/_lines.blade.php` to present earnings/deductions/info sections and employee/payroll metadata.
- Extended `PayslipController` eager-loading so the views can rely on department/position relations.
- Wrote feature coverage (`PayslipIndexViewTest`, `PayslipShowViewTest`) that seeds minimal payroll data respecting unique constraints, salary component enums, and localized assertions.
- Ensured schema alignment via migration `2025_10_01_205500_add_metadata_columns_to_payslips_table.php` (adds salary structure + name snapshot columns) so tests and UI share the same source of truth.

## Files Changed
- `resources/views/payslips/index.blade.php`
- `resources/views/payslips/show.blade.php`
- `resources/views/payslips/partials/_lines.blade.php`
- `app/Http/Controllers/PayslipController.php`
- `tests/Feature/Payroll/PayslipIndexViewTest.php`
- `tests/Feature/Payroll/PayslipShowViewTest.php`
- `database/migrations/2025_10_01_205500_add_metadata_columns_to_payslips_table.php`

## Tests
- `php artisan test --filter=Payslip`

## Status
- Completed: 2025-10-02
- Result: All payslip feature/unit tests pass (8 tests / 22 assertions).

## Rollback Plan
1. `git revert <commit-hash>`.
2. Manually remove the new Blade views and partial.
3. Restore `PayslipController` eager loads and feature tests to their previous state.
4. Roll back the schema change if needed: `php artisan migrate:rollback --path=database/migrations/2025_10_01_205500_add_metadata_columns_to_payslips_table.php`.
