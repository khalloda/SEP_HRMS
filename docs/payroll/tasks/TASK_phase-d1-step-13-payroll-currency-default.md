# Task: Payroll Currency Default Alignment

## Problem
The payroll create form defaulted to USD even though the module standardises on EGP, so new runs and automated tests risked diverging from production expectations.

## Approach
- Updated the currency dropdown to default to EGP and reordered the options for clarity.
- Adjusted controller validation to prioritise EGP in the allowed list and refreshed feature specs to assert the selected option.
- Updated the store feature test to submit runs in EGP, keeping coverage consistent with the new default.

## Files Changed
- `resources/views/payroll/create.blade.php`
- `app/Http/Controllers/PayrollController.php`
- `tests/Feature/Payroll/PayrollCreateViewTest.php`
- `tests/Feature/Payroll/PayrollStoreTest.php`

## Tests
- `php artisan test --filter=PayrollCreateViewTest`
- `php artisan test --filter=PayrollStoreTest`

## Rollback Plan
1. Restore the currency dropdown default to USD and revert validation/test updates.
