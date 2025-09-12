# Claude Development Tasks for HRMS System

This document provides granular, actionable development tasks specifically designed for Claude Code workflow. Each task includes specific file paths, code patterns, and validation criteria.

## Task Categories
- **SETUP**: Environment and project initialization
- **MODEL**: Database models and relationships  
- **MIGR**: Database migrations and schema
- **SEED**: Database seeding and test data
- **CTRL**: Controllers and business logic
- **VIEW**: Blade templates and UI components
- **TEST**: Unit and feature tests
- **API**: API endpoints and integrations
- **DEPLOY**: Deployment and configuration

---

## Phase 0: Project Setup

### SETUP-001: Initialize Laravel Project
**Priority**: P0 | **Estimated**: 30 mins
```bash
composer create-project laravel/laravel . "^10.0"
php artisan --version
```
**Validation**: Confirm Laravel 10+ is installed
**Output**: `composer.json`, `artisan`, basic Laravel structure

### SETUP-002: Install Required Packages
**Priority**: P0 | **Estimated**: 15 mins
```bash
composer require spatie/laravel-permission
composer require mpdf/mpdf  
composer require spatie/laravel-activitylog
composer require maatwebsite/excel
composer require spatie/laravel-medialibrary
```
**Validation**: All packages appear in `composer.json`
**Output**: Updated `composer.json` and `composer.lock`

### SETUP-003: Configure Database Settings
**Priority**: P0 | **Estimated**: 10 mins
**File**: `config/database.php`
```php
'mysql' => [
    'charset' => 'utf8mb4',
    'collation' => 'utf8mb4_unicode_ci',
    'strict' => true,
    'engine' => 'InnoDB',
],
```
**Output**: UTF8MB4 configuration for Arabic support

### SETUP-004: Configure Localization
**Priority**: P0 | **Estimated**: 20 mins
**Files to modify**:
- `config/app.php`: Add 'ar' to supported locales
- Create `resources/lang/ar/` directory structure
- Create `app/Http/Middleware/SetLocale.php`

**Output**: Bilingual support infrastructure

---

## Phase 1: Foundation Models & Migrations

### MIGR-001: Create Users & Roles Tables
**Priority**: P0 | **Estimated**: 45 mins
**Files to create**:
- `database/migrations/2024_01_01_000001_create_roles_tables.php`
- `database/migrations/2024_01_01_000002_create_permissions_tables.php`
- `database/migrations/2024_01_01_000003_create_role_user_table.php`
- `database/migrations/2024_01_01_000004_create_permission_role_table.php`

**Schema requirements**:
```sql
CREATE TABLE roles (
  id BIGINT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
  name VARCHAR(80) UNIQUE NOT NULL,
  display_name VARCHAR(120) NOT NULL,
  created_at TIMESTAMP NULL,
  updated_at TIMESTAMP NULL
);
```
**Output**: RBAC foundation tables

### MODEL-001: Create Role and Permission Models
**Priority**: P0 | **Estimated**: 30 mins
**Files to create**:
- `app/Models/Role.php`
- `app/Models/Permission.php`

**Code pattern**:
```php
class Role extends Model
{
    protected $fillable = ['name', 'display_name'];
    
    public function permissions() {
        return $this->belongsToMany(Permission::class);
    }
    
    public function users() {
        return $this->belongsToMany(User::class);
    }
}
```
**Output**: RBAC model classes

### MIGR-002: Create Master Data Tables
**Priority**: P0 | **Estimated**: 60 mins
**Files to create**:
- `database/migrations/2024_01_02_000001_create_departments_table.php`
- `database/migrations/2024_01_02_000002_create_positions_table.php`
- `database/migrations/2024_01_02_000003_create_employment_types_table.php`

**Department table schema**:
```sql
CREATE TABLE departments (
  id BIGINT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
  code VARCHAR(32) UNIQUE NOT NULL,
  name_en VARCHAR(120) NOT NULL,
  name_ar VARCHAR(120) NOT NULL,
  created_at TIMESTAMP NULL,
  updated_at TIMESTAMP NULL
);
```
**Output**: Master data foundation

### MODEL-002: Create Master Data Models
**Priority**: P0 | **Estimated**: 45 mins
**Files to create**:
- `app/Models/Department.php`
- `app/Models/Position.php` 
- `app/Models/EmploymentType.php`

**Pattern for bilingual models**:
```php
class Department extends Model
{
    protected $fillable = ['code', 'name_en', 'name_ar'];
    
    public function getNameAttribute() {
        return app()->getLocale() === 'ar' ? $this->name_ar : $this->name_en;
    }
    
    public function employees() {
        return $this->hasMany(Employee::class);
    }
}
```
**Output**: Bilingual master data models

### MIGR-003: Create Employees Table
**Priority**: P0 | **Estimated**: 60 mins
**File**: `database/migrations/2024_01_03_000001_create_employees_table.php`

**Schema requirements**:
```sql
CREATE TABLE employees (
  id BIGINT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
  code VARCHAR(32) UNIQUE NOT NULL,
  first_name VARCHAR(80) NOT NULL,
  last_name VARCHAR(80) NOT NULL,
  arabic_name VARCHAR(160) NULL,
  email VARCHAR(190) UNIQUE,
  phone VARCHAR(40),
  hire_date DATE,
  status ENUM('active','inactive','terminated','on_leave') DEFAULT 'active',
  department_id BIGINT UNSIGNED,
  position_id BIGINT UNSIGNED,
  employment_type_id TINYINT UNSIGNED,
  manager_id BIGINT UNSIGNED NULL,
  national_id VARBINARY(256) NULL,
  salary_visibility_flag TINYINT(1) NOT NULL DEFAULT 0,
  created_at TIMESTAMP NULL,
  updated_at TIMESTAMP NULL,
  FULLTEXT KEY ft_employees_search (first_name, last_name, arabic_name, email, code)
);
```
**Output**: Employee table with fulltext search

### MODEL-003: Create Employee Model
**Priority**: P0 | **Estimated**: 45 mins
**File**: `app/Models/Employee.php`

**Key features**:
```php
class Employee extends Model
{
    use HasFactory, LogsActivity;
    
    protected $fillable = [
        'code', 'first_name', 'last_name', 'arabic_name', 
        'email', 'phone', 'hire_date', 'status',
        'department_id', 'position_id', 'employment_type_id', 'manager_id'
    ];
    
    protected $casts = [
        'national_id' => 'encrypted',
        'salary_visibility_flag' => 'boolean',
        'hire_date' => 'date'
    ];
    
    // Relationships
    public function department() {
        return $this->belongsTo(Department::class);
    }
    
    public function position() {
        return $this->belongsTo(Position::class);
    }
    
    public function manager() {
        return $this->belongsTo(Employee::class, 'manager_id');
    }
    
    public function subordinates() {
        return $this->hasMany(Employee::class, 'manager_id');
    }
    
    // Fulltext search
    public function scopeSearch($query, $term) {
        return $query->whereRaw(
            "MATCH(first_name, last_name, arabic_name, email, code) AGAINST(? IN NATURAL LANGUAGE MODE)", 
            [$term]
        );
    }
    
    // Activity logging
    protected static $logAttributes = ['*'];
    protected static $logOnlyDirty = true;
}
```
**Output**: Complete Employee model with search and audit

---

## Phase 2: Contracts & Documents

### MIGR-004: Create Contracts Table  
**Priority**: P0 | **Estimated**: 30 mins
**File**: `database/migrations/2024_01_04_000001_create_contracts_table.php`

**Schema**:
```sql
CREATE TABLE contracts (
  id BIGINT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
  employee_id BIGINT UNSIGNED NOT NULL,
  type ENUM('permanent','fixed_term','probation','internship','consultancy') NOT NULL,
  start_date DATE NOT NULL,
  end_date DATE NULL,
  terms_json JSON NULL,
  status ENUM('active','expired','terminated','pending') DEFAULT 'active',
  created_at TIMESTAMP NULL,
  updated_at TIMESTAMP NULL,
  INDEX idx_contract_end_date (end_date)
);
```
**Output**: Contract management foundation

### MODEL-004: Create Contract Model
**Priority**: P0 | **Estimated**: 40 mins  
**File**: `app/Models/Contract.php`

```php
class Contract extends Model
{
    use HasFactory, LogsActivity;
    
    protected $fillable = [
        'employee_id', 'type', 'start_date', 'end_date', 
        'terms_json', 'status'
    ];
    
    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'terms_json' => 'array'
    ];
    
    public function employee() {
        return $this->belongsTo(Employee::class);
    }
    
    public function documents() {
        return $this->hasMany(Document::class);
    }
    
    // Check if contract is expiring soon
    public function scopeExpiringSoon($query, $days = 30) {
        return $query->whereNotNull('end_date')
            ->where('end_date', '>=', now())
            ->where('end_date', '<=', now()->addDays($days));
    }
    
    // Activity logging
    protected static $logAttributes = ['type', 'start_date', 'end_date', 'terms_json', 'status'];
    protected static $logOnlyDirty = true;
}
```
**Output**: Contract model with expiry tracking

### MIGR-005: Create Documents & Versioning Tables
**Priority**: P0 | **Estimated**: 45 mins
**Files to create**:
- `database/migrations/2024_01_05_000001_create_documents_table.php`
- `database/migrations/2024_01_05_000002_create_document_versions_table.php`
- `database/migrations/2024_01_05_000003_create_tags_table.php`
- `database/migrations/2024_01_05_000004_create_document_tag_table.php`

**Documents table**:
```sql
CREATE TABLE documents (
  id BIGINT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
  employee_id BIGINT UNSIGNED NULL,
  contract_id BIGINT UNSIGNED NULL,
  type VARCHAR(60) NOT NULL,
  path VARCHAR(255) NOT NULL,
  original_name VARCHAR(190) NOT NULL,
  mime VARCHAR(80) NOT NULL,
  checksum CHAR(64) NULL,
  visibility ENUM('private','shared') DEFAULT 'private',
  expires_at DATE NULL,
  version_current INT NOT NULL DEFAULT 1,
  watermark_note VARCHAR(120) NULL,
  created_at TIMESTAMP NULL,
  updated_at TIMESTAMP NULL,
  INDEX idx_documents_exp (expires_at, type)
);
```
**Output**: Document management with versioning

---

## Phase 3: Payroll System

### MIGR-006: Create Salary Components Table
**Priority**: P0 | **Estimated**: 30 mins
**File**: `database/migrations/2024_01_06_000001_create_salary_components_table.php`

```sql
CREATE TABLE salary_components (
  id BIGINT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
  code VARCHAR(40) UNIQUE NOT NULL,
  name_en VARCHAR(120) NOT NULL,
  name_ar VARCHAR(120) NOT NULL,
  comp_type ENUM('earning','deduction','info') NOT NULL,
  calc_mode ENUM('fixed','formula','variable_net_based') NOT NULL,
  taxable TINYINT(1) NOT NULL DEFAULT 1,
  visible_to_roles JSON NULL,
  priority_order INT NOT NULL DEFAULT 0,
  created_at TIMESTAMP NULL,
  updated_at TIMESTAMP NULL
);
```
**Output**: Flexible salary component system

### MODEL-005: Create Salary Component Model
**Priority**: P0 | **Estimated**: 35 mins
**File**: `app/Models/SalaryComponent.php`

```php
class SalaryComponent extends Model
{
    protected $fillable = [
        'code', 'name_en', 'name_ar', 'comp_type', 
        'calc_mode', 'taxable', 'visible_to_roles', 'priority_order'
    ];
    
    protected $casts = [
        'visible_to_roles' => 'array',
        'taxable' => 'boolean'
    ];
    
    public function getNameAttribute() {
        return app()->getLocale() === 'ar' ? $this->name_ar : $this->name_en;
    }
    
    public function isVisibleToRole($role) {
        if (empty($this->visible_to_roles)) return true;
        return in_array($role, $this->visible_to_roles);
    }
    
    public function structureComponents() {
        return $this->hasMany(SalaryStructureComponent::class, 'component_id');
    }
}
```
**Output**: Bilingual salary component model

### CTRL-001: Create Employee CRUD Controller
**Priority**: P0 | **Estimated**: 90 mins
**File**: `app/Http/Controllers/EmployeeController.php`

```php
class EmployeeController extends Controller
{
    public function index(Request $request) {
        $query = Employee::with(['department', 'position', 'manager']);
        
        // Global search
        if ($request->has('search')) {
            $query->search($request->search);
        }
        
        // Filters
        if ($request->has('department')) {
            $query->where('department_id', $request->department);
        }
        
        if ($request->boolean('expiring_contracts')) {
            $query->whereHas('contracts', function($q) {
                $q->expiringSoon(30);
            });
        }
        
        if ($request->boolean('missing_documents')) {
            $query->whereDoesntHave('documents', function($q) {
                $q->where('type', 'national_id');
            });
        }
        
        $employees = $query->paginate(20);
        return view('employees.index', compact('employees'));
    }
    
    public function store(EmployeeStoreRequest $request) {
        DB::transaction(function() use ($request) {
            $employee = Employee::create($request->validated());
            
            // Handle photo upload
            if ($request->hasFile('photo')) {
                $employee->addMediaFromRequest('photo')
                    ->toMediaCollection('photos');
            }
            
            // Create initial contract if provided
            if ($request->has('contract')) {
                $employee->contracts()->create($request->contract);
            }
        });
        
        return redirect()->route('employees.index')
            ->with('success', __('Employee created successfully'));
    }
}
```
**Output**: Full CRUD controller with search and filters

### VIEW-001: Create Employee Index View
**Priority**: P0 | **Estimated**: 60 mins
**File**: `resources/views/employees/index.blade.php`

```blade
@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 text-gold">{{ __('Employees') }}</h1>
        
        @can('employees.create')
        <a href="{{ route('employees.create') }}" class="btn btn-gold">
            <i class="fas fa-plus me-2"></i>{{ __('Add Employee') }}
        </a>
        @endcan
    </div>
    
    <!-- Search and Filters -->
    <div class="card mb-4">
        <div class="card-body">
            <form method="GET" class="row g-3">
                <div class="col-md-4">
                    <input type="text" class="form-control" name="search" 
                           value="{{ request('search') }}" 
                           placeholder="{{ __('Search employees...') }}">
                </div>
                
                <div class="col-md-3">
                    <select name="department" class="form-select">
                        <option value="">{{ __('All Departments') }}</option>
                        @foreach($departments as $dept)
                        <option value="{{ $dept->id }}" 
                                {{ request('department') == $dept->id ? 'selected' : '' }}>
                            {{ $dept->name }}
                        </option>
                        @endforeach
                    </select>
                </div>
                
                <div class="col-md-2">
                    <div class="form-check">
                        <input type="checkbox" name="expiring_contracts" value="1" 
                               class="form-check-input" id="expiring"
                               {{ request('expiring_contracts') ? 'checked' : '' }}>
                        <label class="form-check-label" for="expiring">
                            {{ __('Expiring Contracts') }}
                        </label>
                    </div>
                </div>
                
                <div class="col-md-3">
                    <button type="submit" class="btn btn-outline-gold me-2">
                        {{ __('Filter') }}
                    </button>
                    <a href="{{ route('employees.index') }}" class="btn btn-outline-secondary">
                        {{ __('Clear') }}
                    </a>
                </div>
            </form>
        </div>
    </div>
    
    <!-- Employee Table -->
    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>{{ __('Code') }}</th>
                            <th>{{ __('Name') }}</th>
                            <th>{{ __('Department') }}</th>
                            <th>{{ __('Position') }}</th>
                            <th>{{ __('Status') }}</th>
                            <th>{{ __('Actions') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($employees as $employee)
                        <tr>
                            <td>{{ $employee->code }}</td>
                            <td>
                                <div class="d-flex align-items-center">
                                    @if($employee->getFirstMedia('photos'))
                                    <img src="{{ $employee->getFirstMediaUrl('photos', 'thumb') }}" 
                                         class="rounded-circle me-2" width="32" height="32">
                                    @endif
                                    <div>
                                        <div class="fw-bold">{{ $employee->first_name }} {{ $employee->last_name }}</div>
                                        @if($employee->arabic_name)
                                        <small class="text-muted">{{ $employee->arabic_name }}</small>
                                        @endif
                                    </div>
                                </div>
                            </td>
                            <td>{{ $employee->department?->name }}</td>
                            <td>{{ $employee->position?->name }}</td>
                            <td>
                                <span class="badge bg-{{ $employee->status === 'active' ? 'success' : 'warning' }}">
                                    {{ __(ucfirst($employee->status)) }}
                                </span>
                            </td>
                            <td>
                                <div class="btn-group btn-group-sm">
                                    <a href="{{ route('employees.show', $employee) }}" 
                                       class="btn btn-outline-primary">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    @can('employees.edit')
                                    <a href="{{ route('employees.edit', $employee) }}" 
                                       class="btn btn-outline-secondary">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    @endcan
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            
            {{ $employees->appends(request()->query())->links() }}
        </div>
    </div>
</div>
@endsection
```
**Output**: Professional employee listing with search/filters

---

## Phase 4: Advanced Features

### API-001: Create Attendance Push Endpoint
**Priority**: P1 | **Estimated**: 45 mins
**File**: `app/Http/Controllers/Api/AttendanceController.php`

```php
class AttendanceController extends Controller
{
    public function push(Request $request) {
        // Validate HMAC signature
        $signature = $request->header('X-Signature');
        $expectedSignature = hash_hmac('sha256', $request->getContent(), config('app.attendance_secret'));
        
        if (!hash_equals($signature, $expectedSignature)) {
            return response()->json(['error' => 'Invalid signature'], 403);
        }
        
        $validated = $request->validate([
            'device_serial' => 'required|string',
            'punches' => 'required|array',
            'punches.*.employee_code' => 'required|exists:employees,code',
            'punches.*.punch_time' => 'required|date',
            'punches.*.punch_type' => 'required|in:in,out'
        ]);
        
        DB::transaction(function() use ($validated) {
            $device = AttendanceDevice::firstOrCreate(
                ['serial' => $validated['device_serial']],
                ['name' => 'ZKTeco Device', 'location' => 'Office']
            );
            
            foreach ($validated['punches'] as $punch) {
                $employee = Employee::where('code', $punch['employee_code'])->first();
                
                AttendanceLog::updateOrCreate([
                    'employee_id' => $employee->id,
                    'device_id' => $device->id,
                    'punch_time' => $punch['punch_time'],
                    'punch_type' => $punch['punch_type'],
                ], [
                    'source' => 'connector'
                ]);
            }
            
            $device->update(['last_sync_at' => now()]);
        });
        
        return response()->json([
            'status' => 'success',
            'processed' => count($validated['punches'])
        ]);
    }
}
```
**Output**: Secure attendance data ingestion

### SEED-001: Create Role & Permission Seeder
**Priority**: P0 | **Estimated**: 60 mins
**File**: `database/seeders/RolePermissionSeeder.php`

```php
class RolePermissionSeeder extends Seeder
{
    public function run()
    {
        // Create permissions
        $permissions = [
            // Employee management
            'employees.view' => 'View Employees',
            'employees.create' => 'Create Employees', 
            'employees.edit' => 'Edit Employees',
            'employees.delete' => 'Delete Employees',
            
            // Payroll permissions
            'payroll.view' => 'View Payroll',
            'payroll.view_net' => 'View Net Salary',
            'payroll.edit' => 'Edit Payroll',
            'payroll.approve' => 'Approve Payroll',
            
            // Contract permissions
            'contracts.view' => 'View Contracts',
            'contracts.create' => 'Create Contracts',
            'contracts.edit' => 'Edit Contracts',
            'contracts.terminate' => 'Terminate Contracts',
            
            // Document permissions
            'documents.view' => 'View Documents',
            'documents.upload' => 'Upload Documents',
            'documents.download' => 'Download Documents',
        ];
        
        foreach ($permissions as $name => $display) {
            Permission::create(['name' => $name, 'display_name' => $display]);
        }
        
        // Create roles with permissions
        $roles = [
            'HR_Admin_Manager' => [
                'display_name' => 'HR & Administration Manager',
                'permissions' => array_keys($permissions) // All permissions
            ],
            
            'Accounting_Manager' => [
                'display_name' => 'Accounting Manager',
                'permissions' => [
                    'employees.view', 'payroll.view', 'payroll.view_net',
                    'payroll.edit', 'payroll.approve', 'contracts.view'
                ]
            ],
            
            'HR_Coordinator' => [
                'display_name' => 'HR Coordinator', 
                'permissions' => [
                    'employees.view', 'employees.create', 'employees.edit',
                    'payroll.view', 'contracts.view', 'contracts.create',
                    'contracts.edit', 'documents.view', 'documents.upload'
                ]
                // Note: No payroll.view_net permission
            ],
            
            'Accountant' => [
                'display_name' => 'Accountant',
                'permissions' => [
                    'employees.view', 'payroll.view', 'contracts.view',
                    'documents.view'
                ]
                // Note: No payroll.view_net permission
            ],
            
            'Employee' => [
                'display_name' => 'Employee',
                'permissions' => [
                    'documents.view' // Own documents only
                ]
            ],
            
            'IT_Admin' => [
                'display_name' => 'IT Administrator',
                'permissions' => [
                    // System administration permissions only
                ]
            ]
        ];
        
        foreach ($roles as $name => $config) {
            $role = Role::create([
                'name' => $name,
                'display_name' => $config['display_name']
            ]);
            
            $permissions = Permission::whereIn('name', $config['permissions'])->get();
            $role->permissions()->sync($permissions);
        }
    }
}
```
**Output**: Complete RBAC setup with law firm roles

---

## Testing Tasks

### TEST-001: Employee Model Tests
**Priority**: P1 | **Estimated**: 60 mins
**File**: `tests/Unit/Models/EmployeeTest.php`

```php
class EmployeeTest extends TestCase
{
    use RefreshDatabase;
    
    public function test_employee_has_fulltext_search() {
        $employee = Employee::factory()->create([
            'first_name' => 'Ahmed',
            'last_name' => 'Hassan',
            'arabic_name' => 'أحمد حسن'
        ]);
        
        $results = Employee::search('Ahmed')->get();
        $this->assertContains($employee->id, $results->pluck('id'));
        
        $results = Employee::search('أحمد')->get();
        $this->assertContains($employee->id, $results->pluck('id'));
    }
    
    public function test_employee_manager_relationship() {
        $manager = Employee::factory()->create();
        $employee = Employee::factory()->create(['manager_id' => $manager->id]);
        
        $this->assertEquals($manager->id, $employee->manager->id);
        $this->assertContains($employee->id, $manager->subordinates->pluck('id'));
    }
    
    public function test_national_id_encryption() {
        $employee = Employee::factory()->create(['national_id' => '12345678901234']);
        
        // Verify it's encrypted in database
        $raw = DB::table('employees')->where('id', $employee->id)->first();
        $this->assertNotEquals('12345678901234', $raw->national_id);
        
        // Verify it decrypts correctly
        $this->assertEquals('12345678901234', $employee->fresh()->national_id);
    }
}
```
**Output**: Comprehensive model testing

### TEST-002: Payroll Calculation Tests  
**Priority**: P1 | **Estimated**: 90 mins
**File**: `tests/Feature/PayrollCalculationTest.php`

```php
class PayrollCalculationTest extends TestCase
{
    use RefreshDatabase;
    
    public function test_basic_salary_calculation() {
        $employee = Employee::factory()->create();
        
        // Create salary structure
        $structure = SalaryStructure::factory()->create([
            'employee_id' => $employee->id,
            'effective_from' => now()->subMonth()
        ]);
        
        // Add components
        $basic = SalaryComponent::factory()->create([
            'code' => 'BASIC',
            'comp_type' => 'earning',
            'calc_mode' => 'fixed'
        ]);
        
        $housing = SalaryComponent::factory()->create([
            'code' => 'HOUSING', 
            'comp_type' => 'earning',
            'calc_mode' => 'formula'
        ]);
        
        $structure->components()->attach($basic->id, [
            'value_numeric' => 5000.00,
            'priority_order' => 1
        ]);
        
        $structure->components()->attach($housing->id, [
            'formula_expr' => 'BASIC * 0.30',
            'priority_order' => 2
        ]);
        
        // Calculate payslip
        $calculator = app(SalaryCalculationService::class);
        $payslip = $calculator->calculatePayslip($employee, now()->format('Y-m'));
        
        $this->assertEquals(5000.00, $payslip->getComponentAmount('BASIC'));
        $this->assertEquals(1500.00, $payslip->getComponentAmount('HOUSING'));
        $this->assertEquals(6500.00, $payslip->gross);
    }
    
    public function test_role_based_salary_visibility() {
        $hrCoordinator = User::factory()->create();
        $hrCoordinator->assignRole('HR_Coordinator');
        
        $accountingManager = User::factory()->create();
        $accountingManager->assignRole('Accounting_Manager');
        
        $employee = Employee::factory()->create();
        $payslip = Payslip::factory()->create(['employee_id' => $employee->id]);
        
        // HR Coordinator should NOT see net/gross
        $this->actingAs($hrCoordinator);
        $response = $this->get(route('payroll.payslip', $payslip));
        $response->assertDontSee($payslip->net);
        $response->assertDontSee($payslip->gross);
        
        // Accounting Manager SHOULD see net/gross  
        $this->actingAs($accountingManager);
        $response = $this->get(route('payroll.payslip', $payslip));
        $response->assertSee($payslip->net);
        $response->assertSee($payslip->gross);
    }
}
```
**Output**: Critical payroll business logic testing

---

## Deployment Tasks

### DEPLOY-001: Create Build Script
**Priority**: P1 | **Estimated**: 30 mins
**File**: `scripts/build-deploy.sh`

```bash
#!/bin/bash
set -e

echo "Starting HRMS deployment build..."

# Clean previous build
rm -rf hrms-deploy.zip

# Install dependencies (production)
composer install --no-dev --optimize-autoloader --no-interaction

# Build frontend assets
npm ci
npm run build

# Laravel optimizations
php artisan config:cache
php artisan route:cache  
php artisan view:cache
php artisan event:cache

# Create deployment archive
echo "Creating deployment package..."
zip -r hrms-deploy.zip . \
  -x "node_modules/*" \
  -x ".git/*" \
  -x "tests/*" \
  -x ".env*" \
  -x "*.log" \
  -x "storage/logs/*" \
  -x "scripts/*"

echo "Deployment package created: hrms-deploy.zip"
echo "Upload this file to your cPanel File Manager"
```
**Output**: Automated deployment packaging

### DEPLOY-002: Create Environment Template
**Priority**: P1 | **Estimated**: 15 mins  
**File**: `.env.example`

```env
APP_NAME="HRMS - Sarie Eldin & Partners"
APP_ENV=production
APP_KEY=base64:GENERATE_NEW_KEY
APP_DEBUG=false
APP_TIMEZONE=Africa/Cairo
APP_URL=https://hrms.sarieldin.com

APP_LOCALE=en
APP_FALLBACK_LOCALE=en
APP_FAKER_LOCALE=en_US

DB_CONNECTION=mysql
DB_HOST=localhost
DB_PORT=3306
DB_DATABASE=sarieldi_hrms
DB_USERNAME=sarieldi_hrms
DB_PASSWORD=GENERATE_STRONG_PASSWORD

BROADCAST_CONNECTION=log
CACHE_STORE=file
FILESYSTEM_DISK=private
QUEUE_CONNECTION=database
SESSION_DRIVER=database
SESSION_LIFETIME=480

MAIL_MAILER=smtp
MAIL_HOST=smtp.hostgator.com
MAIL_PORT=587
MAIL_USERNAME=no-reply@sarieldin.com
MAIL_PASSWORD=EMAIL_PASSWORD
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS="no-reply@sarieldin.com"
MAIL_FROM_NAME="${APP_NAME}"

# HRMS Specific
ATTENDANCE_SECRET=GENERATE_RANDOM_SECRET_FOR_HMAC
PDF_FONTS_PATH=/home/sarieldi/app/resources/fonts
DOCUMENT_WATERMARK_DEFAULT="Confidential - HR Use Only"
```
**Output**: Production-ready environment template

### DEPLOY-003: Create Deployment Checklist
**Priority**: P1 | **Estimated**: 20 mins
**File**: `DEPLOYMENT.md`

```markdown
# HRMS Deployment Checklist (GoDaddy cPanel)

## Pre-deployment
- [ ] Build completed successfully with `scripts/build-deploy.sh`
- [ ] Database created in cPanel MySQL
- [ ] Database user created with full privileges
- [ ] SSL certificate active on hrms.sarieldin.com

## Deployment Steps
1. **Upload & Extract**
   - [ ] Upload `hrms-deploy.zip` to cPanel File Manager
   - [ ] Extract to `/home/sarieldi/app/` directory
   - [ ] Verify `public/` directory exists

2. **Environment Configuration** 
   - [ ] Create `.env` file from template
   - [ ] Generate APP_KEY: `php artisan key:generate`
   - [ ] Configure database credentials
   - [ ] Set correct file permissions (755 for directories, 644 for files)

3. **Database Setup**
   - [ ] Import `docs/HRMS_Schema.sql` via phpMyAdmin
   - [ ] Import `docs/HRMS_Seed.sql` for initial data
   - [ ] Run `php artisan migrate:status` to verify

4. **File Permissions**
   ```bash
   chmod -R 755 bootstrap/cache/
   chmod -R 755 storage/
   chown -R sarieldi:sarieldi storage/ bootstrap/cache/
   ```

5. **Subdomain Configuration**
   - [ ] Create subdomain: hrms.sarieldin.com
   - [ ] Point document root to `/home/sarieldi/app/public`
   - [ ] Test basic Laravel page loads

6. **Cron Job Setup**
   - [ ] Add cron job: `0 1 * * * curl -s https://hrms.sarieldin.com/cron/schedule?token=SECRET`
   - [ ] Test cron execution manually

7. **Testing Checklist**
   - [ ] Login page accessible
   - [ ] Arabic fonts display correctly in PDFs
   - [ ] File uploads work to storage/app/private/
   - [ ] Email notifications send successfully
   - [ ] Database queries execute without errors

## Post-deployment
- [ ] Create first HR Admin user
- [ ] Import initial employee data
- [ ] Configure attendance device connection
- [ ] Schedule regular database backups

## Rollback Plan
- [ ] Keep previous version as `app-backup/`
- [ ] Database backup stored before migration
- [ ] Rollback procedure documented
```
**Output**: Complete deployment guide

---

## Task Summary by Priority

### P0 (Must Have) - 23 tasks
Core functionality required for MVP:
- Project setup and environment configuration
- RBAC and user management
- Employee CRUD with search
- Basic payroll structure  
- Contract management
- Document storage foundation

### P1 (Should Have) - 15 tasks  
Important features for full functionality:
- Advanced payroll calculations
- Attendance integration
- Document versioning and tagging
- Comprehensive testing
- Deployment automation

### P2 (Nice to Have) - 8 tasks
Enhancement features:
- Advanced reporting
- API endpoints
- Onboarding wizard
- Performance optimizations

**Total Estimated Time**: ~45 hours of development work

Each task includes specific file paths, code patterns, validation criteria, and expected outputs to ensure consistent implementation following Laravel best practices and the specific requirements of this HRMS system for Sarie Eldin & Partners.