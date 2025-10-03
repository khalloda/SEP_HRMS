# Task: Step 05 Phase C2 - Queued Payroll Processing Scaffold

## Problem
Payroll calculations still ran synchronously, blocking the request thread and risking timeouts for large employee sets. There was no chunking to keep memory bounded, nor any worker job to offload heavy calculations when the queue flag is enabled.

## Approach
- Added configurable chunked iteration inside `PayrollCalculationService`, including orchestration helpers, totals refresh, and correlation-aware logging that works for both synchronous and queued contexts.
- Introduced a queueable `ProcessPayrollRun` job and controller branch that dispatches it when `payroll.queue_enabled` is true, keeping existing synchronous behaviour as the default fallback.
- Expanded payroll config with chunk size and queue connection/name toggles so environments can tailor throughput without editing code.
- Created focused unit/feature coverage for chunk sizing and controller/job dispatch to guard the new execution paths.

## Files Changed
- `app/Services/PayrollCalculationService.php`
- `app/Jobs/ProcessPayrollRun.php`
- `app/Http/Controllers/PayrollController.php`
- `config/payroll.php`
- `tests/Unit/Payroll/PayrollChunkingTest.php`
- `tests/Feature/Payroll/PayrollQueueTest.php`
- `docs/payroll/tasks/TASK_step-05-phase-c2-queued-processing.md`

## Tests
- `php artisan test --filter=PayrollChunkingTest`
- `php artisan test --filter=PayrollQueueTest`
- `php artisan test --filter=FormulaGraphTest`

## Rollback Plan
1. `git revert <commit-hash>`.
2. Delete `app/Jobs/ProcessPayrollRun.php` if reverting manually.
3. Restore `PayrollCalculationService` and `PayrollController` to their previous versions to remove queue/chunk logic.
4. Reset `config/payroll.php` to the earlier keys and clear caches (`php artisan config:clear`).
5. Remove the added tests and this task document.
