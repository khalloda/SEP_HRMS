# Task: Salary Component Localization & Demo Seeder

## Problem
Salary component seed data contained mojibake Arabic labels, and there was no convenient way to load demonstration payroll data for review environments.

## Approach
- Replaced corrupted Arabic strings in `SalaryComponent::getPredefinedComponents()` with properly encoded translations.
- Added `PayrollDemoSeeder` that seeds a sample payroll run (only when the payroll module is enabled) and ensures a creator user exists.
- Created feature coverage verifying the demo seeder skips when disabled and produces a sample payroll run when enabled.

## Files Changed
- `app/Models/SalaryComponent.php`
- `database/seeders/PayrollDemoSeeder.php`
- `tests/Feature/Payroll/PayrollDemoSeederTest.php`

## Tests
- `php artisan test --filter=PayrollDemoSeederTest`
- `php artisan test --filter=PayrollFeatureFlagTest`
- `php artisan test --testsuite=Unit`

## Rollback Plan
1. `git revert <commit-hash>`.
2. Remove `database/seeders/PayrollDemoSeeder.php` if reverting manually.
3. Restore the previous `SalaryComponent::getPredefinedComponents()` definitions.
4. Delete `tests/Feature/Payroll/PayrollDemoSeederTest.php` if reverting manually.
