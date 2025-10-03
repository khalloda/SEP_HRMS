# Task: Phase D1 Step 17 - Payroll Currency Configuration

## Problem
Payload validation, Blade defaults, and tests each hard-coded payroll currencies, making future additions inconsistent and error-prone.

## Approach
- Added a canonical `currencies` array to `config/payroll.php` with EGP first.
- Updated `PayrollController` to source form data and validation rules from the config with sensible fallbacks.
- Adjusted the create Blade view to bind dropdown options dynamically and respect the configured default.
- Extended `PayrollCreateViewTest` to assert all configured currencies render.

## Files Changed
- `config/payroll.php`
- `app/Http/Controllers/PayrollController.php`
- `resources/views/payroll/create.blade.php`
- `tests/Feature/Payroll/PayrollCreateViewTest.php`

## Tests
- `php artisan test --filter=PayrollCreateViewTest`

## Rollback Plan
1. Remove the `currencies` entry from `config/payroll.php`.
2. Restore hard-coded arrays in controller, view, and test.
