# Salary Component Formula User Guide

## 1. Overview
Salary component formulas let you automate payroll calculations by composing arithmetic expressions with component codes (e.g., `BASIC_SALARY`, `HOUSING_ALLOWANCE`). Each computed amount is stored on the related payslip line and feeds downstream totals (gross, deductions, net). This guide outlines syntax, feature flags, and best practices for authoring reliable formulas.

## 2. Prerequisites & Feature Flags
- Conditional logic requires the safe parser: set `PAYROLL_SAFE_ENGINE_CONDITIONALS=true` in `.env` and run `php artisan config:clear`.
- When the flag is `false`, formulas are restricted to arithmetic; entering `IF(` will trigger validation errors.
- The parser operates in English locale and expects ASCII characters; commas are argument separators, not decimal markers.

## 3. Component References
- Use the component code exactly as defined (case-insensitive; system normalises to uppercase).
- Dependencies are resolved in priority order. A component can reference any previously computed component.
- Missing component references default to `0`. Validate your structures to avoid silent omissions.

## 4. Numeric Rules
- Standard decimal notation (`1234.56`); thousands separators are not permitted.
- Division by zero throws a validation error during calculation; ensure denominators cannot evaluate to `0`.
- Final amounts persist with two decimal places; intermediate operations use double precision.

## 5. Supported Operators
- **Arithmetic**: `+`, `-`, `*`, `/`
- **Comparison**: `>`, `<`, `>=`, `<=`, `==`, `!=`
- **Logical**: `AND`, `OR` (evaluate truthiness of comparison outputs; non-zero is `true`).
- Parentheses `(` `)` control evaluation order. Default precedence (highest to lowest): parentheses → multiplication/division → addition/subtraction → comparisons → `AND` → `OR`.

## 6. Functions
### 6.1 IF
```
IF(condition, value_if_true, value_if_false)
```
- Condition must resolve to numeric truthy (non-zero) or falsy (`0`).
- Arguments can be numbers, component references, or nested `IF` expressions.
- Nesting is supported without depth limits; ensure parentheses are balanced.

#### Examples
- Tiered tax:
  `IF(BASIC_SALARY<1000, 0, IF(BASIC_SALARY<10000, BASIC_SALARY*0.10, BASIC_SALARY*0.30))`
- Cap housing allowance at 40% of basic:
  `MIN(HOUSING_ALLOWANCE, BASIC_SALARY*0.4)` *(future function; currently replicate using nested IF)*
  `IF(HOUSING_ALLOWANCE > BASIC_SALARY*0.4, BASIC_SALARY*0.4, HOUSING_ALLOWANCE)`

> Additional functions (e.g., `MIN`, `MAX`, `ROUND`) are not yet available; use nested IF statements as shown.

## 7. Formula Examples
| Scenario | Formula |
| --- | --- |
| Social insurance capped at 5% | `IF(BASIC_SALARY>15000, 15000*0.05, BASIC_SALARY*0.05)` |
| Transportation allowance 10% of gross earnings | `(BASIC_SALARY + HOUSING_ALLOWANCE) * 0.10` |
| Net salary snapshot | `BASIC_SALARY + HOUSING_ALLOWANCE + TRANSPORT_ALLOWANCE - INCOME_TAX - SOCIAL_INSURANCE` |

## 8. Authoring Workflow
1. Ensure flag status matches planned syntax; toggle if IF statements will be used.
2. Create or edit salary structure components and enter formulas on the relevant rows.
3. Validation feedback:
   - Missing required fields: standard form errors.
   - Conditional syntax with flag off: `hrms.salary_structure.conditional_flag_required` message.
   - Post-save calculation errors (division by zero, invalid tokens) appear in payroll run logs and on the calculation result summary.
4. Recalculate payroll runs or individual payslips to validate results.

## 9. Troubleshooting
- **"Conditional formulas require the payroll conditional engine flag"**: enable `PAYROLL_SAFE_ENGINE_CONDITIONALS` and clear config cache.
- **Alpine validation errors (front-end)**: refresh after enabling flag to ensure helper badge renders (commit `95aafa7`).
- **Unexpected zero output**: confirm referenced component has a lower priority (calculated earlier) and the code matches exactly.
- **Parser error**: check for unsupported tokens, mismatched parentheses, or trailing commas.
- **Division by zero**: wrap denominator with safety check, e.g., `IF(HOURS_WORKED==0,0,TOTAL_PAY/HOURS_WORKED)`.

## 10. Best Practices
- Keep formulas simple; move complex logic into multiple components with clear descriptions.
- Document assumptions (rates, thresholds) in component notes.
- Use consistent priority ordering to avoid circular references; the system will raise an error if a cycle is detected.
- Test changes on staging payroll runs before enabling in production; compare payslip totals pre/post change.
- Version contracts or structures when adjusting tax rules mid-period to preserve historical accuracy.

## 11. Change Management Checklist
- Update documentation and internal SOPs whenever rates or thresholds change.
- Notify payroll approvers that conditional logic is active to ensure review of branching scenarios.
- After toggling the flag, monitor `storage/logs/laravel.log` for `Payroll component calculation failed` entries.

For additional details, refer to:
- `docs/payroll/salary_component_formula/salary_component_formula_Plan.md`
- `docs/payroll/salary_component_formula/salary_component_formula_Runbook.md`
- `config/payroll.php` for feature flag settings.
