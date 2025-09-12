# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

## Project Overview

This is an HRMS (Human Resource Management System) for Sarie Eldin & Partners  Legal Advisors, a law firm with ~50 employees. The system will be built using Laravel/PHP and deployed on GoDaddy cPanel hosting.

## Project Status

Currently in planning phase. The repository contains comprehensive documentation in the `docs/` directory but no implementation yet. This is a **Phase 1** project focused on core HRMS functionality.

## Key Architecture Decisions

- **Backend**: Laravel 10+ with PHP 8.2
- **Database**: MySQL 8 with utf8mb4 charset for Arabic support
- **Deployment**: GoDaddy cPanel (no SSH access)
- **Localization**: Bilingual English/Arabic with RTL support
- **PDF Generation**: mPDF library for Arabic text rendering
- **File Storage**: Laravel private disk for sensitive documents with signed routes
- **Authentication**: Role-Based Access Control (RBAC) with 6 core roles

## Development Commands

Since no Laravel project exists yet, these will be the standard Laravel commands once initialized:

```bash
# Development
php artisan serve
php artisan migrate
php artisan db:seed

# Testing  
php artisan test
./vendor/bin/phpunit

# Code Quality
php artisan ide-helper:generate
composer dump-autoload
```

## Core Business Logic

### User Roles & Permissions
- **HR & Administration Manager**: Full system access
- **Accounting Manager**: Full payroll access including Net/Gross
- **HR Coordinator**: Employee/contract/document management (no Net/Gross)
- **Accountant**: Financial focus (no Net/Gross)
- **Employees**: Self-service portal only
- **IT Admin**: System administration only

### Critical Security Requirements
- **Payroll Confidentiality**: HR Coordinator & Accountant roles cannot view Net/Gross salary amounts
- **Document Watermarking**: Privileged downloads include watermarks like "Confidential  HR Use Only"
- **Field-Level Encryption**: National ID and salary figures encrypted at rest
- **Two-Step Approvals**: Required for sensitive actions (contract termination, payroll posting)

### Data Architecture Highlights
- **Employee Management**: Full employee lifecycle with manager hierarchies
- **Contracts**: Configurable terms with expiry tracking (30/15/7 day alerts)
- **Payroll**: Complex salary structures with formula-based calculations
- **Documents**: Versioning system with tagging (National ID, Bar License, etc.)
- **Attendance**: ZKTeco biometric integration via API endpoint
- **Templates**: Dynamic PDF generation for contracts, payslips, HR letters

## Deployment Model

**Important**: No SSH access on GoDaddy cPanel. Deployment process:
1. Build locally with `composer install --no-dev` and `npm run build`
2. Create ZIP package including `/vendor` directory
3. Upload via cPanel File Manager
4. Extract to `/home/<user>/app` directory
5. Configure `.env` via File Manager
6. Import database via phpMyAdmin
7. Set up cron jobs via cPanel or external webcron

## Integration Points

- **ZKTeco Attendance**: `/api/attendance/push` endpoint with HMAC token authentication
- **Email Notifications**: Contract expiry alerts, weekly digest emails
- **Future Integrations**: API-first design for potential mobile app or Zoho Books integration

## Database Schema

The complete schema is in `docs/HRMS_Schema.sql` with:
- **37+ tables** including core entities, audit logs, approval workflow
- **Fulltext search** on employee names/emails/codes
- **JSON columns** for flexible data storage (contract terms, salary formulas)
- **Foreign key constraints** with proper cascading rules

## Documentation Structure

- `docs/HRMS_PRD_SarieEldin.md`: Complete Product Requirements Document
- `docs/HRMS-Plan.md`: 6-phase implementation timeline
- `docs/HRMS-Tasks.md`: Detailed task breakdown with priorities
- `docs/HRMS_ERD.md`: Entity Relationship Diagram (Mermaid format)
- `docs/HRMS_Schema.sql`: Complete database schema
- `docs/HRMS_Seed.sql`: Initial data for roles, departments, positions

## Development Priorities

**Phase 1 (Foundation)**: Users/RBAC, employees, contracts, file storage, templates
**Phase 2 (Payroll)**: Salary structures, payroll runs, two-step approvals
**Phase 3 (Attendance)**: API integration, rollup calculations
**Phase 4 (Compliance)**: Document versioning, audit trails, notifications
**Phase 5 (Self-Service)**: Employee portal, reporting
**Phase 6 (Hardening)**: Security enhancements, API endpoints

## Branding & UI

- **Colors**: Gold (#c6a44a), Dark Green (#2e4029), Cream (#f9f5e6)
- **Typography**: Serif for titles, sans-serif for body text
- **Layout**: Professional corporate design matching firm's website
- **Bilingual**: English/Arabic toggle with proper RTL support