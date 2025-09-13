<?php

return [
    /*
    |--------------------------------------------------------------------------
    | HRMS Language Lines
    |--------------------------------------------------------------------------
    |
    | The following language lines are used throughout the HRMS application.
    | These are specific to the Human Resource Management System features.
    |
    */

    // Common
    'dashboard' => 'Dashboard',
    'search' => 'Search',
    'filter' => 'Filter',
    'clear' => 'Clear',
    'actions' => 'Actions',
    'view' => 'View',
    'edit' => 'Edit',
    'delete' => 'Delete',
    'save' => 'Save',
    'cancel' => 'Cancel',
    'create' => 'Create',
    'add' => 'Add',
    'update' => 'Update',
    'created_successfully' => 'Created successfully',
    'updated_successfully' => 'Updated successfully',
    'deleted_successfully' => 'Deleted successfully',

    // Navigation
    'employees' => 'Employees',
    'departments' => 'Departments',
    'positions' => 'Positions',
    'contracts' => 'Contracts',
    'payroll' => 'Payroll',
    'attendance' => 'Attendance',
    'documents' => 'Documents',
    'reports' => 'Reports',
    'settings' => 'Settings',

    // Employee Fields
    'employee_code' => 'Employee Code',
    'first_name' => 'First Name',
    'last_name' => 'Last Name',
    'arabic_name' => 'Arabic Name',
    'email' => 'Email',
    'phone' => 'Phone',
    'hire_date' => 'Hire Date',
    'department' => 'Department',
    'position' => 'Position',
    'manager' => 'Manager',
    'active' => 'Active',
    'inactive' => 'Inactive',
    'terminated' => 'Terminated',
    'on_leave' => 'On Leave',

    // Contract Fields
    'contract_type' => 'Contract Type',
    'start_date' => 'Start Date',
    'end_date' => 'End Date',
    'permanent' => 'Permanent',
    'fixed_term' => 'Fixed Term',
    'probation' => 'Probationary',
    'internship' => 'Internship',
    'consultancy' => 'Consultancy',

    // Payroll
    'salary' => 'Salary',
    'basic_salary' => 'Basic Salary',
    'allowances' => 'Allowances',
    'deductions' => 'Deductions',
    'gross_salary' => 'Gross Salary',
    'net_salary' => 'Net Salary',
    'payslip' => 'Payslip',
    'payroll_run' => 'Payroll Run',

    // Departments
    'all_departments' => 'All Departments',
    'hr' => 'Human Resources',
    'admin' => 'Administration',
    'accounting' => 'Accounting',
    'it' => 'Information Technology',
    'litigation' => 'Litigation',
    'corporate' => 'Corporate',
    'arbitration' => 'Arbitration',

    // Positions - Lawyers
    'managing_partner' => 'Managing Partner',
    'senior_partner' => 'Senior Partner',
    'partner' => 'Partner',
    'junior_partner' => 'Junior Partner',
    'senior_associate' => 'Senior Associate',
    'associate' => 'Associate',
    'junior_associate' => 'Junior Associate',
    'intern' => 'Intern',

    // Positions - Admin
    'hr_admin_manager' => 'HR & Admin Manager',
    'accounting_manager' => 'Accounting Manager',
    'accountant' => 'Accountant',
    'senior_systems_engineer' => 'Senior Systems Engineer',
    'messenger' => 'Messenger',
    'admin_assistant' => 'Admin Assistant',
    'office_boy' => 'Office Boy',

    // Filters
    'expiring_contracts' => 'Expiring Contracts',
    'missing_documents' => 'Missing Documents',
    'search_employees' => 'Search employees...',

    // Messages
    'no_results' => 'No results found',
    'loading' => 'Loading...',
    'confirm_delete' => 'Are you sure you want to delete this item?',
    'recent_activity' => 'Recent Activity',
    
    // Employee Management
    'add_employee' => 'Add Employee',
    'employee_created' => 'Employee created successfully',
    'employee_updated' => 'Employee updated successfully',
    'employee_details' => 'Employee Details',
    'employment_information' => 'Employment Information',
    'personal_information' => 'Personal Information',
    'contact_information' => 'Contact Information',
    
    // Employee specific translations
    'employee' => [
        'created_successfully' => 'Employee created successfully',
        'updated_successfully' => 'Employee updated successfully',
        'deleted_successfully' => 'Employee deleted successfully',
        'terminated_successfully' => 'Employee terminated successfully',
        'reactivated_successfully' => 'Employee reactivated successfully',
        'cannot_delete_has_dependencies' => 'Cannot delete employee: has contracts, payslips, or direct reports',
        'cannot_be_own_manager' => 'Employee cannot be their own manager',
        'not_terminated' => 'Employee is not terminated',
        'termination_reason' => 'Termination Reason',
        'termination_date' => 'Termination Date',
        'terminate' => 'Terminate',
        'reactivate' => 'Reactivate',
        'export' => 'Export',
        'statistics' => 'Statistics',
        'years_of_service' => 'Years of Service',
        'direct_reports' => 'Direct Reports',
        'direct_report' => 'Direct Report',
        'has_user_account' => 'Has User Account',
        'salary_visibility' => 'Salary Visibility',
        'employment_type' => 'Employment Type',
        'full_name' => 'Full Name',
        'display_name' => 'Display Name',
        'hierarchy_level' => 'Hierarchy Level',
        'is_overtime_eligible' => 'Overtime Eligible',
        'total_employees' => 'Total Employees',
        'active_employees' => 'Active Employees',
        'new_hires_this_month' => 'New Hires This Month',
        'by_department' => 'By Department',
        'by_position' => 'By Position',
        'by_employment_type' => 'By Employment Type',
        // Photo Management
        'photo' => 'Photo',
        'upload_photo' => 'Upload Photo',
        'change_photo' => 'Change Photo',
        'delete_photo' => 'Delete Photo',
        'photo_uploaded_successfully' => 'Photo uploaded successfully',
        'photo_deleted_successfully' => 'Photo deleted successfully',
        'no_photo_to_delete' => 'No photo to delete',
        'photo_requirements' => 'Photo must be JPEG, PNG, JPG, or GIF format and under 5MB',
        'default_photo' => 'Default Photo',
        'photo_info' => 'Photo Information',
        'original_filename' => 'Original Filename',
        'file_size' => 'File Size',
        'uploaded_date' => 'Uploaded Date',
        'photo_actions' => 'Photo Actions',
    ],
    
    // Status translations (individual status items are defined above as simple strings)

    // Document Types
    'national_id' => 'National ID',
    'bar_license' => 'Bar License',
    'contract_document' => 'Contract Document',
    'employment_proof' => 'Employment Proof',
    'hr_letter' => 'HR Letter',

    // Status translations (nested for backward compatibility)
    'status' => [
        'status' => 'Status',
        'active' => 'Active',
        'inactive' => 'Inactive',
        'terminated' => 'Terminated',
        'on_leave' => 'On Leave',
    ],

    // Activity Log Messages
    'activity' => [
        'Employee created' => 'Employee created',
        'Employee updated' => 'Employee updated',
        'Employee deleted' => 'Employee deleted',
        'Employee terminated' => 'Employee terminated',
        'Employee reactivated' => 'Employee reactivated',
        'Employee photo uploaded' => 'Employee photo uploaded',
        'Employee photo deleted' => 'Employee photo deleted',
        'Document uploaded' => 'Document uploaded',
        'Document updated' => 'Document updated',
        'Document deleted' => 'Document deleted',
        'Document downloaded' => 'Document downloaded',
        'Salary component created' => 'Salary component created',
        'Salary component updated' => 'Salary component updated',
        'Salary component deleted' => 'Salary component deleted',
        'User logged in' => 'User logged in',
        'User logged out' => 'User logged out',
        'User registered' => 'User registered',
        'User profile updated' => 'User profile updated',
        'User account linked to employee' => 'User account linked to employee',
    ],

    // Contract translations
    'add_contract' => 'Add Contract',
    'total_contracts' => 'Total Contracts',
    'active_contracts' => 'Active Contracts',
    'search_contracts' => 'Search contracts...',
    'search_by_employee' => 'Search by employee name or code...',
    'all_types' => 'All Types',
    'all_statuses' => 'All Statuses',
    'all_employees' => 'All Employees',
    'all_expiry_statuses' => 'All Expiry Statuses',
    'expiry_status' => 'Expiry Status',
    'expiring_urgently' => 'Expiring Urgently (≤7 days)',
    'expiring_critically' => 'Expiring Critically (≤15 days)',
    'expiring_soon' => 'Expiring Soon (≤30 days)',
    'days_left' => 'days left',
    'ok' => 'OK',
    'renew' => 'Renew',
    'back_to_contracts' => 'Back to Contracts',
    'contract_details' => 'Contract Details',
    'select_employee' => 'Select Employee',
    'select_contract_type' => 'Select Contract Type',
    'contract_type_help' => 'Choose the appropriate contract type for this employee',
    'end_date_help' => 'Leave blank for permanent contracts',
    'end_date_required_for_type' => 'End date is required for this contract type',
    'contract_terms' => 'Contract Terms',
    'optional' => 'Optional',
    'confidentiality_clause' => 'Confidentiality Agreement',
    'non_compete_clause' => 'Non-Compete Agreement',
    'notice_period_days' => 'Notice Period (Days)',
    'working_hours_per_day' => 'Working Hours/Day',
    'probation_period_days' => 'Probation Period (Days)',
    'annual_leave_days' => 'Annual Leave Days',
    'sick_leave_days' => 'Sick Leave Days',
    'health_insurance_provided' => 'Health Insurance Provided',
    'create_contract' => 'Create Contract',
    'contract_information' => 'Contract Information',
    'edit_contract' => 'Edit Contract',
    'renew_contract' => 'Renew Contract',
    'terminate_contract' => 'Terminate Contract',
    'delete_contract' => 'Delete Contract',
    'contract_id' => 'Contract ID',
    'duration' => 'Duration',
    'months' => 'months',
    'months_short' => 'm',
    'days_until_expiry' => 'Days Until Expiry',
    'related_documents' => 'Related Documents',
    'requires_end_date' => 'Requires End Date',
    'renewable' => 'Renewable',
    'properties' => 'Properties',
    'yes' => 'Yes',
    'no' => 'No',
    'terms' => [
        'confidentiality' => 'Confidentiality',
        'non_compete' => 'Non-Compete',
        'notice_period_days' => 'Notice Period (Days)',
        'working_hours' => 'Working Hours',
        'probation_period' => 'Probation Period (Days)',
        'annual_leave_days' => 'Annual Leave Days',
        'sick_leave_days' => 'Sick Leave Days',
        'health_insurance' => 'Health Insurance',
        'end_of_service_eligible' => 'End of Service Eligible',
        'renewal_option' => 'Renewal Option',
        'evaluation_required' => 'Evaluation Required',
        'stipend_amount' => 'Stipend Amount',
        'certificate_provided' => 'Certificate Provided',
        'hourly_rate' => 'Hourly Rate',
        'invoice_required' => 'Invoice Required',
    ],
    
    // Contract types
    'contract_types' => [
        'permanent' => 'Permanent',
        'fixed_term' => 'Fixed Term',
        'probation' => 'Probationary',
        'internship' => 'Internship',
        'consultancy' => 'Consultancy',
    ],
    
    // Contract status
    'contract_status' => [
        'active' => 'Active',
        'expired' => 'Expired',
        'terminated' => 'Terminated',
        'pending' => 'Pending',
    ],

    // Contract management
    'contract' => [
        'created_successfully' => 'Contract created successfully',
        'updated_successfully' => 'Contract updated successfully',
        'deleted_successfully' => 'Contract deleted successfully',
        'renewed_successfully' => 'Contract renewed successfully',
        'terminated_successfully' => 'Contract terminated successfully',
        'cannot_delete_has_dependencies' => 'Cannot delete contract: it has associated documents',
        'cannot_renew' => 'This contract cannot be renewed',
    ],

    // Language Toggle
    'language' => 'Language',
    'english' => 'English',
    'arabic' => 'العربية',

];