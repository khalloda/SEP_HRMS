
# HRMS Tasks — Updated

## Legend
- **Priority**: P0 (must), P1 (should), P2 (nice)
- **Owner**: assign to specific devs
- **Output**: PR, file, or artifact

---

## P0 — Foundation
- [ ] (P0) Initialize Laravel project; configure locales EN/AR. **Output**: repo scaffold
- [ ] (P0) Implement users/RBAC (roles, permissions). **Output**: migrations, seeders
- [ ] (P0) Departments, Positions, Employment Types CRUD. **Output**: UI + tests
- [ ] (P0) Employees CRUD + manager mapping + photos; **fulltext search**; filters for expiries/missing IDs. **Output**: forms, policies
- [ ] (P0) File storage (private) + signed download route; **watermarking** option. **Output**: controllers, policies
- [ ] (P0) Templates module (Blade in DB) + PDF (mPDF) with Arabic fonts. **Output**: templates
- [ ] (P0) Contracts CRUD + terms JSON; expiry scheduler 30/15/7; dashboard widget. **Output**: jobs, mailables

## P0 — Payroll
- [ ] (P0) Salary components catalog & UI. **Output**: CRUD
- [ ] (P0) Salary structure per employee; dependency validation (DAG). **Output**: service + tests
- [ ] (P0) Payroll run flow (draft/locked/posted). **Output**: UI + policies
- [ ] (P0) **Two-step approvals** for payroll posting. **Output**: approvals engine + UI
- [ ] (P0) Payslip rendering (EN/AR) + role-based Net/Gross visibility. **Output**: PDFs
- [ ] (P0) Payroll reports (dept/role totals, export). **Output**: reports

## P1 — Attendance
- [ ] (P1) /api/attendance/push with HMAC token. **Output**: controller + docs
- [ ] (P1) Rollup job (daily). **Output**: scheduler
- [ ] (P1) OT calc only for Messengers/Office Boys. **Output**: policy + calc
- [ ] (P1) Attendance reports. **Output**: reports

## P1 — Documents & Compliance
- [ ] (P1) **Document versioning** table + UI; on upload, archive old as new version. **Output**: DB + controller
- [ ] (P1) **Tagging** for documents (National ID, Bar License). **Output**: DB + UI
- [ ] (P1) **Audit logs** for contract & salary structure edits. **Output**: listeners
- [ ] (P1) Weekly **digest email** (expiries, birthdays, pending approvals). **Output**: mailable + sched

## P1 — Self-Service & Dashboards
- [ ] (P1) Employee portal: profile view; downloads (own docs only). **Output**: pages
- [ ] (P1) HR letters generator (parameterized). **Output**: PDFs
- [ ] (P1) **Role-based dashboards** (HR, Accountant, Employee). **Output**: views

## P2 — API & Onboarding
- [ ] (P2) Public/internal API endpoints (REST JSON) for selected use cases. **Output**: routes + docs
- [ ] (P2) **Onboarding wizard** (profile → contract → salary → documents). **Output**: wizard UI
- [ ] (P2) **Bar Association Registration** doc type with reminder. **Output**: seed + reminder
- [ ] (P2) **Case link** table integration (employee_cases). **Output**: basic UI

## Acceptance Tests (sample)
- [ ] Create contract with end_date in 30 days → email + dashboard alert fires and appears in weekly digest.
- [ ] Run payroll for sample month → totals match HR sheet; posting requires two approvals.
- [ ] Coordinator/Accountant cannot see Net/Gross in UI or PDFs.
- [ ] Upload new ID → old copy preserved as prior **version**; tags searchable.
- [ ] Push two punches from connector → daily rollup matches expected.
- [ ] Global search finds an employee by Arabic name or code.
