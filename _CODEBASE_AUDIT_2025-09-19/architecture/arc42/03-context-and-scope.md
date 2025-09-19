# System Context and Scope

## Business Context

SEP_HRMS is a comprehensive Human Resource Management System designed specifically for Sarie Eldin & Partners Legal Advisors, a law firm with approximately 50 employees. The system manages essential HR processes, employee data, and integrates with external systems for attendance tracking.

### External Systems and Users

#### Primary Users

- **HR Staff**
  - HR Managers and Coordinators
  - Primary system administrators
  - Full access to HR functions

- **Employees**
  - Regular staff members
  - Self-service portal access
  - Limited view of own data

- **Management**
  - Department heads
  - Partner access
  - Reporting and approval functions

#### External Systems

- **ZKTeco Attendance System**
  - Biometric attendance devices
  - Real-time attendance tracking
  - API-based integration

- **Email System**
  - Notification delivery
  - Document distribution
  - Automated alerts

- **Document Storage**
  - Secure file storage
  - Version control
  - Access management

## Technical Context

### System Scope

The system encompasses:

1. Employee Information Management
   - Personal data
   - Employment records
   - Contract management
   - Document storage

2. Attendance Management
   - Biometric integration
   - Leave management
   - Attendance reporting
   - Schedule tracking

3. Payroll Processing
   - Salary calculation
   - Payslip generation
   - Deduction management
   - Payment records

4. Document Management
   - Template system
   - Version control
   - Access control
   - Audit logging

### Technical Interfaces

1. **ZKTeco Integration**
   - Protocol: HTTP/HTTPS
   - Authentication: HMAC-SHA256
   - Data Format: JSON
   - Endpoint: `/api/attendance/push`

2. **Email Interface**
   - Protocol: SMTP
   - Server: GoDaddy SMTP
   - Authentication: TLS
   - Templates: Blade

3. **Storage Interface**
   - Type: Private disk
   - Access: Signed URLs
   - Encryption: AES-256
   - Backup: Daily

4. **Database Interface**
   - System: MySQL 8.0
   - Character Set: UTF8MB4
   - Collation: utf8mb4_unicode_ci
   - Encryption: Field-level

## Quality Goals

1. **Security**
   - Encrypted sensitive data
   - Role-based access control
   - Audit logging
   - Secure file storage

2. **Usability**
   - Intuitive interface
   - Mobile responsive
   - Bilingual support
   - Quick search

3. **Performance**
   - Sub-second response
   - Optimized queries
   - Efficient file handling
   - Background processing

4. **Maintainability**
   - Modular design
   - Clean architecture
   - Comprehensive logging
   - Documentation

## Stakeholders

### Internal Stakeholders

1. **HR Department**
   - Primary system users
   - Process owners
   - Data custodians

2. **Management**
   - Strategic oversight
   - Report consumers
   - Approval authorities

3. **Employees**
   - End users
   - Data subjects
   - Self-service users

### External Stakeholders

1. **Regulatory Bodies**
   - Bar Association
   - Labor Ministry
   - Data Protection

2. **Technology Partners**
   - ZKTeco (Attendance)
   - GoDaddy (Hosting)
   - Support vendors

3. **Clients**
   - Indirect stakeholders
   - Compliance requirements
   - Service expectations
