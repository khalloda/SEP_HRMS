# Task: Phase D1 Step 18 - Payroll Currency Helper

## Problem
Controllers, views, and tests duplicated logic to read `config('payroll.currencies')` and handle fallbacks.

## Approach
- Introduced global helper `payrollCurrencies()` inside `AppServiceProvider` namespace bootstrap.
- Bound the helper in the service container as `payroll.currencies` for dependency injection.
- Refactored `PayrollController`, the create Blade view, and feature tests to rely on the helper.

## Files Changed
- `app/Providers/AppServiceProvider.php`
- `app/Http/Controllers/PayrollController.php`
- `resources/views/payroll/create.blade.php`
- `tests/Feature/Payroll/PayrollCreateViewTest.php`

## Tests
- `php artisan test --filter=PayrollCreateViewTest`

## Rollback Plan
1. Remove the helper from `AppServiceProvider` and container binding.
2. Restore direct config calls in controller, view, and tests.
