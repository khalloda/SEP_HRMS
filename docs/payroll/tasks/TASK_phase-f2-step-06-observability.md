# Task: Step 06 Phase F2 - Payroll Correlation & Logging Enhancements

## Problem
Payroll run operations lacked consistent correlation tracking. Logs sometimes missed `correlation_id`/`payroll_run_id`, queued jobs overwrote log context, and the UI gave no reference ID back to admins when calculations succeeded, queued, or failed.

## Approach
- Added `App\Support\CorrelationIdManager` to centralise correlation ID creation, request storage, and log context seeding.
- Updated `PayrollCalculationService`, `PayrollController`, and `ProcessPayrollRun` to rely on the manager, enrich log context with `payroll_run_id`, and surface the correlation reference in session flashes for both synchronous and queued paths.
- Ensured queue jobs and validation flows share the same correlation context so warning/error logs are traceable.
- Extended feature/unit coverage to assert chunked calculations accept the extra dependency and that controller responses include the correlation reference.

## Files Changed
- `app/Support/CorrelationIdManager.php`
- `app/Services/PayrollCalculationService.php`
- `app/Http/Controllers/PayrollController.php`
- `app/Jobs/ProcessPayrollRun.php`
- `tests/Feature/Payroll/PayrollQueueTest.php`
- `tests/Unit/Payroll/PayrollChunkingTest.php`

## Tests
- `php artisan test --filter=PayrollQueueTest`
- `php artisan test --filter=PayrollChunkingTest`
- `php artisan test --filter=FormulaGraphTest`

## Rollback Plan
1. `git revert <commit-hash>`.
2. Remove `app/Support/CorrelationIdManager.php` and the updated imports/usages in controllers, services, and jobs.
3. Restore controller session flashes and log calls to their previous state.
4. Drop the new test expectations and this task document.
