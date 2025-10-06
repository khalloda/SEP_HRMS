# Codex/Auto Prompt — Employee Salary Structure History (Feature Spec & Execution Plan)

**Branch:** `feat/employee-salarystructure-history`  
**Mode:** Auto (follow `Agent_Rules.md` — commits, docs, tests, rollback, **no Codacy**).  
**DB:** LIVE reads allowed. **Non‑destructive ADDs** (columns/indexes/views) are allowed. Any DROP/DELETE/RENAME/TYPE change requires confirmation.

---

## Goal
Build a **view/report** that shows an employee’s **salary structure history** across their entire tenure (or filtered by year(s) / contract), including:
- Totals per period: **GROSS**, **NET**, and **component breakdown** (earnings vs deductions).
- Visibility respects RBAC (only users with payroll visibility can see NET/GROSS).
- Export to **PDF** and **Excel**.
- Arabic/RTL verified in PDF.

This feature is read‑heavy; prioritize correctness, performance, and security.

---

## Functional Requirements

### Inputs / Filters
- **Employee**: by `employee_id` (required).
- **Period**: optional date range (from/to) or quick presets (year, last 12 months).
- **Contract**: optional `contract_id` filter (when an employee has multiple contracts).
- **Pagination** or infinite scroll for long histories.

### Output (per period / structural change)
- **Effective From / To** (structure validity window).
- **Structure Components** split as:
  - **EARNINGS** (positive)
  - **DEDUCTIONS** (negative)
- **Totals**:
  - **GROSS** (sum earnings before deductions)
  - **NET** (GROSS − deductions)
- **Audit**: who created/changed, timestamp (if available).
- **Links**: to payslip (if exists), to structure view.

### Exports
- **Excel**: one row per period, plus optional detail sheet per period.
- **PDF**: printable history summary (RTL/Arabic safe).

---

## Non‑Functional & Security
- **RBAC**: Enforce `payroll.view_net` (or agreed permission) before showing NET/GROSS.
- **Signed URLs** for any private file downloads.
- **Performance**: use chunked queries; add **indexes** if needed (non‑destructive ADD allowed).
- **I18n/RTL**: Arabic layout validated for PDF.
- **Feature Flag**: `use_salary_structure_history = true` (default **false** on live until approved).

---

## Data Sources (adapt to existing schema)
- `salary_structures` (or equivalent) with effective dates.
- `salary_structure_components` (type: earning/deduction, amount, formula, priority).
- `contracts` (to filter history by contract).
- `payslips` / `payroll_runs` (optional cross‑link).
- Any view/table that stores **computed totals**; otherwise compute on the fly using the formula engine.

> If a **missing column** is needed (e.g., `component_type`, `effective_to`), you may **ADD** it with safe defaults and document it in `docs/ops/change-log.md` (no DROP/RENAME without approval).

---

## Architecture

### Controller & Routes
- `GET /employees/{employee}/salary-history` → HTML view (filterable).
- `GET /employees/{employee}/salary-history/export` → `?format=pdf|xlsx&…`

Create `EmployeeSalaryHistoryController` (or add methods under existing Payroll/Employee controller).

### Service Layer
- `SalaryHistoryService`:
  - Resolve structures within date/contract filters.
  - Load components for each structure.
  - Compute **GROSS/NET** per structure period:
    - If totals persisted → use persisted values.
    - Else evaluate via formula engine (guarded; use feature flag).
  - Return typed DTOs for the view/export.

### Views
- Blade view: filter bar (range, year, contract), summary cards (GROSS/NET), accordion or table grouped by **Effective From**.
- Respect permissions: hide NET/GROSS for unauthorized users; show a notice.

### Exports
- `ReportExportService` (if present) or new lightweight export helpers.
- Excel via Maatwebsite/Excel; PDF via mPDF/Dompdf (whichever the project uses).
- Add job + queued flow if heavy; otherwise synchronous for a single employee.

---

## Implementation Steps (small, atomic)

1) **Scaffold**
   - Feature flag `config/payroll.php: 'use_salary_structure_history' => env('USE_SALARY_STRUCTURE_HISTORY', false)`
   - Routes + empty controller + policy checks.
   - Minimal Blade with filter form (no data yet).

2) **Service & Query**
   - Implement `SalaryHistoryService::fetch(employee, filters)` with efficient eager loads.
   - For each structure window, aggregate components → earnings/deductions → compute GROSS/NET.
   - Add **non‑destructive indexes** if slow (e.g., on `(employee_id, effective_from)`).
   - Unit tests for service logic (no UI).

3) **View Data & UI**
   - Render grouped rows per period with totals and a component breakdown (EARNINGS/DEDUCTIONS).
   - RBAC: hide NET/GROSS unless authorized.
   - Playwright test to validate rendering, filters, and RBAC masking.

4) **Exports**
   - Add `/export?format=pdf|xlsx` endpoints.
   - Implement Excel & PDF; verify Arabic/RTL.
   - Feature/Playwright tests to download and assert non‑empty content.

5) **Docs**
   - `docs/payroll/salary_history/SalaryHistory_Deep_Analysis.md`
   - `docs/payroll/salary_history/SalaryHistory_Plan.md`
   - `docs/payroll/salary_history/SalaryHistory_Runbook.md`
   - `docs/payroll/salary_history/SalaryHistory_ChangeLog.md`

6) **Stop & Review**
   - Summarize status, files changed, commit hash, NEXT. Await approval before enabling flag on live.

---

## Live DB Safety (recap)
- Allowed: **ADD COLUMN/INDEX/VIEW** with NULL/defaults; read heavy queries; exports.
- Requires approval: any **DROP/DELETE/RENAME/TYPE change**, mass UPDATE, or risk of downtime.
- Record every schema ADD in `docs/ops/change-log.md` with rollback notes.

---

## Acceptance Criteria
- Page loads for an employee with historical structures, respecting filters.
- GROSS/NET totals match expected calculation rules (or persisted totals).
- RBAC hides NET/GROSS for unauthorized viewers.
- PDF/Excel exports download and open.
- Tests: service unit tests + at least one feature + one Playwright path.
- Arabic/RTL PDF verified.

---

## Reply Format (every step)
- **status** — one line
- **files changed**
- **commit hash**
- **NEXT** — your next small action
- End with **“READY FOR NEXT?”** when awaiting approval
