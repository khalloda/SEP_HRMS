# Building Block View

## System Overview

SEP_HRMS is structured in a layered architecture with clear separation of concerns. This document describes the decomposition of the system into its constituent building blocks, from high-level components down to implementation units.

## Level 1: System Context White Box

### Core Components

1. **Web Interface**
   - User authentication
   - Role-based access
   - Employee self-service
   - Administrative dashboard

2. **API Layer**
   - REST endpoints
   - ZKTeco integration
   - External system interfaces
   - API authentication

3. **Business Logic**
   - Service implementations
   - Policy enforcement
   - Data validation
   - Business rules

4. **Data Access**
   - Database operations
   - File storage
   - Cache management
   - Query optimization

### Supporting Systems

1. **Authentication System**
   - User management
   - Role assignment
   - Permission control
   - Session handling

2. **Document Management**
   - File storage
   - Version control
   - Access control
   - Template processing

3. **Notification System**
   - Email notifications
   - Alert management
   - Scheduled reminders
   - Digest generation

## Level 2: Container Level

### Web Application

1. **Frontend Components**
   - **Views**
     - Blade templates
     - Component layouts
     - Form handling
     - Client validation

   - **Assets**
     - CSS/SCSS
     - JavaScript modules
     - Static resources
     - Media files

2. **Controllers**
   - **Web Controllers**
     - Request handling
     - Response formatting
     - View composition
     - Session management

   - **API Controllers**
     - REST endpoints
     - JSON responses
     - Rate limiting
     - API versioning

3. **Middleware**
   - Authentication
   - Authorization
   - Input sanitization
   - Response compression

### Service Layer

1. **Core Services**
   - **Employee Service**
     - Profile management
     - Employment history
     - Contract handling
     - Document processing

   - **Attendance Service**
     - Time tracking
     - Leave management
     - Schedule handling
     - Report generation

   - **Payroll Service**
     - Salary calculation
     - Deduction processing
     - Payslip generation
     - Payment records

2. **Support Services**
   - **Document Service**
     - File operations
     - Template processing
     - Version control
     - Access management

   - **Notification Service**
     - Email dispatch
     - Alert handling
     - Reminder scheduling
     - Digest compilation

   - **Audit Service**
     - Activity logging
     - Change tracking
     - Access monitoring
     - Report generation

### Data Layer

1. **Models**
   - **Core Models**
     - Employee
     - Department
     - Position
     - Contract

   - **Transaction Models**
     - Attendance
     - Payroll
     - Leave
     - Document

   - **Support Models**
     - User
     - Role
     - Permission
     - Audit

2. **Repositories**
   - Data access patterns
   - Query optimization
   - Cache strategies
   - Transaction management

## Level 3: Component Level

### Model Components

1. **Employee Management**

   ```php
   class Employee extends Model
   {
       // Relationships
       public function department()
       public function position()
       public function contracts()
       public function documents()
       
       // Scopes
       public function scopeActive()
       public function scopeDepartment()
       
       // Attributes
       protected $encrypted = ['national_id']
       protected $dates = ['hired_at']
   }
   ```

2. **Document Management**

   ```php
   class Document extends Model
   {
       // Relationships
       public function versions()
       public function employee()
       
       // File handling
       public function storeFile()
       public function generateSignedUrl()
       
       // Version control
       public function createVersion()
       public function getLatestVersion()
   }
   ```

### Service Components

1. **Payroll Processing**

   ```php
   class PayrollService
   {
       // Core methods
       public function calculateSalary()
       public function processDeductions()
       public function generatePayslip()
       
       // Support methods
       public function validateCalculations()
       public function applyTaxRules()
   }
   ```

2. **Document Generation**

   ```php
   class DocumentGenerationService
   {
       // Template handling
       public function processTemplate()
       public function mergePlaceholders()
       
       // File operations
       public function generatePDF()
       public function storeDocument()
   }
   ```

## Interface Specifications

### Internal Interfaces

1. **Service Interfaces**

   ```php
   interface EmployeeServiceInterface
   {
       public function create(array $data)
       public function update($id, array $data)
       public function delete($id)
       public function find($id)
   }
   ```

2. **Repository Interfaces**

   ```php
   interface DocumentRepositoryInterface
   {
       public function findByEmployee($employeeId)
       public function findByType($type)
       public function createVersion($documentId, $content)
       public function getVersions($documentId)
   }
   ```

### External Interfaces

1. **API Endpoints**

   ```php
   // Attendance API
   Route::post('/api/attendance/push', [AttendanceController::class, 'store'])
   Route::get('/api/attendance/{employee}', [AttendanceController::class, 'show'])
   
   // Document API
   Route::get('/api/documents/{document}/download', [DocumentController::class, 'download'])
   Route::post('/api/documents/upload', [DocumentController::class, 'upload'])
   ```

2. **Event Interfaces**

   ```php
   class DocumentCreated
   {
       public $document;
       public $user;
       public $timestamp;
   }
   
   class AttendanceRecorded
   {
       public $employee;
       public $timestamp;
       public $deviceId;
   }
   ```
