# Salary History — Change Log

## 2025-10-06
- Added Detailed/Consolidated toggle for view, PDF, and Excel.
- Added optional multi-sheet Excel export (Summary + one sheet per period) via `multisheet=1`.
- Added Arabic/RTL QA checklist.
- Added non-destructive indexes for salary history queries.

Status: NOT TESTED YET (manual and automated tests pending).

Rollback:
- Toggle off feature via query params (`detail=0`, omit `multisheet=1`).
- Revert commits if needed:
  - 6bddb06 excel(salary-history): optional multi-sheet export toggle
  - 46ce87a salary-history: add detailed/consolidated toggle
  - f43ef64 migrations(salary-history): INFORMATION_SCHEMA index checks
