# Task: Expression Evaluator Abstraction

## Problem
Payroll calculations relied on eval-based formula evaluation. We need an extensible abstraction to support a safer parser and allow toggling via config before replacing the legacy path.

## Approach
- Introduced `App\Services\Payroll\ExpressionEvaluator` handling both legacy (sanitised eval) and a new parser (shunting-yard) behind the `payroll.expression_engine` flag.
- Injected the evaluator into `PayrollCalculationService` and replaced direct `safeEvaluate` calls; removed the old helper methods.
- Added PHPUnit coverage for both evaluator modes and guarded division-by-zero/invalid inputs.

## Files Changed
- `app/Services/Payroll/ExpressionEvaluator.php`
- `app/Services/PayrollCalculationService.php`
- `tests/Unit/Payroll/ExpressionEvaluatorTest.php`

## Tests
- `php artisan test --testsuite=Unit`
- `php artisan test --filter=PayrollFeatureFlagTest`
- `php artisan test --filter=PayrollDemoSeederTest`

## Rollback Plan
1. `git revert <commit-hash>`.
2. Delete `app/Services/Payroll/ExpressionEvaluator.php` if reverting manually.
3. Restore the previous `PayrollCalculationService` (reintroduce `safeEvaluate`).
4. Remove `tests/Unit/Payroll/ExpressionEvaluatorTest.php`.
