# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

## Project Overview

This is an HRMS (Human Resource Management System) for Sarie Eldin & Partners  Legal Advisors, a law firm with ~50 employees. The system will be built using Laravel/PHP and deployed on GoDaddy cPanel hosting.

## Project Status

**ACTIVE DEVELOPMENT** - Phase 1 Foundation is substantially complete. Laravel 10 application is operational with comprehensive employee management system implemented.

### ✅ Completed Components:
- Laravel 10.49.0 application setup with PHP 8.4
- Complete database schema (27+ tables) imported
- Bilingual localization system (English/Arabic with RTL support)
- Role-Based Access Control with Spatie Permission package
- Master data models (Department, Position, EmploymentType)
- User model with RBAC integration
- Middleware for language switching
- Activity logging setup for audit trails
- **Employee model with encryption and fulltext search**
- **Employee CRUD controller with advanced filtering**
- **Contract model with expiry tracking and automation**
- **Responsive Employee views with Sarie Eldin branding**
- **Employee Policy for authorization**
- **Automated contract expiry command with scheduler**

### 🔄 In Progress:
- Document management system
- Payroll system setup

### 📋 Next Phase:
- Payroll system implementation
- Attendance integration
- Document management interface

## Key Architecture Decisions

- **Backend**: Laravel 10+ with PHP 8.2
- **Database**: MySQL 8 with utf8mb4 charset for Arabic support
- **Deployment**: GoDaddy cPanel (no SSH access)
- **Localization**: Bilingual English/Arabic with RTL support
- **PDF Generation**: mPDF library for Arabic text rendering
- **File Storage**: Laravel private disk for sensitive documents with signed routes
- **Authentication**: Role-Based Access Control (RBAC) with 6 core roles

## Development Commands

The Laravel application is fully operational. Current working commands:

```bash
# Development Server
php artisan serve

# Database Operations (Schema already imported)
php artisan tinker  # For database interactions
php artisan migrate:status  # Check migration status

# Testing (once tests are created)
php artisan test
./vendor/bin/phpunit

# Code Quality & Maintenance
php artisan config:cache
php artisan route:cache
php artisan view:cache
composer dump-autoload

# Package-specific Commands
php artisan vendor:publish --provider="Spatie\Permission\PermissionServiceProvider"
php artisan vendor:publish --provider="Spatie\Activitylog\ActivitylogServiceProvider"
```

### Database Configuration
- **Database**: `sep_hrms`
- **Username**: `root`
- **Password**: `1234`
- **Host**: `localhost:3306`
- **Charset**: `utf8mb4` (Arabic support enabled)

### Current Environment
- Laravel Framework 10.49.0
- PHP 8.4.12
- MySQL with 27+ tables imported
- Spatie Permission package configured

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
- `Claude_Plan.md`: Technical implementation roadmap for Claude Code
- `Claude_Tasks.md`: Granular development tasks with file specifications
- `CLAUDE.md`: This file - guidance for Claude Code development

## Implemented Models & Features

### Authentication & Authorization
- `app/Models/User.php`: Enhanced with Spatie Roles, activity logging, and HRMS relationships
- `app/Models/Role.php`: Extended Spatie model with HRMS-specific role logic
- `app/Models/Permission.php`: Enhanced with categorization and sensitivity flags
- `app/Http/Middleware/SetLocale.php`: Language switching middleware

### Master Data Models
- `app/Models/Department.php`: Bilingual departments with employee statistics
- `app/Models/Position.php`: Lawyer/Admin positions with hierarchy and overtime eligibility
- `app/Models/EmploymentType.php`: Contract types with benefit eligibility rules

### Localization System
- `resources/lang/en/hrms.php`: English HRMS-specific translations
- `resources/lang/ar/hrms.php`: Arabic translations with proper RTL support
- `resources/lang/ar/auth.php`: Arabic authentication messages
- `resources/lang/ar/validation.php`: Complete Arabic validation messages

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