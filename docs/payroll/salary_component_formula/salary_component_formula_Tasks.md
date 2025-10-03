# Payroll Formula Conditional Logic Tasks

## Overview
Implement Excel-style conditional formulas under a guarded config flag while preserving current arithmetic-only behaviour. Each task targets no more than two files.

## Task 1 — Feature Flag Scaffold
- **Purpose**: Introduce config + env plumbing to toggle conditional evaluation.
- **Files**: `config/payroll.php`, `.env.example`.
- **Acceptance**: Flag default false; tests confirm `config('payroll.use_safe_engine_conditionals')` resolves.
- **Tests**: Update/extend configuration unit test (if existing) or add new `tests/Unit/Payroll/PayrollConfigTest.php`.
- **Rollback**: Revert commit (`git revert`), remove config entry, clear env docs.
- **Status**: ✅ Completed (commit adds `use_safe_engine_conditionals` keyed off `PAYROLL_SAFE_ENGINE_CONDITIONALS`).

## Task 2 — Parser Capability Extension
- **Purpose**: Add comparison operators and boolean evaluation to `ExpressionEvaluator` new engine.
- **Files**: `app/Services/Payroll/ExpressionEvaluator.php`, `tests/Unit/Payroll/ExpressionEvaluatorTest.php`.
- **Acceptance**: Supports `> < >= <= == !=`, logical `AND/OR` (if included) returning boolean values for IF evaluation; legacy engine untouched.
- **Tests**: Unit tests for operator precedence, unary handling, divide-by-zero.
- **Rollback**: Revert commit; ensure tests back to original state.

## Task 3 — Function Dispatch Layer
- **Purpose**: Allow whitelist functions (`IF`) resolving to numeric outputs within evaluator.
- **Files**: `ExpressionEvaluator`, new helper class (e.g., `ExpressionFunctionRegistry`), tests.
- **Acceptance**: `IF(condition, x, y)` executes first true branch; nested calls supported; invalid argument counts raise descriptive exception.
- **Tests**: Unit: true/false branches, nested IF, error handling.
- **Rollback**: Revert commit; delete helper class.
- **Status**: ✅ Completed (commit introduces `ExpressionFunctionRegistry`, adds IF support with nesting and validation tests).

## Task 4 — Payroll Service Integration
- **Purpose**: Wire flag to choose conditional-capable engine, pass contextual variables if needed.
- **Files**: `PayrollCalculationService.php`, `config/payroll.php`.
- **Acceptance**: When flag true, evaluator uses new engine; when false, fallback to legacy behavior; existing formulas produce identical output.
- **Tests**: Feature test for payroll run with conditional formula; regression test ensuring legacy path unaffected.
- **Rollback**: Toggle flag off; revert commit if deeper issues.

## Task 5 — Validation & UX Messaging
- **Purpose**: Provide backend validation and UI hints for new syntax.
- **Files**: `app/Http/Requests/SalaryStructureComponentRequest.php` (or equivalent validator), `resources/views/salary-structures/{create,edit}.blade.php` for helper text.
- **Acceptance**: Invalid formula surfaces clear error; helper text explains `IF(condition, true, false)` syntax; AR translation stub added.
- **Tests**: Feature test covering validation failure; Playwright (if available) to confirm helper text displayed.
- **Rollback**: Revert commit; remove helper text/validation additions.

## Task 6 — Documentation & Runbook Update
- **Purpose**: Document usage, limitations, and support escalation steps.
- **Files**: Update current docs in `docs/payroll/salary_component_formula/*.md`.
- **Acceptance**: Docs mention feature flag, sample formulas, troubleshooting steps.
- **Tests**: Not applicable.
- **Rollback**: Revert documentation commit.
- **Status**: ✅ Completed (runbook/plan/deep analysis now highlight flag workflow, UI validation, and helper messaging).

## Task 7 — Post-Deployment Verification Script (Optional)
- **Purpose**: Provide artisan command to compare legacy vs new outputs for sample data (run manually pre/post toggle).
- **Files**: `app/Console/Commands/PayrollFormulaAuditCommand.php`, tests.
- **Acceptance**: Command exports comparison CSV; only executes when flag enabled.
- **Tests**: Unit test using fake data (Pest/PHPUnit).
- **Rollback**: Revert command commit.
