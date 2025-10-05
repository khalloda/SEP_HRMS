# Runbook — Salary Structure History

## Enable Feature (safe)
- Set `USE_SALARY_STRUCTURE_HISTORY=true` in environment
- Clear config cache: `php artisan config:clear`

## URLs
- View: `/employees/{employee}/salary-history`
- Export: `/employees/{employee}/salary-history/export?format=xlsx|pdf`

## Permissions
- View requires `employees.view` (via policy)
- NET/GROSS visible only with `payroll.view_net`

## Rollback
- Set `USE_SALARY_STRUCTURE_HISTORY=false`
- Optionally `git revert` the feature commits

## Notes
- Exports are generated on-demand; for heavy usage, consider queuing
- PDF uses mPDF with UTF‑8 and Arabic/RTL support
