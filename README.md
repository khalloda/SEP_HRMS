# Sarie Eldin & Partners HRMS

[![Laravel](https://img.shields.io/badge/Laravel-10.49.0-red.svg)](https://laravel.com)
[![PHP](https://img.shields.io/badge/PHP-8.4-blue.svg)](https://www.php.net)
[![MySQL](https://img.shields.io/badge/MySQL-8.0-orange.svg)](https://www.mysql.com)
[![Bootstrap](https://img.shields.io/badge/Bootstrap-5.3-purple.svg)](https://getbootstrap.com)

A comprehensive Human Resource Management System (HRMS) built for **Sarie Eldin & Partners Legal Advisors**, a law firm managing ~50 employees. This system provides complete employee lifecycle management, document handling, payroll structure setup, and role-based access control with bilingual Arabic/English support.

## 🚀 Features

### ✅ **Phase 1 - Foundation Complete**

#### 🔐 **Authentication & User Management**
- Complete user authentication (login, register, logout, profile management)
- Role-Based Access Control (RBAC) with 6 specialized roles
- User-employee linking system
- Activity logging for full audit trails
- Session management with "Remember Me" functionality

#### 👥 **Employee Management**
- Full employee CRUD operations with advanced filtering
- Employee search with fulltext and basic search fallback
- Employee statistics and analytics dashboard
- Export functionality (Excel, PDF, CSV ready)
- Employee termination and reactivation workflows
- Manager hierarchies and reporting structures
- Employee directory with professional responsive interface

#### 📄 **Document Management**
- Complete document upload and management system
- Document versioning with history tracking
- Document tagging and categorization
- Document expiry tracking with alerts
- Role-based document visibility and access control
- Watermarking support for confidential documents
- File validation (10MB limit, multiple formats)
- Secure private file storage with signed downloads

#### 💰 **Payroll Structure Setup**
- Salary component management (earnings, deductions, information)
- Predefined salary components with one-click seeding
- Multiple calculation modes (fixed, formula-based, variable)
- Role-based salary visibility (Net/Gross restrictions)
- Priority ordering for payroll calculations
- Component dependencies and relationships

### 🌐 **Core Features**
- **Bilingual Support**: English/Arabic with RTL layout
- **Professional UI**: Corporate Sarie Eldin branding with gold/green theme
- **Responsive Design**: Bootstrap 5 with mobile optimization
- **Security**: Field-level encryption, role-based permissions
- **Audit Trails**: Complete activity logging for compliance

## 🛠️ Technology Stack

- **Backend**: Laravel 10.49.0 with PHP 8.4
- **Database**: MySQL 8.0 with utf8mb4 (Arabic support)
- **Frontend**: Bootstrap 5.3 with custom Sarie Eldin styling
- **Authentication**: Laravel Sanctum with Spatie Permission
- **File Storage**: Laravel private disk for secure document handling
- **Localization**: Laravel multilingual with Arabic RTL support
- **Activity Logging**: Spatie ActivityLog for audit compliance

## 📋 Installation

### Prerequisites
- PHP 8.4+
- MySQL 8.0+
- Composer
- Web server (Apache/Nginx)

### Setup Instructions

1. **Clone the repository**
   ```bash
   git clone [repository-url]
   cd SEP_HRMS
   ```

2. **Install dependencies**
   ```bash
   composer install --no-dev --optimize-autoloader
   ```

3. **Environment configuration**
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```

4. **Database setup**
   ```bash
   # Configure database in .env file
   DB_CONNECTION=mysql
   DB_HOST=localhost
   DB_PORT=3306
   DB_DATABASE=sep_hrms
   DB_USERNAME=root
   DB_PASSWORD=1234
   
   # Import the complete schema
   # Use phpMyAdmin or MySQL CLI to import docs/HRMS_Schema.sql
   ```

5. **Initialize application**
   ```bash
   # Fix any missing database tables
   php artisan hrms:create-missing-tables
   
   # Create default users and roles
   php artisan db:seed --class=DefaultUsersSeeder
   
   # Create storage links
   php artisan storage:link
   ```

6. **Start development server**
   ```bash
   php artisan serve --host=0.0.0.0 --port=8000
   ```

7. **Access the application**
   - URL: `http://localhost:8000`
   - Default login: `hr@sarieldin.com` / `password123`

## 👤 Default User Accounts

The system comes with pre-configured user accounts for testing:

| Role | Email | Password | Permissions |
|------|-------|----------|-------------|
| HR Admin Manager | `hr@sarieldin.com` | `password123` | Full system access |
| Accounting Manager | `accounting@sarieldin.com` | `password123` | Full payroll access |
| IT Administrator | `it@sarieldin.com` | `password123` | System administration |
| HR Coordinator | `hrcoord@sarieldin.com` | `password123` | Employee/document management |
| Demo Employee | `demo@sarieldin.com` | `password123` | Self-service portal |

⚠️ **Important**: Change these passwords in production!

## 🔗 Application URLs

### Main Application
- **Dashboard**: `/` - HRMS dashboard with statistics
- **Login**: `/login` - User authentication
- **Profile**: `/profile` - User profile management

### Employee Management
- **Employee Directory**: `/employees` - Complete employee listing
- **Add Employee**: `/employees/create` - New employee registration
- **Employee Details**: `/employees/{id}` - Individual employee profile

### Document Management
- **Document Library**: `/documents` - Document management interface
- **Upload Document**: `/documents/create` - File upload system
- **Document Details**: `/documents/{id}` - Document viewer with versions

### Payroll Management
- **Salary Components**: `/salary-components` - Component management
- **Create Component**: `/salary-components/create` - New component setup

## 🎨 Design & Branding

The application features professional corporate design matching Sarie Eldin & Partners branding:

- **Primary Colors**: Gold (#c6a44a), Dark Green (#2e4029), Cream (#f9f5e6)
- **Typography**: Professional serif/sans-serif combination
- **Layout**: Clean, responsive Bootstrap 5 interface
- **Languages**: English and Arabic with proper RTL support

## 🔒 Security Features

- **Role-Based Access Control**: 6 specialized roles with granular permissions
- **Data Encryption**: Sensitive fields (National ID, salary) encrypted at rest
- **Document Security**: Private file storage with signed URLs
- **Activity Logging**: Complete audit trail for compliance
- **Session Security**: Secure authentication with configurable timeouts

## 🛡️ User Roles & Permissions

| Role | Employee Management | Document Access | Payroll Access | System Admin |
|------|-------------------|-----------------|----------------|--------------|
| **HR Admin Manager** | Full CRUD | All documents | Full including Net/Gross | Yes |
| **Accounting Manager** | View only | Financial docs | Full including Net/Gross | Limited |
| **HR Coordinator** | Full CRUD | All except payslips | No Net/Gross access | No |
| **Accountant** | View only | Financial docs | No Net/Gross access | No |
| **Employee** | Own record only | Own documents | Own payslips | No |
| **IT Admin** | Limited | System docs | No access | Full |

## 📁 Project Structure

```
SEP_HRMS/
├── app/
│   ├── Console/Commands/          # Custom Artisan commands
│   ├── Http/Controllers/          # Application controllers
│   │   ├── Auth/                 # Authentication controllers
│   │   ├── EmployeeController.php # Employee management
│   │   ├── DocumentController.php # Document management
│   │   └── SalaryComponentController.php # Payroll setup
│   ├── Models/                   # Eloquent models
│   │   ├── User.php             # User authentication
│   │   ├── Employee.php         # Employee data
│   │   ├── Document.php         # Document management
│   │   └── SalaryComponent.php  # Payroll components
│   └── Policies/                # Authorization policies
├── database/
│   ├── migrations/              # Database migrations
│   └── seeders/                 # Database seeders
├── resources/
│   ├── lang/                    # Localization files (EN/AR)
│   └── views/                   # Blade templates
├── routes/                      # Application routes
├── storage/                     # File storage
└── docs/                       # Project documentation
```

## 🔄 Development Workflow

### Available Artisan Commands

```bash
# Development server
php artisan serve --host=0.0.0.0 --port=8000

# Database management
php artisan migrate:status                    # Check migrations
php artisan hrms:create-missing-tables       # Fix missing tables
php artisan db:seed --class=DefaultUsersSeeder # Create default users

# Application maintenance
php artisan config:clear                     # Clear config cache
php artisan route:clear                      # Clear route cache
php artisan view:clear                       # Clear view cache
php artisan cache:clear                      # Clear application cache

# View system information
php artisan route:list                       # List all routes
```

## 🚀 Deployment

### GoDaddy cPanel Deployment

This application is designed for deployment on GoDaddy cPanel hosting:

1. **Build for production**
   ```bash
   composer install --no-dev --optimize-autoloader
   php artisan config:cache
   php artisan route:cache
   php artisan view:cache
   ```

2. **Create deployment package**
   ```bash
   # Create ZIP file including /vendor directory
   zip -r hrms-production.zip . -x "node_modules/*" ".git/*"
   ```

3. **cPanel deployment**
   - Upload ZIP file via cPanel File Manager
   - Extract to `/home/user/app` directory
   - Configure `.env` via File Manager
   - Import database via phpMyAdmin
   - Set document root to `/public`

4. **Post-deployment**
   - Configure cron jobs for scheduled tasks
   - Set up SSL certificate
   - Configure email settings

## 📋 Roadmap

### 🔄 Phase 2 - Operations (Next)
- [ ] Employee photo management with media library
- [ ] Salary structure assignment to employees  
- [ ] Payroll run processing and payslip generation
- [ ] Contract lifecycle management
- [ ] Advanced dashboard analytics

### 🔄 Phase 3 - Integration
- [ ] ZKTeco attendance device integration
- [ ] Automated email notifications
- [ ] Advanced reporting system
- [ ] Mobile-responsive enhancements

### 🔄 Phase 4 - Advanced Features
- [ ] API endpoints for mobile app
- [ ] Advanced security features
- [ ] Performance optimization
- [ ] Third-party integrations (Zoho Books)

## 📞 Support & Documentation

- **Main Documentation**: `CLAUDE.md` - Complete development guide
- **Database Schema**: `docs/HRMS_Schema.sql` - Complete database structure
- **Requirements**: `docs/HRMS_PRD_SarieEldin.md` - Product requirements
- **Architecture**: `docs/HRMS_ERD.md` - Entity relationship diagram

## 📄 License

This project is proprietary software developed for Sarie Eldin & Partners Legal Advisors.

---

**Sarie Eldin & Partners HRMS v1.0** - *Comprehensive Human Resource Management Solution*
