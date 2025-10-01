# Task: Payroll Route Guard

## Problem
Payroll and payslip routes remained publicly reachable (post-auth) even when the module is disabled, risking exposure of unfinished features during rollout.

## Approach
- Added `App\Http\Middleware\EnsurePayrollEnabled` and registered a `payroll.enabled` alias in the HTTP kernel.
- Wrapped all payroll and payslip routes in the new middleware, leveraging the previously introduced `payrollEnabled()` helper.
- Added `tests/Feature/Payroll/PayrollFeatureFlagTest.php` to assert 404 when the flag is off and JSON success when enabled (uses statistics endpoint to avoid missing views).

## Files Changed
- `app/Http/Middleware/EnsurePayrollEnabled.php`
- `app/Http/Kernel.php`
- `routes/web.php`
- `tests/Feature/Payroll/PayrollFeatureFlagTest.php`

## Tests
- Intended: `php artisan test --filter=PayrollFeatureFlagTest`
- Result: Blocked by legacy schema gap (`contracts` table missing during migrations`); no assertions executed.

## Rollback Plan
1. `git revert <commit-hash>`.
2. Manually remove `EnsurePayrollEnabled` class if reverting in pieces.
3. Remove middleware alias and route group changes.
4. Clear route and config caches: `php artisan route:clear && php artisan config:clear`.
