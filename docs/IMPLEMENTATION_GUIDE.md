# HRMS Implementation Guide

**Comprehensive Guide to Sarie Eldin & Partners HRMS System**  
**Version**: 1.0  
**Last Updated**: December 2024

## 📋 Table of Contents

1. [System Overview](#system-overview)
2. [User Roles & Permissions](#user-roles--permissions)
3. [Employee Management](#employee-management)
4. [Contract Management](#contract-management)
5. [Document Management](#document-management)
6. [Payroll Structure](#payroll-structure)
7. [System Administration](#system-administration)
8. [API Endpoints](#api-endpoints)
9. [Troubleshooting](#troubleshooting)

---

## 🏢 System Overview

The HRMS (Human Resource Management System) is a comprehensive web application built for Sarie Eldin & Partners Legal Advisors to manage their ~50 employees, contracts, documents, and payroll operations.

### Key Features
- **Employee Lifecycle Management**: From hiring to termination
- **Contract Management**: Complete contract lifecycle with expiry tracking
- **Document Management**: Secure document storage with versioning
- **Payroll Structure**: Flexible salary component management
- **Bilingual Interface**: English and Arabic with RTL support
- **Role-Based Security**: Granular access control for different user types

### System Architecture
- **Backend**: Laravel 10 with PHP 8.4
- **Database**: MySQL with UTF8MB4 charset
- **Storage**: Private file system for sensitive documents
- **Authentication**: Role-based access control with Spatie Permission
- **Localization**: Complete English/Arabic translation support

---

## 👥 User Roles & Permissions

### 1. HR Admin Manager
**Access Level**: Complete system access
- ✅ All employee operations (create, edit, delete, terminate, reactivate)
- ✅ All contract operations (create, edit, delete, renew, terminate)
- ✅ All document operations (upload, edit, delete, download)
- ✅ Full payroll access including Net/Gross salary amounts
- ✅ User management and role assignments
- ✅ System administration functions
- ✅ Export and reporting capabilities

### 2. HR Coordinator
**Access Level**: Employee/Contract/Document management (no Net/Gross salary)
- ✅ Employee operations (create, edit, view, terminate, reactivate)
- ✅ Contract operations (create, edit, view, renew, terminate)
- ✅ Document operations (upload, edit, view, download)
- ❌ Cannot view Net/Gross salary amounts
- ❌ Cannot delete employees or contracts
- ❌ Limited payroll component access

### 3. Accounting Manager
**Access Level**: Full payroll access including Net/Gross amounts
- ✅ View all employees and contracts
- ✅ Full payroll access including Net/Gross salary figures
- ✅ Salary component management
- ✅ View and download payroll-related documents
- ✅ Export payroll reports
- ❌ Cannot create/edit/delete employees or contracts

### 4. Accountant
**Access Level**: Financial operations (no Net/Gross salary visibility)
- ✅ View employees and contracts
- ✅ Limited payroll access (no Net/Gross amounts)
- ✅ View financial documents
- ❌ Cannot view Net/Gross salary amounts
- ❌ Cannot create/edit employees or contracts
- ❌ Limited system access

### 5. IT Admin
**Access Level**: System administration only
- ✅ System maintenance and configuration
- ✅ User account management
- ✅ Technical troubleshooting
- ❌ Limited access to HR/payroll data
- ❌ Cannot perform HR operations

### 6. Employee
**Access Level**: Self-service portal only
- ✅ View own employee profile
- ✅ View own contracts
- ✅ View own documents
- ✅ Update personal information (limited)
- ❌ Cannot view other employees' data
- ❌ Cannot access administrative functions

---

## 👤 Employee Management

### Employee CRUD Operations

#### Creating a New Employee
1. **Navigate**: `/employees/create`
2. **Required Fields**:
   - First Name, Last Name
   - Department, Position, Employment Type
   - Manager (optional)
3. **Optional Fields**:
   - Arabic Name, Email, Phone
   - Hire Date, National ID
   - Salary Visibility Flag

#### Employee Search & Filtering
**Available Filters**:
- **Text Search**: Name, email, or employee code
- **Status**: Active, Inactive, Terminated, On Leave
- **Department**: Filter by specific department
- **Position**: Filter by job position
- **Employment Type**: Full-time, Part-time, Contract, etc.
- **Manager**: Filter by reporting manager
- **Hire Date Range**: From/to date filtering
- **Sorting**: By name, code, hire date (ascending/descending)

#### Employee Photo Management
- **Upload**: Support for JPEG, PNG, JPG, GIF (max 5MB)
- **Storage**: Secure private storage with access control
- **Display**: Automatic thumbnail generation
- **Permissions**: Only authorized users can upload/delete photos

#### Employee Termination & Reactivation
- **Termination**: 
  - Requires termination reason and date
  - Changes status to "terminated"
  - Maintains employee record for audit purposes
- **Reactivation**: 
  - Only available for terminated employees
  - Restores status to "active"
  - Requires proper authorization

### Employee Statistics
**Available Metrics**:
- Total employees count
- Active vs inactive employees
- New hires by month
- Distribution by department
- Distribution by position
- Distribution by employment type

---

## 📄 Contract Management

### Contract Types Supported
1. **Permanent**: No end date, standard benefits
2. **Fixed Term**: Specific end date, renewable
3. **Probation**: 90-day trial period, limited benefits
4. **Internship**: Training period, minimal benefits
5. **Consultancy**: Project-based, hourly rate

### Contract Creation Process

#### Smart Contract Terms
The system automatically populates contract terms based on type:

**Permanent Contract Defaults**:
- Notice Period: 30 days
- Working Hours: 8 hours/day
- Annual Leave: 21 days
- Health Insurance: Yes
- End of Service: Eligible

**Probation Contract Defaults**:
- Notice Period: 7 days
- Working Hours: 8 hours/day
- Annual Leave: 0 days
- Health Insurance: No
- Probation Period: 90 days

#### Contract Terms Management
**Configurable Terms**:
- Confidentiality Agreement (Yes/No)
- Non-Compete Agreement (Yes/No)
- Notice Period (Days)
- Working Hours per Day
- Probation Period (Days)
- Annual Leave Days
- Sick Leave Days
- Health Insurance Coverage

### Contract Expiry Management

#### Three-Tier Alert System
1. **Urgent (≤7 days)**: Red badge, immediate attention required
2. **Critical (≤15 days)**: Yellow badge, action needed soon
3. **Soon (≤30 days)**: Blue badge, planning required

#### Contract Renewal Process
- **Eligibility**: Fixed-term and probation contracts
- **Workflow**: Automated renewal with new terms
- **History**: Maintains link between old and new contracts
- **Approval**: Requires proper authorization

#### Contract Termination
- **Manual Termination**: With reason and effective date
- **Automatic Expiry**: System detects expired contracts
- **Documentation**: Complete audit trail maintained

### Contract Statistics
- Total contracts by type
- Active vs expired contracts
- Contracts expiring by time period
- Renewal success rates
- Contract duration analysis

---

## 📁 Document Management

### Document Types
**Predefined Categories**:
- National ID documents
- Bar License certificates
- Contract documents
- Employment proof letters
- HR correspondence
- Payroll documents

### Document Upload & Management

#### File Upload Process
1. **File Validation**: Type and size restrictions (10MB max)
2. **Metadata Capture**: Original filename, MIME type, checksum
3. **Private Storage**: Secure storage outside web root
4. **Access Control**: Role-based download permissions

#### Document Versioning
- **Version History**: Complete version tracking
- **Rollback Capability**: Restore previous versions
- **Change Tracking**: Who uploaded, when, and why
- **Storage Optimization**: Efficient file storage management

#### Document Expiry Tracking
- **Expiry Dates**: Optional expiry date setting
- **Alert System**: Automated expiry notifications
- **Renewal Reminders**: Proactive document renewal
- **Compliance Tracking**: Regulatory compliance monitoring

### Document Security

#### Access Control
- **Role-Based Access**: Permissions by user role
- **Employee Visibility**: Employees see only their documents
- **Private Documents**: HR-only access for sensitive files
- **Audit Trail**: Complete access logging

#### Watermarking (Ready for Implementation)
- **Confidential Marking**: "Confidential - HR Use Only"
- **User Identification**: Downloads marked with user info
- **Timestamp**: Download date and time stamping
- **Tracking**: Download activity monitoring

---

## 💰 Payroll Structure

### Salary Components

#### Component Types
1. **Earnings**: Basic salary, allowances, bonuses
2. **Deductions**: Taxes, insurance, loan payments
3. **Information Only**: Non-monetary benefits, notes

#### Calculation Modes
1. **Fixed Amount**: Static monetary value
2. **Formula-Based**: Dynamic calculations using expressions
3. **Variable Net-Based**: Percentage of net salary

#### Component Management
- **Predefined Components**: Standard components for quick setup
- **Custom Components**: Organization-specific components
- **Priority Ordering**: Calculation sequence management
- **Visibility Control**: Role-based component access

### Salary Structure Assignment
- **Employee Structures**: Individual salary configurations
- **Effective Periods**: Date-based salary changes
- **Component Mapping**: Flexible component assignments
- **Override Capabilities**: Individual employee adjustments

### Payroll Security
**Net/Gross Salary Restrictions**:
- HR Coordinators: Cannot view actual salary amounts
- Accountants: Cannot view actual salary amounts
- Accounting Managers: Full salary access
- HR Admin Managers: Full salary access

---

## ⚙️ System Administration

### User Management

#### User Account Creation
1. **Registration Process**: Admin-created or self-registration
2. **Role Assignment**: Mandatory role selection
3. **Employee Linking**: Optional employee profile connection
4. **Email Verification**: Account verification process

#### Role Management
- **Spatie Permission**: Advanced role and permission system
- **Guard Configuration**: Web-based authentication
- **Role Hierarchy**: Structured access levels
- **Permission Inheritance**: Role-based permission assignment

### Language & Localization

#### Bilingual Support
- **Languages**: English (default) and Arabic
- **RTL Support**: Right-to-left layout for Arabic
- **Translation Coverage**: 500+ translation keys
- **Language Switching**: Real-time language toggle

#### Translation Management
- **English**: `resources/lang/en/hrms.php`
- **Arabic**: `resources/lang/ar/hrms.php`
- **Activity Logs**: Bilingual audit trail messages
- **Form Validation**: Localized error messages

### File Storage Management

#### Private Disk Configuration
```php
'private' => [
    'driver' => 'local',
    'root' => storage_path('app/private'),
    'visibility' => 'private',
],
```

#### Directory Structure
```
storage/app/private/
├── employee_photos/     # Employee profile photos
└── documents/          # Document uploads
```

#### Security Measures
- **Access Control**: Role-based file access
- **Signed URLs**: Temporary secure download links
- **File Validation**: Type and size restrictions
- **Virus Scanning**: Ready for integration

### Activity Logging

#### Audit Trail Features
- **Complete Logging**: All CRUD operations tracked
- **User Attribution**: Who performed each action
- **Timestamp Tracking**: When actions occurred
- **Property Changes**: What data was modified
- **Bilingual Messages**: Localized activity descriptions

#### Log Categories
- Employee operations
- Contract lifecycle events
- Document management actions
- User authentication events
- System administration activities

---

## 🔌 API Endpoints

### Employee Management APIs
```
GET    /employees                     # List employees with filtering
POST   /employees                     # Create new employee
GET    /employees/{id}                # Get employee details
PUT    /employees/{id}                # Update employee
DELETE /employees/{id}                # Delete employee
POST   /employees/{id}/terminate      # Terminate employee
POST   /employees/{id}/reactivate     # Reactivate employee
GET    /employees-statistics          # Employee statistics
GET    /employees-search              # Employee search API
```

### Contract Management APIs
```
GET    /contracts                     # List contracts with filtering
POST   /contracts                     # Create new contract
GET    /contracts/{id}                # Get contract details
PUT    /contracts/{id}                # Update contract
DELETE /contracts/{id}                # Delete contract
POST   /contracts/{id}/renew          # Renew contract
POST   /contracts/{id}/terminate      # Terminate contract
GET    /contracts-requiring-attention # Expiry alerts
POST   /contracts-bulk-operation      # Bulk operations
```

### Document Management APIs
```
GET    /documents                     # List documents with filtering
POST   /documents                     # Upload new document
GET    /documents/{id}                # Get document details
PUT    /documents/{id}                # Update document
DELETE /documents/{id}                # Delete document
GET    /documents/{id}/download       # Download document
POST   /documents/{id}/upload-version # Upload new version
```

### System APIs
```
GET    /language/{locale}             # Switch language
GET    /profile                       # User profile
PUT    /profile                       # Update profile
POST   /profile/link-employee         # Link employee account
```

---

## 🔧 Troubleshooting

### Common Issues & Solutions

#### Translation Key Errors
**Error**: `htmlspecialchars(): Argument #1 ($string) must be of type string, array given`
**Solution**: 
```bash
php artisan config:clear
php artisan view:clear
php artisan cache:clear
```

#### File Upload Issues
**Error**: "Disk [private] does not have a configured driver"
**Solution**: Verify `config/filesystems.php` has private disk configuration

#### Permission Denied Errors  
**Error**: "This action is unauthorized"
**Solution**: Check user role assignments and policy configurations

#### Database Connection Issues
**Error**: Database connection failed
**Solution**: Verify `.env` database configuration:
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=sep_hrms
DB_USERNAME=root
DB_PASSWORD=1234
```

### Cache Management
```bash
# Clear all caches
php artisan config:clear
php artisan view:clear
php artisan cache:clear
php artisan route:clear

# Rebuild caches
php artisan config:cache
php artisan view:cache
php artisan route:cache
```

### Log Monitoring
**Application Logs**: `storage/logs/laravel.log`
**Activity Logs**: Database table `activity_log`
**Error Tracking**: Laravel error reporting system

### Performance Optimization
**Database Indexing**: Ensure proper indexes on search columns
**File Storage**: Monitor disk space in `storage/app/private/`
**Memory Usage**: Monitor PHP memory limits for large file uploads

---

## 📞 Support & Maintenance

### Regular Maintenance Tasks
1. **Database Backups**: Regular automated backups
2. **File Backups**: Include `storage/app/private/` directory
3. **Log Rotation**: Manage application log files
4. **Cache Optimization**: Regular cache rebuilding
5. **Security Updates**: Keep Laravel and dependencies updated

### Development Commands
```bash
# Development server
php artisan serve --host=0.0.0.0 --port=8000

# Database operations
php artisan migrate:status
php artisan db:seed

# Maintenance mode
php artisan down
php artisan up
```

### Production Deployment
1. **Environment Setup**: Configure production `.env`
2. **Dependency Installation**: `composer install --no-dev`
3. **Asset Compilation**: `npm run build`
4. **Database Migration**: `php artisan migrate`
5. **Cache Building**: `php artisan config:cache`
6. **SSL Configuration**: HTTPS setup
7. **Email Configuration**: SMTP settings for notifications

---

**This implementation guide provides comprehensive documentation for using and maintaining the HRMS system. For technical support or feature requests, refer to the development team or system administrator.**