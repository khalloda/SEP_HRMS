# Salary Structure History — Plan

## Scope
- Filters: employee (required), date range/presets, optional contract
- Output per structure period with earnings/deductions and totals
- RBAC: mask NET/GROSS when unauthorized
- Exports: Excel/PDF

## Steps
1. Feature flag + routes + controller + service (scaffold) — DONE
2. Implement aggregation using structure pivots — DONE
3. Blade render + pagination — DONE (basic)
4. Exports via Excel/PDF — DONE (baseline)
5. Tests: unit/service, feature route checks, Playwright path — TODO
6. Polish UI, Arabic/RTL verification — TODO
7. Performance review and add indexes if needed — TODO

## Rollback
- `git revert` the feature commits; flag defaults to false so feature remains off.
