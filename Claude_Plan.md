# Claude Implementation Plan for HRMS System

This document provides a detailed technical implementation roadmap specifically designed for Claude Code development workflow. It complements the business-focused `docs/HRMS-Plan.md` with technical specifics.

## Prerequisites & Environment Setup

### Phase 0: Development Environment (Day 1)
1. **Initialize Laravel Project**
   ```bash
   composer create-project laravel/laravel . "^10.0"
   php artisan --version  # Confirm Laravel 10+
   ```

2. **Configure Localization**
   ```bash
   php artisan lang:publish
   # Create resources/lang/ar/ directory structure
   # Configure config/app.php for 'ar' locale
   ```

3. **Install Required Packages**
   ```bash
   composer require spatie/laravel-permission
   composer require mpdf/mpdf
   composer require spatie/laravel-activitylog
   composer require spatie/laravel-medialibrary
   composer require maatwebsite/excel
   ```

4. **Database Configuration**
   - Set MySQL charset to utf8mb4 in config/database.php
   - Configure collation for Arabic text support

## Phase 1: Foundation Architecture (Week 1-2)

### 1.1 Authentication & Authorization
**Files to Create:**
- `database/migrations/2024_create_roles_permissions_tables.php`
- `database/seeders/RolePermissionSeeder.php`
- `app/Models/User.php` (extend with HasRoles trait)
- `app/Models/Role.php`
- `app/Models/Permission.php`

**Key Implementation Details:**
```php
// User model relationships
public function employee() { return $this->belongsTo(Employee::class); }

// Role-based middleware
Route::middleware(['role:HR_Admin_Manager'])->group(function() {
    // HR admin routes
});
```

### 1.2 Core Master Data
**Models & Migrations:**
- `app/Models/Department.php` + migration
- `app/Models/Position.php` + migration  
- `app/Models/EmploymentType.php` + migration
- `app/Models/Employee.php` + migration with fulltext index

**Employee Model Specifics:**
```php
// Encrypted attributes
protected $casts = [
    'national_id' => 'encrypted',
    'salary_visibility_flag' => 'boolean'
];

// Fulltext search scope
public function scopeSearch($query, $term) {
    return $query->whereRaw(
        "MATCH(first_name, last_name, arabic_name, email, code) AGAINST(? IN NATURAL LANGUAGE MODE)", 
        [$term]
    );
}
```

### 1.3 File Storage & Security
**Implementation Pattern:**
```php
// config/filesystems.php - Private disk configuration
'private' => [
    'driver' => 'local',
    'root' => storage_path('app/private'),
    'visibility' => 'private'
],

// Signed route for document downloads
Route::get('/documents/{document}/download', [DocumentController::class, 'download'])
    ->name('documents.download')
    ->middleware(['signed', 'auth']);
```

### 1.4 Template Engine & PDF Generation
**Files to Create:**
- `app/Models/Template.php`
- `app/Services/PdfGeneratorService.php`
- `app/Services/TemplateRenderService.php`

**mPDF Configuration for Arabic:**
```php
$mpdf = new \Mpdf\Mpdf([
    'mode' => 'utf-8',
    'format' => 'A4',
    'orientation' => 'P',
    'autoArabic' => true,
    'autoLangToFont' => true
]);
```

## Phase 2: Payroll System (Week 3-4)

### 2.1 Salary Structure Architecture
**Complex Models Required:**
- `app/Models/SalaryComponent.php`
- `app/Models/SalaryStructure.php`
- `app/Models/SalaryStructureComponent.php`
- `app/Services/SalaryCalculationService.php`

**Calculation Engine Logic:**
```php
class SalaryCalculationService {
    public function calculatePayslip(Employee $employee, $period) {
        // 1. Load active salary structure
        // 2. Process components in dependency order (topological sort)
        // 3. Apply formulas with variable substitution
        // 4. Respect role-based visibility rules
    }
    
    private function evaluateFormula($expression, $variables) {
        // Safe math expression evaluation
        // Support for: BASIC * 0.15, GROSS - DEDUCTIONS, etc.
    }
}
```

### 2.2 Payroll Run Workflow
**State Machine Implementation:**
```php
// PayrollRun statuses: draft → locked → posted
class PayrollRun extends Model {
    public function lock() {
        DB::transaction(function() {
            $this->generatePayslips();
            $this->update(['status' => 'locked']);
        });
    }
    
    public function requiresApproval() {
        return ApprovalRequest::create([
            'entity_type' => 'PayrollRun',
            'entity_id' => $this->id,
            'action' => 'post_payroll'
        ]);
    }
}
```

### 2.3 Two-Step Approval System
**Generic Approval Engine:**
- `app/Models/ApprovalRequest.php`
- `app/Models/ApprovalEvent.php`
- `app/Services/ApprovalService.php`
- `app/Notifications/ApprovalRequiredNotification.php`

## Phase 3: Attendance Integration (Week 5)

### 3.1 API Endpoint for ZKTeco
**Route & Controller:**
```php
// routes/api.php
Route::post('/attendance/push', [AttendanceController::class, 'push'])
    ->middleware('api.token');

// AttendanceController
public function push(Request $request) {
    // 1. Validate HMAC signature
    // 2. Parse attendance data
    // 3. Store in attendance_logs table
    // 4. Trigger rollup calculation
}
```

### 3.2 Daily Rollup Job
```php
class DailyAttendanceRollup extends Command {
    public function handle() {
        // Process previous day's punches
        // Calculate: late arrivals, early departures, overtime
        // Only calculate OT for eligible positions (Messengers, Office Boys)
    }
}
```

## Phase 4: Document Management & Compliance (Week 6)

### 4.1 Document Versioning System
**Implementation Strategy:**
```php
class Document extends Model {
    public function versions() {
        return $this->hasMany(DocumentVersion::class);
    }
    
    public function createNewVersion($file) {
        DB::transaction(function() use ($file) {
            // Archive current version
            $this->versions()->create([
                'version_no' => $this->version_current,
                'path' => $this->path,
                'checksum' => $this->checksum
            ]);
            
            // Update current document
            $this->increment('version_current');
            $this->update(['path' => $file->store('private')]);
        });
    }
}
```

### 4.2 Audit Trail Implementation
```php
// Using Spatie Activity Log
use Spatie\Activitylog\Traits\LogsActivity;

class Contract extends Model {
    use LogsActivity;
    
    protected static $logAttributes = ['terms_json', 'end_date', 'status'];
    protected static $logOnlyDirty = true;
}
```

## Phase 5: Self-Service Portal & Reports (Week 7)

### 5.1 Role-Based Dashboards
**Blade Template Structure:**
```php
// resources/views/dashboard/index.blade.php
@switch(auth()->user()->primary_role)
    @case('HR_Admin_Manager')
        @include('dashboard.hr-admin')
        @break
    @case('Accounting_Manager') 
        @include('dashboard.accounting')
        @break
    @case('Employee')
        @include('dashboard.employee')
        @break
@endswitch
```

### 5.2 Export Functionality
**Excel/PDF Export Service:**
```php
class ReportExportService {
    public function exportPayrollReport($period, $format = 'excel') {
        $data = PayrollRun::with('payslips.employee')
            ->where('period_start', $period)
            ->get();
            
        return match($format) {
            'excel' => Excel::download(new PayrollExport($data), 'payroll.xlsx'),
            'pdf' => $this->generatePdfReport($data)
        };
    }
}
```

## Phase 6: Security & Deployment (Week 8)

### 6.1 Field-Level Encryption
**Implementation in Models:**
```php
// Employee model
protected $casts = [
    'national_id' => 'encrypted:string',
    'bank_account' => 'encrypted:string'
];

// Salary amount encryption
class PayslipLine extends Model {
    protected $casts = [
        'amount' => 'encrypted:decimal:2'
    ];
}
```

### 6.2 Document Watermarking
```php
class DocumentDownloadService {
    public function generateWatermarkedPdf($document, $user) {
        $mpdf = new \Mpdf\Mpdf();
        $mpdf->SetWatermarkText("Confidential - {$user->name} - " . now()->format('Y-m-d'));
        $mpdf->showWatermarkText = true;
        // ... render document
    }
}
```

### 6.3 GoDaddy cPanel Deployment
**Build Script (local):**
```bash
#!/bin/bash
composer install --no-dev --optimize-autoloader
npm run build
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Create deployment package
zip -r hrms-deploy.zip . -x "node_modules/*" ".git/*" "tests/*"
```

**Deployment Checklist:**
1. Upload ZIP to cPanel File Manager
2. Extract to `/home/<user>/app/`
3. Create `.env` file via File Manager
4. Import `docs/HRMS_Schema.sql` via phpMyAdmin
5. Set writable permissions on storage/ and bootstrap/cache/
6. Configure cPanel cron job: `curl https://hrms.sarieldin.com/cron/schedule?token=<secret>`
7. Test PDF generation with Arabic text

## Testing Strategy

### Unit Tests
- Salary calculation engine with various formulas
- RBAC permission checking
- Document versioning logic
- Approval workflow state transitions

### Integration Tests  
- ZKTeco attendance data processing
- Email notification sending
- PDF generation with Arabic fonts
- Role-based UI visibility

### Acceptance Tests
- End-to-end payroll run with approvals
- Document upload with versioning
- Employee self-service portal
- Bilingual UI switching

## Performance Considerations

### Database Optimization
- Index on frequently queried columns (employee_id, period dates)
- Partitioning for large attendance_logs table
- Query optimization for payroll calculations

### File Storage
- Separate storage for different document types
- Cleanup jobs for old document versions
- Efficient signed URL generation

### Caching Strategy
- Cache compiled salary structures
- Cache user permissions
- Cache template rendering

## Security Checklist

### Data Protection
- [ ] Salary data visible only to authorized roles
- [ ] National ID encryption at rest
- [ ] Secure document access via signed URLs
- [ ] Audit trails for sensitive operations

### Access Control
- [ ] Role-based route protection
- [ ] Field-level visibility controls
- [ ] Two-factor authentication for admin roles
- [ ] Session timeout configuration

### Compliance
- [ ] GDPR-compliant data handling
- [ ] Secure backup procedures
- [ ] Document retention policies
- [ ] Audit log preservation