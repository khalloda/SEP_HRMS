
# HRMS Product Requirements Document (PRD)
**Sarie Eldin & Partners – Legal Advisors**  
URL: [https://hrms.sarieldin.com](https://hrms.sarieldin.com)  
Deployment: GoDaddy Hosting, cPanel, MySQL DB, File Storage on same host

---

## 1. Overview
Sarie Eldin & Partners – Legal Advisors, a leading law firm (~50 employees), requires a bilingual HRMS (English/Arabic) to manage employee data, contracts, payroll, documents, attendance, and notifications.  
The system must reflect the **firm’s branding and website theme** while ensuring **role-based access control, payroll confidentiality, and compliance**.

---

## 2. User Roles & Permissions
### Core Roles
- **HR & Administration Manager**: Full control (employees, contracts, payroll).  
- **Accounting Manager**: Full payroll & salary access.  
- **HR Coordinator**: Manage employees/contracts/docs/payroll components (except Net/Gross).  
- **Accountant**: Same as HR Coordinator, financial focus (except Net/Gross).  
- **Employees**: Self-service portal (profile, payslips, HR docs).  
- **IT Admin (Systems Engineer)**: System setup, user mgmt, integrations, no HR content.  

---

## 3. Employee Management
- Employee profiles (personal data, department, position, manager).  
- Lawyer positions: Managing Partner, Senior Partner, Partner, Junior Partner, Senior Associate, Associate, Junior Associate, Intern.  
- Admin staff: HR/Admin Manager, Accounting Manager, Accountant, Senior Systems Engineer, Messenger, Admin Assistant, Office Boys.  
- Contract types: Permanent, Fixed-term, Probation, Internship, Consultancy.  
- Document management: ID, contracts, bar association, etc.  
- Expiry notifications (contracts, IDs, permits).  

---

## 4. Payroll & Salary Structure
- Components: Basic, Allowances, Deductions, Bonuses, Overtime (only Messengers & Office Boys).  
- Access rules:  
  - HR/Admin Manager + Accounting Manager: Full visibility.  
  - HR Coordinator & Accountant: Components only, no Net/Gross.  
- Formulas: variable & derived (e.g., Gross = Basic + Allowances – Deductions).  
- Payroll runs: lock, generate payslips, reports.  

---

## 5. Attendance & Time Tracking
- Integration: ZKTeco biometric machines (via local connector → HRMS API).  
- Logs: check-in/out, late, absence, overtime.  
- Overtime: Only for eligible staff (Messengers, Office Boys).  

---

## 6. Contracts & Agreements
- Configurable terms.  
- Expiry tracking with alerts (30/15/7 days).  
- Renewal/termination workflows.  
- Linked to salary structure.  

---

## 7. Templates & Documents
- Templates: Contracts, Payslips, Employment Proof, HR Letters.  
- Admin-manageable template library.  
- Merge fields: {EmployeeName}, {Position}, {NetSalary}.  
- Bilingual output (EN/AR).  
- PDFs generated with **mPDF** (for Arabic RTL).  

---

## 8. Notifications & Communication
- Dashboard alerts + Email alerts (contract expiry, pending approvals).  
- Digest email: weekly HR summary (expiring docs, birthdays, approvals).  
- Pending approvals displayed on dashboard.  

---

## 9. Employee Self-Service Portal
- View/update profile.  
- Download payslips & HR letters.  
- Request contracts/proofs.  
- EN/AR toggle.  

---

## 10. Frontend Theme & Branding
### Visual Identity
- **Colors**:  
  - Gold: `#c6a44a`  
  - Dark Green: `#2e4029`  
  - Cream/Beige: `#f9f5e6`  
- Typography: Serif for titles, sans-serif for body.  
- Professional, corporate look, aligned with firm’s website.

### Layout
- **Header**: Logo (left), nav (center), language toggle + user profile (right).  
- **Sidebar**: Optional collapsible, gold-highlight active section.  
- **Footer**: Privacy, Contact HR, Careers, © Sarie Eldin & Partners.  

### UI Components
- Cards: Rounded, gold headers.  
- Tables: Alternating cream/white rows.  
- Forms: Clean, bilingual, RTL toggle.  
- Buttons: Gold (primary), Green (secondary).  

### Pages
- **Dashboard**: Expiry alerts, attendance summary, payroll cycle, notifications.  
- **Employee Profile**: Photo, info, contracts, docs, payroll, attendance.  
- **Payroll**: Salary table, export payslips.  

---

## 11. Technical Environment (GoDaddy cPanel)
- **Stack**: PHP 8.2 + Laravel (built locally, uploaded).  
- **DB**: MySQL 8 (utf8mb4).  
- **File storage**:  
  - Private (`/storage/app/private`) for sensitive docs.  
  - Public (`/public/uploads`) for thumbnails/templates.  
  - Access via signed routes.  
- **Deployment**: Build locally (Composer, NPM) → upload ZIP via cPanel.  
- **Cron**: cPanel UI or external webcron hitting `/cron/schedule`.  
- **Attendance**: Local connector pushing JSON to `/api/attendance/push`.  

---

## 12. Audit & Compliance Enhancements
- Two-step approvals for sensitive actions (contract termination, payroll posting).  
- Audit trails for contract edits, salary structure changes.  

---

## 13. Search & Filters
- Global search bar: employees/contracts by name, code, position, expiry date.  
- Filters: contracts expiring this quarter, employees without valid IDs.  

---

## 14. File Management Additions
- Versioning: retain old versions of documents when replaced.  
- Tagging: categorize docs (National ID, Bar License).  

---

## 15. Security Layers
- Role-based dashboards (HR vs Accountant vs Employee).  
- Field-level encryption (National ID, salary).  
- Document access watermarking (e.g., “Confidential – HR Use Only”).  

---

## 16. Reporting
- Payroll by department/role.  
- Overtime report (messengers/office boys).  
- Contract expiry list.  
- Employee attrition/headcount trends.  
- Export: Excel/PDF.  

---

## 17. Non-Functional Requirements
- Scalable up to 200 users.  
- Mobile responsive.  
- EN/AR toggle (RTL for Arabic).  
- Optimized for shared hosting.  

---

## 18. Extras (Future-Proofing)
- API-first mindset (REST/JSON for future mobile/Zoho Books integration).  
- Onboarding wizard (step-by-step employee addition).  
- Bar Association Registration as special doc type with expiry reminder.  
- Case assignment history integration point (future).  

---

## 19. Phase 2 (Future)
- Performance evaluation.  
- Training & development.  
- Recruitment workflow.  
- Mobile app for self-service.  

---

## 20. Deliverables (Phase 1)
- HRMS core (employees, payroll, contracts, attendance, templates).  
- Bilingual frontend matching corporate website.  
- Secure document storage.  
- Notification system.  

---

## 21. Deployment Checklist
1. Create subdomain `hrms.sarieldin.com` → docroot `/app/public`.  
2. Import DB schema via phpMyAdmin.  
3. Upload built project ZIP → extract in `/home/<user>/app`.  
4. Configure `.env`.  
5. Set writable permissions on `/storage` and `/bootstrap/cache`.  
6. Add cPanel cron (or external webcron).  
7. Run AutoSSL for domain.  
8. Test PDF generation (Arabic + English).  

---

**End of PRD**
