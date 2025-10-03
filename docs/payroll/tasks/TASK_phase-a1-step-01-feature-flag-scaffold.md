# Task: Feature Flag Scaffold

## Problem
Payroll routes and controllers currently have no runtime guard, making partially implemented features visible. A baseline feature flag is needed before exposing additional payroll work.

## Approach
- Added `config/payroll.php` with primary `enabled` flag plus placeholders for upcoming engine/queue toggles.
- Introduced `PAYROLL_V2_ENABLED` and related env examples for quick toggling.
- Registered a reusable container binding and global helper `payrollEnabled()` in `AppServiceProvider` to centralize the check.
- Added a PHPUnit unit test suite verifying config defaults and helper behavior.

## Files Changed
- `config/payroll.php`
- `.env.example`
- `app/Providers/AppServiceProvider.php`
- `tests/Unit/Config/PayrollConfigTest.php`

## Tests
- Intended: `php artisan test --testsuite=Unit`
- Result: Blocked by existing syntax error in `config/reports.php` (addressed in Task A1.2).

## Rollback Plan
1. `git revert <commit-hash>`.
2. Remove the added env variables from `.env` files.
3. Delete `config/payroll.php` if reverting manually.
4. Re-run `php artisan config:clear` to drop cached configuration.
