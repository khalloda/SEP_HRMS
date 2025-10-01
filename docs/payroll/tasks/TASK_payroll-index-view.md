# Task: Payroll Index View Scaffold

## Problem
The payroll index route returned a 500 because no Blade template existed, preventing users from browsing payroll runs even with the feature flag enabled.

## Approach
- Added `resources/views/payroll/index.blade.php` with a basic Bootstrap layout showing filters and a summary table for payroll runs.
- Used plain PHP loops for compatibility and ensured graceful handling of empty states and pagination.
- Added feature coverage (`PayrollIndexViewTest`) validating flag behaviour and basic rendering for HR admin users.

## Files Changed
- `resources/views/payroll/index.blade.php`
- `tests/Feature/Payroll/PayrollIndexViewTest.php`

## Tests
- `php artisan test --testsuite=Unit`
- `php artisan test --filter=PayrollFeatureFlagTest`
- `php artisan test --filter=PayrollDemoSeederTest`
- `php artisan test --filter=PayrollIndexViewTest`

## Rollback Plan
1. `git revert <commit-hash>`.
2. Remove `resources/views/payroll/index.blade.php` if reverting manually.
3. Delete `tests/Feature/Payroll/PayrollIndexViewTest.php`.
