# Task: Step 04 Phase C1 - Component Dependency Graph Safeguards

## Problem
Formula-driven payroll components could reference each other in a cycle, causing calculations to recurse forever or yield incorrect values once the new evaluator is fully wired in. We needed a deterministic graph build that blocks circular references before any run proceeds.

## Approach
- Added a reusable dependency graph builder to `PayrollCalculationService` and surfaced `assertNoCircularDependencies()` so other flows (recalc, validations, future queue job) can guard structures.
- Raised a dedicated `PayrollDependencyCycleException` capturing the offending cycle and structure ID; integrated handling in payslip recalculation and run validation with structured logging (including correlation IDs when present).
- Extended run validation to scan each unique salary structure used by eligible employees, returning descriptive issues when cycles exist.
- Wrote unit coverage (`FormulaGraphTest`) exercising cycle detection, acyclic graphs, and dependencies that point outside the structure.

## Files Changed
- `app/Exceptions/PayrollDependencyCycleException.php`
- `app/Services/PayrollCalculationService.php`
- `tests/Unit/Payroll/FormulaGraphTest.php`
- `docs/payroll/tasks/TASK_step-04-phase-c1-component-dependency-graph.md`

## Tests
- `php artisan test --filter=FormulaGraphTest`

## Rollback Plan
1. `git revert <commit-hash>`.
2. Delete `app/Exceptions/PayrollDependencyCycleException.php` if reverting manually.
3. Restore `PayrollCalculationService` to the previous revision (removing dependency graph logic).
4. Remove `tests/Unit/Payroll/FormulaGraphTest.php`.
5. Delete this task doc.
