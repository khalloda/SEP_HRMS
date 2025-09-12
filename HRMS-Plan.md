
# HRMS Implementation Plan (GoDaddy cPanel) — Updated

## Phase 0 — Prep (1 week)
- Confirm domain & SSL for `hrms.sarieldin.com`.
- Create MySQL DB + user.
- Finalize RBAC matrix & payroll component catalog.
- Prepare ZKTeco connector host (office PC/server).

## Phase 1 — Foundation (2–3 weeks)
- Users/RBAC, departments, positions, employees.
- File storage (private disk + signed routes), templates engine with EN/AR.
- Contract module with expiry jobs + dashboard widgets.
- **Global search (fulltext)** across employees; list filters for expiries and missing IDs.
- Deploy to cPanel (ZIP upload). Enable cron or external webcron.

## Phase 2 — Payroll (2–3 weeks)
- Salary components (fixed/formula/net-based) + structure per employee.
- Payroll run flow: draft → lock → post; payslip PDF rendering.
- Role-based visibility (hide Net/Gross where required).
- **Two-step approvals** for payroll posting (generic approvals engine).

## Phase 3 — Attendance (1–2 weeks)
- /api/attendance/push endpoint + token auth.
- Rollups (late/absence/OT — OT only for Messengers/Office Boys).
- Attendance reports & anomaly alerts.

## Phase 4 — Documents & Compliance (1–2 weeks)
- **Document versioning** + **tagging** (National ID, Bar License).
- **Bar Association Registration** as special doc type with expiry reminders.
- **Audit trail** for contracts & salary structure edits.
- **Weekly digest email**: expiries, pending approvals, birthdays.

## Phase 5 — Self-Service & Reports (1–2 weeks)
- Employee portal (profile, payslips, HR letters).
- Reports: payroll by dept/role, overtime (eligible roles), contract expiry, attrition; export Excel/PDF.
- **Role-based dashboards** (HR, Accountant, Employee).

## Phase 6 — Hardening & Future-proofing (1 week)
- Field-level encryption (national ID, salary figures).
- Document watermarking (“Confidential – HR Use Only”) on privileged downloads.
- API-first endpoints (selected read/write) for future mobile/Zoho Books.
- Onboarding wizard (profile → contract → salary → documents).
- Case link table prepared for future integration.
- Backups (DB + files, encrypted) + healthcheck.

## Deployment Model (no SSH)
- Build locally (composer/npm) → zip (with /vendor) → cPanel upload.
- DB schema import via phpMyAdmin (`HRMS_Schema.sql`) + optional seed (`HRMS_Seed.sql`).
- `.env` via File Manager. Cron via cPanel or external web cron (`/cron/schedule?token=...`).

## Success Criteria
- 100% EN/AR coverage; accurate fonts in PDFs.
- Expiry notifications fire (30/15/7 days) and appear in weekly digest.
- Payroll results match HR’s control sheet for a pilot month; approvals enforced.
- Attendance import validated end-to-end.
- Document versioning/tagging operational; audit logs capture critical edits.
