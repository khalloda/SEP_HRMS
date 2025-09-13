# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

## Project Overview

This is an HRMS (Human Resource Management System) for Sarie Eldin & Partners  Legal Advisors, a law firm with ~50 employees. The system will be built using Laravel/PHP and deployed on GoDaddy cPanel hosting.

## Project Status

**PHASE 1 FOUNDATION COMPLETE** - Laravel 10 application is fully operational with complete HRMS foundation implemented. All core systems are working and tested.

### ✅ Completed Components:

#### Authentication & User Management
- Complete authentication system (login, register, logout, profile)
- User-employee linking functionality with profile management
- Role-Based Access Control with Spatie Permission package
- Activity logging with Spatie ActivityLog for full audit trails
- Session management and remember me functionality
- Password reset and profile update capabilities

#### Employee Management System  
- Full Employee CRUD operations with advanced filtering
- Employee search with fulltext and basic search fallback
- Employee statistics and analytics dashboard
- Employee export functionality (Excel, PDF, CSV ready)
- Employee termination and reactivation workflows
- Manager hierarchies and reporting structures
- Employee photo upload and management system
- Employee model with encryption and fulltext search
- Responsive Employee views with Sarie Eldin branding
- Employee Policy for role-based authorization

#### Contract Management System
- Complete Contract CRUD operations with advanced filtering
- Contract expiry tracking with three-tier alert system (urgent/critical/soon)
- Contract renewal and termination workflows
- Smart contract terms auto-population based on contract type
- Contract statistics and analytics dashboard
- Contract type management (permanent, fixed-term, probation, internship, consultancy)
- Role-based contract access control and visibility
- Contract Policy with comprehensive authorization rules
- Activity logging for contract lifecycle management
- Bulk contract operations support

#### Document Management System
- Complete document upload and management interface  
- Document versioning system with history tracking
- Document tagging and categorization system
- Document expiry tracking with notification alerts
- Role-based document visibility and access control
- Watermarking support for confidential documents
- File type validation and size restrictions (10MB limit)
- Private file storage with signed download URLs

#### Payroll Structure Setup
- Salary component management (earnings, deductions, info)
- Predefined salary components with seeding capability
- Formula-based, fixed amount, and variable calculations
- Role-based salary component visibility (Net/Gross restrictions)
- Priority ordering for payroll calculations
- Support for multiple calculation modes and dependencies

#### Core Infrastructure
- Laravel 10.49.0 application with PHP 8.4
- Complete database schema (37+ tables) fully deployed
- All Spatie packages properly configured and working
- Bilingual localization system (English/Arabic with RTL support)
- Master data models (Department, Position, EmploymentType)
- Professional UI with corporate Sarie Eldin branding
- Responsive Bootstrap 5 interface with custom styling

### 📋 Phase 2 Priorities:
- Employee photo management and media library integration
- Salary structure assignment to individual employees
- Payroll run processing and payslip generation
- Advanced dashboard analytics and reporting widgets
- Contract lifecycle management with automated workflows
- Attendance system integration with ZKTeco devices

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
php artisan serve --host=0.0.0.0 --port=8000

# Database Operations
php artisan tinker  # For database interactions
php artisan migrate:status  # Check migration status
php artisan hrms:create-missing-tables  # Fix missing database tables

# HRMS-Specific Commands
php artisan db:seed --class=DefaultUsersSeeder  # Create default users
php artisan route:list  # View all available routes

# Testing (once tests are created)
php artisan test
./vendor/bin/phpunit

# Code Quality & Maintenance
php artisan config:clear
php artisan route:clear
php artisan view:clear
php artisan cache:clear
composer dump-autoload

# Package-specific Commands (already configured)
php artisan vendor:publish --provider="Spatie\Permission\PermissionServiceProvider"
php artisan vendor:publish --provider="Spatie\Activitylog\ActivitylogServiceProvider"
```

### Default User Accounts
Created via `DefaultUsersSeeder`:
- **HR Admin**: `hr@sarieldin.com` / `password123`
- **Accounting Manager**: `accounting@sarieldin.com` / `password123`  
- **IT Admin**: `it@sarieldin.com` / `password123`
- **HR Coordinator**: `hrcoord@sarieldin.com` / `password123`
- **Demo Employee**: `demo@sarieldin.com` / `password123`

### Database Configuration
- **Database**: `sep_hrms`
- **Username**: `root`
- **Password**: `1234`
- **Host**: `localhost:3306`
- **Charset**: `utf8mb4` (Arabic support enabled)

### Current Environment
- Laravel Framework 10.49.0
- PHP 8.4.12
- MySQL with 37+ tables fully operational
- Spatie Permission package configured with all roles/permissions
- Spatie ActivityLog package working with audit trails
- Bootstrap 5 with custom Sarie Eldin branding
- All routes registered and working properly

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
- `app/Models/User.php`: Enhanced with Spatie Roles, activity logging, and employee relationships
- `app/Http/Controllers/Auth/AuthController.php`: Complete authentication with login, register, profile management
- `app/Http/Middleware/SetLocale.php`: Language switching middleware
- `database/seeders/DefaultUsersSeeder.php`: Default user accounts with role assignments
- `app/Policies/`: Role-based policies for all models (Employee, Document, SalaryComponent, Contract)

### Employee Management System
- `app/Models/Employee.php`: Full employee model with relationships, search, encryption, photo management
- `app/Http/Controllers/EmployeeController.php`: Complete CRUD with filtering, export, statistics, photo upload
- `app/Policies/EmployeePolicy.php`: Comprehensive role-based access control
- `resources/views/employees/`: Professional responsive views (index, create, edit, show)
- Employee search with fulltext and fallback capabilities
- Manager hierarchies and reporting structures
- Photo upload/management with private storage

### Contract Management System
- `app/Models/Contract.php`: Comprehensive contract model with expiry tracking, renewal logic
- `app/Http/Controllers/ContractController.php`: Complete CRUD with filtering, renewal, termination, bulk operations
- `app/Policies/ContractPolicy.php`: Role-based contract access control and authorization
- `resources/views/contracts/`: Professional contract management interface (index, create, show)
- Contract types: permanent, fixed-term, probation, internship, consultancy
- Smart contract terms auto-population based on contract type
- Three-tier expiry alert system (urgent ≤7 days, critical ≤15 days, soon ≤30 days)
- Contract renewal and termination workflows with audit trails
- Bulk contract operations support

### Document Management System  
- `app/Models/Document.php`: Document model with versioning, tagging, expiry tracking
- `app/Models/DocumentVersion.php`: Version history management
- `app/Models/Tag.php`: Document categorization and tagging
- `app/Http/Controllers/DocumentController.php`: Complete document CRUD with file handling
- `app/Policies/DocumentPolicy.php`: Role-based document access control
- `resources/views/documents/`: Professional document management interface
- Private file storage with secure download routes

### Payroll Structure System
- `app/Models/SalaryComponent.php`: Earnings, deductions, and info components
- `app/Models/SalaryStructure.php`: Employee salary structures with effective periods
- `app/Models/SalaryStructureComponent.php`: Component-structure relationships
- `app/Http/Controllers/SalaryComponentController.php`: Component management with seeding
- `app/Policies/SalaryComponentPolicy.php`: Role-based salary data access
- `resources/views/salary-components/`: Component management interface

### Master Data Models
- `app/Models/Department.php`: Bilingual departments with employee statistics
- `app/Models/Position.php`: Lawyer/Admin positions with hierarchy and overtime eligibility  
- `app/Models/EmploymentType.php`: Contract types with benefit eligibility rules

### Core Infrastructure
- `app/Console/Commands/CreateMissingTables.php`: Database repair command
- `resources/views/layouts/app.blade.php`: Professional branded layout with navigation
- `resources/views/dashboard.blade.php`: HRMS dashboard with statistics
- `routes/web.php`: Complete route registration for all modules
- Comprehensive bilingual localization (English/Arabic) with RTL support
- Private file storage system with secure access controls

### Localization System
- `resources/lang/en/hrms.php`: Complete English HRMS translations
- `resources/lang/ar/hrms.php`: Complete Arabic translations with RTL support
- `resources/lang/ar/auth.php`: Arabic authentication messages
- `resources/lang/ar/validation.php`: Complete Arabic validation messages
- Full employee, contract, document, and payroll terminology coverage
- Activity log translations for audit trail localization

## Development Priorities

✅ **Phase 1 (Foundation) - COMPLETE**: Users/RBAC, employees, contracts, documents, payroll structure setup
✅ **Phase 2 (Operations) - PARTIALLY COMPLETE**: Employee photos ✅, contract management ✅, payroll runs pending
🔄 **Phase 3 (Attendance)**: API integration, rollup calculations, time tracking
🔄 **Phase 4 (Compliance)**: Advanced reporting, audit trails, automated notifications
🔄 **Phase 5 (Self-Service)**: Employee portal, mobile-responsive features
🔄 **Phase 6 (Hardening)**: Security enhancements, API endpoints, performance optimization

## Available Application URLs

**Development Server**: `http://hrms.local/` or `http://localhost:8000/`

### Main Application Routes
- **Dashboard**: `/` - HRMS dashboard with statistics
- **Login**: `/login` - User authentication
- **Register**: `/register` - New user registration  
- **Profile**: `/profile` - User profile management

### Employee Management
- **Employees List**: `/employees` - Employee directory with search/filters
- **Add Employee**: `/employees/create` - New employee registration
- **Employee Details**: `/employees/{id}` - Individual employee view
- **Employee Statistics**: `/employees-statistics` - Employee analytics API

### Contract Management
- **Contracts List**: `/contracts` - Contract directory with advanced filtering
- **Add Contract**: `/contracts/create` - New contract creation
- **Contract Details**: `/contracts/{id}` - Individual contract view with terms
- **Contract Renewal**: `/contracts/{id}/renew` - Contract renewal workflow
- **Contract Termination**: `/contracts/{id}/terminate` - Contract termination
- **Contracts Requiring Attention**: `/contracts-requiring-attention` - Expiry alerts API

### Document Management  
- **Documents List**: `/documents` - Document library with filters
- **Upload Document**: `/documents/create` - File upload interface
- **Document Details**: `/documents/{id}` - Document view with versions
- **Download Document**: `/documents/{id}/download` - Secure file download
- **Employee Contracts**: `/documents/employee-contracts` - Contract documents API

### Payroll Management
- **Salary Components**: `/salary-components` - Component management
- **Create Component**: `/salary-components/create` - New component setup
- **Seed Components**: `/salary-components/seed-predefined` - Default components

### System Administration
- **Language Switch**: `/language/{locale}` - EN/AR language toggle

## Branding & UI

- **Colors**: Gold (#c6a44a), Dark Green (#2e4029), Cream (#f9f5e6)
- **Typography**: Serif for titles, sans-serif for body text
- **Layout**: Professional corporate design matching firm's website
- **Bilingual**: English/Arabic toggle with proper RTL support