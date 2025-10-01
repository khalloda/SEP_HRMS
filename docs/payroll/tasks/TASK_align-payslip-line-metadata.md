# Task: Align payslip line metadata with schema

## Problem
`payslip_lines` schema lacked columns referenced by the payroll engine (`component_code`, locale-specific names, `formula_used`, `priority_order`, `include_in_gross`, `taxable`). This mismatch would break mass assignment and reporting once payroll calculations run.

## Approach
- Added migration `2025_10_01_200100_add_component_metadata_to_payslip_lines.php` to introduce the missing columns while preserving existing legacy fields.
- Updated `App\Models\PayslipLine` fillables, casts, and accessors to support both old and new columns, keeping backwards compatibility with legacy attributes (`priority`, `is_taxable`, `component_name`).
- Normalised helper methods (`component_display_name`, taxable/priority helpers) and ensured `isIncludedInGross` defaults to earnings when the new flag is absent.
- Adjusted `PayrollCalculationService` to populate both new and legacy columns when generating payslip lines.
- Added unit coverage in `tests/Unit/Payroll/PayslipLineModelTest.php` to guard accessor behaviour and column synchronisation.

## Files Changed
- `database/migrations/2025_10_01_200100_add_component_metadata_to_payslip_lines.php`
- `app/Models/PayslipLine.php`
- `app/Services/PayrollCalculationService.php`
- `tests/Unit/Payroll/PayslipLineModelTest.php`
- `docs/payroll/tasks/TASK_align-payslip-line-metadata.md`

## Tests
- `php artisan test --testsuite=Unit`

## Rollback Plan
1. `git revert <commit-hash>`.
2. Manually drop newly added columns if reverting migration only: run `php artisan migrate:rollback --path=database/migrations/2025_10_01_200100_add_component_metadata_to_payslip_lines.php`.
3. Remove `tests/Unit/Payroll/PayslipLineModelTest.php` if added manually.
4. Clear config cache if needed (`php artisan config:clear`).
