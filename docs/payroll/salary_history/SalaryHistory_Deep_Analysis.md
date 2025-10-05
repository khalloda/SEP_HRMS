# Salary Structure History — Deep Analysis

## Objective
Provide a read‑heavy report of an employee’s salary structure history with period windows, component breakdown, and totals, respecting RBAC and supporting exports.

## Data Sources
- `salary_structures` (employee_id, effective_from/to)
- `salary_structure_components` (structure_id, component_id, value_numeric, formula_expr, priority_order)
- `salary_components` (code, name, comp_type: earning|deduction|info)
- Optional cross-links to `payslips` and `payroll_runs` for drill‑through

## Design Notes
- Feature flag: `payroll.use_salary_structure_history` (env: `USE_SALARY_STRUCTURE_HISTORY`)
- Controller: `EmployeeSalaryHistoryController@index|export`
- Service: `SalaryHistoryService::fetch|export`
- RBAC: hide NET/GROSS unless user has `payroll.view_net`
- Exports: Excel via Maatwebsite/Excel; PDF via mPDF (Arabic/RTL safe)
- Performance: indexed by `(employee_id, effective_from)`; pagination 20/page

## Open Items
- Formula evaluation path (deferred to expression engine where needed)
- Detail sheet per period in Excel (optional)


