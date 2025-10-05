# Salary Component Formula Deep Analysis

## Data Storage & Schema
- Formulas live on `salary_structure_components.formula_expr` (VARCHAR 1000) with sibling numeric fallback `value_numeric`; schema defined in `docs/HRMS_Schema.sql` and `DATABASE_DUMP/sep_hrms.sql`.
- Pivot model `SalaryStructureComponent` exposes `$fillable` for `formula_expr`, `$casts` for numeric and dependency JSON to ensure coercion when loading structures.
- Demo data snapshot (`DATABASE_DUMP/sep_hrms.sql`) shows simple expression `BASIC_SALARY*0.2` tied to component ID 49; no tiered logic in seed.
- UI surfaces formulas in salary structure create/edit Blade templates (`resources/views/salary-structures/{create,edit}.blade.php`) and posts them back via Alpine-powered arrays, persisting raw strings.

## Evaluation Flow Today
- `PayrollCalculationService::evaluateFormulaComponent()` replaces tokens matching `/\b[A-Za-z_][A-Za-z0-9_]*\b/` with already-computed component values (uppercased keys) or `0` if missing.
- Substitution happens before calculator call; missing tokens silently default to zero, masking data errors.
- Cleaned expression passes to `App\Services\Payroll\ExpressionEvaluator::evaluate()`.
- Evaluator selects engine via `config('payroll.expression_engine')` (`legacy` default, optional `new`).
- Feature flag `config('payroll.use_safe_engine_conditionals')` (Task 1) now toggles conditional-aware parser path; when enabled component substitution preserves recognised functions (`IF`) and logical keywords.
- **Legacy engine**: strips everything except digits, `+-*/().` then executes `eval("return {$cleanExpression};")`; supports floats, unary minus via PHP eval, but inherits PHP precedence and double precision quirks.
- **New engine**: shunting-yard parser producing Reverse Polish Notation; supports operators `+ - * /`, parentheses, unary minus; division guarded by epsilon to block divide-by-zero.
- Both engines throw `InvalidArgumentException` on invalid syntax or zero division; caller catches generic `\Throwable` and logs error, returning `0.0` for the component.

## Variable Resolution & Types
- Component codes normalized to uppercase before substitution; values stored in `$calculatedValues` as floats (`float` casting on insert). No decimal scaling beyond PHP double; final amounts stored to DB via Eloquent casts (`decimal:2`).
- No support for functions, comparisons, boolean operators, or string literals. Attendance/context variables injected elsewhere (e.g., variable net-based components) but not accessible inside formulas.
- Error handling: if regex replacement fails (`preg_replace_callback` returning null) service throws `RuntimeException`; upstream catches and logs.
- Result propagation: calculated amount saved in payslip line (`payslip->payslipLines()->create`) including metadata (`formula_used`, `calculation_notes`). `Payslip::updateTotals()` recomputes gross/deductions/net, then totals roll up to payroll run via `PayrollCalculationService::updateRunTotals()`.

## Performance & Logging Notes
- Salary structures evaluated sequentially per employee; chunking at payroll level uses `config('payroll.chunk_size')`, but expression evaluation is per component without caching.
- No memoization; repeated references within a formula re-use numeric substitution outputs but not re-computed mid-expression.
- Logging includes debug entry per component with amount; errors write to log with correlation ID when available, but user-facing feedback limited to generic error list.
- When conditional flag disabled, validation rejects `IF(` usage in salary structure controllers to prevent silent failures; UI helper text clarifies flag state.
