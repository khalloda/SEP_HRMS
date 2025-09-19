# AI Coding Agent Instructions for SEP_HRMS

## Project Overview
This is a comprehensive Human Resource Management System (HRMS) for Sarie Eldin & Partners Legal Advisors, a law firm with ~50 employees. The system is built with Laravel 10/PHP 8.4 and targets GoDaddy cPanel hosting.

## Core Architecture Patterns

### Data Model Architecture
- `Employee` is the central model with extensive relationships
- Strict role-based permissions using Spatie Permission package
- Field-level encryption for sensitive data (national IDs, salaries)
- Fulltext search with basic search fallback on critical models

### Services Layer
- Complex business logic encapsulated in dedicated services
  Example: `PayrollCalculationService` for formula evaluation
- Activity logging through `AuditTrailService`
- File storage handled through Laravel's private disk

### Integration Architecture 
- ZKTeco attendance devices integrated via `/api/attendance/push`
- HMAC-SHA256 signature authentication for device communication
- Email notifications for critical alerts (expiry, weekly digest)
- API-first design pattern for future integrations

## Key Workflows

### Development Setup
```bash
# Initial setup
composer install
cp .env.example .env
php artisan key:generate

# Database setup
# Configure DB_* in .env first:
# DB_DATABASE=sep_hrms
# DB_USERNAME=root  
# DB_PASSWORD=1234
php artisan migrate
php artisan db:seed --class=DefaultUsersSeeder

# Development server
php artisan serve --host=0.0.0.0 --port=8000
```

### Deployment Workflow
```bash
# 1. Build for production
composer install --no-dev --optimize-autoloader
php artisan config:cache
php artisan route:cache
php artisan view:cache

# 2. Create deployment package including /vendor
# 3. Upload to GoDaddy via cPanel File Manager
# 4. Extract to /home/user/app
# 5. Configure .env via File Manager
# 6. Import database via phpMyAdmin
```

## Critical Conventions

### Code Organization
- Models in `app/Models/` with relationships and scopes
- Business logic in `app/Services/`
- Controllers in `app/Http/Controllers/` 
- Policies in `app/Policies/` for authorization
- Views in `resources/views/` with Bootstrap 5 and corporate styling
- Database migrations in `database/migrations/`

### Required Patterns
1. Always use policies for authorization:
```php
public function update(User $user, Employee $employee)
{
    return $user->hasAnyRole(['HR_Admin_Manager', 'HR_Coordinator']) ||
           ($user->employee && $user->employee->id === $employee->id);
}
```

2. Enable activity logging on models:
```php
use LogsActivity;

public function getActivitylogOptions(): LogOptions
{
    return LogOptions::defaults()
        ->logOnly(['status', 'department_id', ...])
        ->logOnlyDirty();
}
```

3. Implement role-based salary visibility:
```php
public function canViewSalaryInformation(User $user): bool 
{
    return $user->hasAnyRole(['HR_Admin_Manager', 'Accounting_Manager']);
}
```

### Data Flows
1. Employee Updates:
   - Controller validates input
   - Policy checks authorization
   - Model updates with activity logging
   - AuditTrailService records critical changes

2. Salary Calculations:
   - PayrollRun initiates calculation
   - SalaryComponent evaluates formulas
   - Payslip generated with role visibility
   - PDF generation with watermarking

3. Document Management:
   - Secure upload to private disk
   - Version history tracked
   - Access controlled by policy
   - Signed URLs for downloads

## Cross-Component Integration

### Security Model
- Six core roles with strict permission hierarchy
- Field-level encryption for sensitive data
- Private file storage with signed URLs
- Two-step approval for critical operations

### Localization
- Bilingual system (English/Arabic)
- RTL support for Arabic
- Translation files in `resources/lang/`
- Dynamic language switching

### Test Coverage
- Unit tests in `tests/Unit/`
- Feature tests in `tests/Feature/`
- Database seeding for test data
- Test environment configuration in `phpunit.xml`

## Project State (September 2025)
- Phase 4 complete (Documents & Compliance)
- All core systems operational
- Database schema fully deployed (40+ tables)
- ZKTeco integration active
- Weekly digest emails running