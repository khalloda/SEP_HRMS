# Task: Phase D1 Step 19 - Payroll Edit View

## Problem
The payroll edit route rendered a 500 due to the missing Blade template and still relied on hard-coded currency options, breaking parity with the create flow.

## Approach
- Added `resources/views/payroll/edit.blade.php` mirroring the create form but prefilled with run data and powered by `payrollCurrencies()`.
- Wired the controller to pass the centralized currency list and reused shared status banners for consistency.

## Files Changed
- `app/Http/Controllers/PayrollController.php`
- `resources/views/payroll/edit.blade.php`

## Tests
- `php artisan test --filter=PayrollEditViewTest`

## Rollback Plan
1. Delete `resources/views/payroll/edit.blade.php`.
2. Revert controller changes introducing the helper binding.
