<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\ContractController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\LetterController;
use App\Http\Controllers\ReportExportsController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// Dashboard - Main application entry point
Route::middleware(['auth'])->group(function () {
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/dashboard/expiry-alerts', [DashboardController::class, 'getExpiryAlerts'])->name('dashboard.expiry-alerts');
    Route::post('/dashboard/dismiss-notification/{notification}', [DashboardController::class, 'dismissNotification'])->name('dashboard.dismiss-notification');

    // Enhanced Dashboard Analytics API
    Route::get('/dashboard/analytics', [DashboardController::class, 'getAnalytics'])->name('dashboard.analytics');
    Route::get('/dashboard/employee-stats', [DashboardController::class, 'getEmployeeStats'])->name('dashboard.employee-stats');
    Route::get('/dashboard/payroll-insights', [DashboardController::class, 'getPayrollInsights'])->name('dashboard.payroll-insights');
    Route::get('/dashboard/contract-analytics', [DashboardController::class, 'getContractAnalytics'])->name('dashboard.contract-analytics');
    Route::get('/dashboard/critical-alerts', [DashboardController::class, 'getCriticalAlerts'])->name('dashboard.critical-alerts');
    Route::get('/dashboard/charts-data', [DashboardController::class, 'getChartsData'])->name('dashboard.charts-data');
    Route::get('/dashboard/recent-activities', [DashboardController::class, 'getRecentActivities'])->name('dashboard.recent-activities');
    Route::post('/dashboard/clear-cache', [DashboardController::class, 'clearCache'])->name('dashboard.clear-cache');
});

// Authentication Routes
Route::middleware(['guest'])->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
});

Route::middleware(['auth'])->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    Route::get('/profile', [AuthController::class, 'profile'])->name('profile');
    Route::put('/profile', [AuthController::class, 'updateProfile'])->name('profile.update');
    Route::post('/profile/link-employee', [AuthController::class, 'linkEmployee'])->name('profile.link-employee');
});

// Application routes with authentication
Route::middleware(['auth'])->group(function () {
    // Admin area
    Route::prefix('admin')->name('admin.')->group(function () {
        Route::get('users', [\App\Http\Controllers\Admin\UsersAdminController::class, 'index'])->name('users.index');
        Route::get('users/create', [\App\Http\Controllers\Admin\UsersAdminController::class, 'create'])->name('users.create');
        Route::post('users', [\App\Http\Controllers\Admin\UsersAdminController::class, 'store'])->name('users.store');
        Route::get('users/{user}/edit', [\App\Http\Controllers\Admin\UsersAdminController::class, 'edit'])->name('users.edit');
        Route::put('users/{user}', [\App\Http\Controllers\Admin\UsersAdminController::class, 'update'])->name('users.update');
        Route::delete('users/{user}', [\App\Http\Controllers\Admin\UsersAdminController::class, 'destroy'])->name('users.destroy');

        // Roles & Permissions management (Spatie)
        Route::get('roles', [\App\Http\Controllers\Admin\RolesAdminController::class, 'index'])->name('roles.index');
        Route::get('roles/create', [\App\Http\Controllers\Admin\RolesAdminController::class, 'create'])->name('roles.create');
        Route::post('roles', [\App\Http\Controllers\Admin\RolesAdminController::class, 'store'])->name('roles.store');
        Route::get('roles/{role}/edit', [\App\Http\Controllers\Admin\RolesAdminController::class, 'edit'])->name('roles.edit');
        Route::put('roles/{role}', [\App\Http\Controllers\Admin\RolesAdminController::class, 'update'])->name('roles.update');
        Route::delete('roles/{role}', [\App\Http\Controllers\Admin\RolesAdminController::class, 'destroy'])->name('roles.destroy');

        Route::get('permissions', [\App\Http\Controllers\Admin\PermissionsAdminController::class, 'index'])->name('permissions.index');
        Route::post('permissions', [\App\Http\Controllers\Admin\PermissionsAdminController::class, 'store'])->name('permissions.store');
        Route::delete('permissions/{permission}', [\App\Http\Controllers\Admin\PermissionsAdminController::class, 'destroy'])->name('permissions.destroy');
    });
    
    // Employee management routes
    Route::resource('employees', EmployeeController::class)->middleware('can:employees.view');
    
    // Additional employee routes
    Route::post('employees/{employee}/terminate', [EmployeeController::class, 'terminate'])
        ->middleware('can:employees.manage')
        ->name('employees.terminate');
    Route::post('employees/{employee}/reactivate', [EmployeeController::class, 'reactivate'])
        ->middleware('can:employees.manage')
        ->name('employees.reactivate');
    Route::get('employees-export', [EmployeeController::class, 'export'])
        ->name('employees.export');
    Route::get('employees-statistics', [EmployeeController::class, 'statistics'])
        ->name('employees.statistics');
    Route::get('employees-search', [EmployeeController::class, 'search'])
        ->name('employees.search');
    
    // Employee photo management routes
    Route::post('employees/{employee}/upload-photo', [EmployeeController::class, 'uploadPhoto'])
        ->middleware('can:employees.manage')
        ->name('employees.upload-photo');
    Route::get('employees/{employee}/photo', [EmployeeController::class, 'servePhoto'])
        ->name('employee.photo');
    Route::delete('employees/{employee}/photo', [EmployeeController::class, 'deletePhoto'])
        ->middleware('can:employees.manage')
        ->name('employees.delete-photo');
        
    // Contract management routes
    Route::resource('contracts', ContractController::class)->middleware('can:contracts.view');
    Route::post('contracts/{contract}/renew', [ContractController::class, 'renew'])
        ->middleware('can:contracts.manage')
        ->name('contracts.renew');
    Route::post('contracts/{contract}/terminate', [ContractController::class, 'terminate'])
        ->middleware('can:contracts.manage')
        ->name('contracts.terminate');
    // Lifecycle
    Route::post('contracts/{contract}/submit-review', [ContractController::class, 'submitForReview'])
        ->middleware('can:contracts.manage')
        ->name('contracts.submit-review');
    Route::post('contracts/{contract}/approve', [ContractController::class, 'approve'])
        ->middleware('can:contracts.manage')
        ->name('contracts.approve');
    Route::post('contracts/{contract}/sign', [ContractController::class, 'sign'])
        ->middleware('can:contracts.manage')
        ->name('contracts.sign');
    Route::get('contracts-requiring-attention', [ContractController::class, 'getContractsRequiringAttention'])
        ->name('contracts.requiring-attention');
    Route::get('contracts-export', [ContractController::class, 'export'])
        ->name('contracts.export');
    Route::post('contracts-bulk-operation', [ContractController::class, 'bulkOperation'])
        ->name('contracts.bulk-operation');
        
    // Document management routes
    Route::resource('documents', \App\Http\Controllers\DocumentController::class);
    Route::get('documents/{document}/download', [\App\Http\Controllers\DocumentController::class, 'download'])
        ->name('documents.download');
    Route::post('documents/{document}/upload-version', [\App\Http\Controllers\DocumentController::class, 'uploadVersion'])
        ->name('documents.upload-version');
    Route::get('documents/employee-contracts', [\App\Http\Controllers\DocumentController::class, 'getEmployeeContracts'])
        ->name('documents.employee-contracts');
        
    // Payroll management routes
    Route::resource('salary-components', \App\Http\Controllers\SalaryComponentController::class);
    Route::post('salary-components/seed-predefined', [\App\Http\Controllers\SalaryComponentController::class, 'seedPredefined'])
        ->name('salary-components.seed-predefined');

    // Leave & Attendance
    Route::prefix('leave')->name('leave.')->group(function(){
        // Policies (HR manage)
        Route::get('policies', [\App\Http\Controllers\LeavePolicyController::class,'index'])
            ->middleware('can:attendance.view')->name('policies.index');
        Route::get('policies/create', [\App\Http\Controllers\LeavePolicyController::class,'create'])
            ->middleware('can:attendance.manage')->name('policies.create');
        Route::post('policies', [\App\Http\Controllers\LeavePolicyController::class,'store'])
            ->middleware('can:attendance.manage')->name('policies.store');
        Route::get('policies/{id}/edit', [\App\Http\Controllers\LeavePolicyController::class,'edit'])
            ->middleware('can:attendance.manage')->name('policies.edit');
        Route::put('policies/{id}', [\App\Http\Controllers\LeavePolicyController::class,'update'])
            ->middleware('can:attendance.manage')->name('policies.update');
        Route::delete('policies/{id}', [\App\Http\Controllers\LeavePolicyController::class,'destroy'])
            ->middleware('can:attendance.manage')->name('policies.destroy');

        // Requests
        Route::get('requests', [\App\Http\Controllers\LeaveRequestController::class,'index'])
            ->middleware('can:attendance.view')->name('requests.index');
        Route::post('requests', [\App\Http\Controllers\LeaveRequestController::class,'store'])
            ->middleware('can:attendance.view')->name('requests.store');
        Route::post('requests/{id}/approve', [\App\Http\Controllers\LeaveRequestController::class,'approve'])
            ->middleware('can:attendance.manage')->name('requests.approve');
        Route::post('requests/{id}/reject', [\App\Http\Controllers\LeaveRequestController::class,'reject'])
            ->middleware('can:attendance.manage')->name('requests.reject');

        // Calendar events
        Route::get('events', [\App\Http\Controllers\LeaveRequestController::class,'events'])
            ->middleware('can:attendance.view')->name('events');
    });

    // Salary structure management routes (nested under employees)
    Route::prefix('employees/{employee}')->group(function () {
        Route::get('salary-structures', [\App\Http\Controllers\SalaryStructureController::class, 'index'])
            ->name('employees.salary-structures.index');
        Route::get('salary-structures/create', [\App\Http\Controllers\SalaryStructureController::class, 'create'])
            ->name('employees.salary-structures.create');
        Route::post('salary-structures', [\App\Http\Controllers\SalaryStructureController::class, 'store'])
            ->name('employees.salary-structures.store');
        Route::get('salary-structures/{salaryStructure}', [\App\Http\Controllers\SalaryStructureController::class, 'show'])
            ->name('employees.salary-structures.show');
        Route::get('salary-structures/{salaryStructure}/edit', [\App\Http\Controllers\SalaryStructureController::class, 'edit'])
            ->name('employees.salary-structures.edit');
        Route::put('salary-structures/{salaryStructure}', [\App\Http\Controllers\SalaryStructureController::class, 'update'])
            ->name('employees.salary-structures.update');
        Route::post('salary-structures/{salaryStructure}/clone', [\App\Http\Controllers\SalaryStructureController::class, 'clone'])
            ->name('employees.salary-structures.clone');
        Route::post('salary-structures/{salaryStructure}/terminate', [\App\Http\Controllers\SalaryStructureController::class, 'terminate'])
            ->name('employees.salary-structures.terminate');
        Route::get('salary-structures/{salaryStructure}/calculate', [\App\Http\Controllers\SalaryStructureController::class, 'calculate'])
            ->name('employees.salary-structures.calculate');
    });

    Route::middleware('payroll.enabled')->group(function () {
        // Payroll runs management
        Route::resource('payroll', \App\Http\Controllers\PayrollController::class);
        Route::post('payroll/{payrollRun}/calculate', [\App\Http\Controllers\PayrollController::class, 'calculate'])
            ->name('payroll.calculate');
        Route::post('payroll/{payrollRun}/lock', [\App\Http\Controllers\PayrollController::class, 'lock'])
            ->name('payroll.lock');
        Route::post('payroll/{payrollRun}/unlock', [\App\Http\Controllers\PayrollController::class, 'unlock'])
            ->name('payroll.unlock');
        Route::post('payroll/{payrollRun}/approve', [\App\Http\Controllers\PayrollController::class, 'approve'])
            ->name('payroll.approve');
        Route::post('payroll/{payrollRun}/reject', [\App\Http\Controllers\PayrollController::class, 'reject'])
            ->name('payroll.reject');
        Route::post('payroll/{payrollRun}/post', [\App\Http\Controllers\PayrollController::class, 'post'])
            ->name('payroll.post');
        Route::post('payroll/{payrollRun}/cancel', [\App\Http\Controllers\PayrollController::class, 'cancel'])
            ->name('payroll.cancel');
        Route::get('payroll/{payrollRun}/payslips', [\App\Http\Controllers\PayrollController::class, 'payslips'])
            ->name('payroll.payslips');
        Route::get('payroll/{payrollRun}/export', [\App\Http\Controllers\PayrollController::class, 'export'])
            ->name('payroll.export');
        Route::get('payroll-statistics', [\App\Http\Controllers\PayrollController::class, 'statistics'])
            ->name('payroll.statistics');

        // Individual payslip management
        Route::resource('payslips', \App\Http\Controllers\PayslipController::class)->only(['index', 'show']);
        Route::get('employees/{employee}/payslips', [\App\Http\Controllers\PayslipController::class, 'index'])
            ->name('employees.payslips.index');
        Route::get('payslips/{payslip}/pdf', [\App\Http\Controllers\PayslipController::class, 'downloadPdf'])
            ->name('payslips.download-pdf');
        Route::post('payslips/{payslip}/generate-pdf', [\App\Http\Controllers\PayslipController::class, 'generatePdf'])
            ->name('payslips.generate-pdf');
        Route::post('payroll/{payrollRun}/generate-bulk-pdf', [\App\Http\Controllers\PayslipController::class, 'generateBulkPdf'])
            ->name('payroll.generate-bulk-pdf');
        Route::post('payslips/{payslip}/send-email', [\App\Http\Controllers\PayslipController::class, 'sendEmail'])
            ->name('payslips.send-email');
        Route::post('payroll/{payrollRun}/send-bulk-email', [\App\Http\Controllers\PayslipController::class, 'sendBulkEmail'])
            ->name('payroll.send-bulk-email');
        Route::get('payslips-export', [\App\Http\Controllers\PayslipController::class, 'export'])
            ->name('payslips.export');
        Route::get('payslip-statistics', [\App\Http\Controllers\PayslipController::class, 'statistics'])
            ->name('payslips.statistics');
    });
    // Audit trail management routes (HR Admin and IT Admin only)
    Route::middleware(['can:viewAny,Spatie\Activitylog\Models\Activity'])->group(function () {
        Route::get('audit-trail', [\App\Http\Controllers\AuditTrailController::class, 'index'])
            ->name('audit-trail.index');
        Route::get('audit-trail/export', [\App\Http\Controllers\AuditTrailController::class, 'export'])
            ->name('audit-trail.export');
        Route::get('audit-trail/statistics', [\App\Http\Controllers\AuditTrailController::class, 'statistics'])
            ->name('audit-trail.statistics');
        Route::get('audit-trail/{subjectType}/{subjectId}', [\App\Http\Controllers\AuditTrailController::class, 'show'])
            ->name('audit-trail.show');
    });

    // Weekly digest management routes (HR Admin and IT Admin only)
    Route::middleware(['can:viewAny,Spatie\Activitylog\Models\Activity'])->group(function () {
        Route::get('weekly-digest', [\App\Http\Controllers\WeeklyDigestController::class, 'index'])
            ->name('weekly-digest.index');
        Route::get('weekly-digest/preview', [\App\Http\Controllers\WeeklyDigestController::class, 'preview'])
            ->name('weekly-digest.preview');
        Route::post('weekly-digest/send', [\App\Http\Controllers\WeeklyDigestController::class, 'send'])
            ->name('weekly-digest.send');
        Route::post('weekly-digest/test', [\App\Http\Controllers\WeeklyDigestController::class, 'test'])
            ->name('weekly-digest.test');
        Route::get('weekly-digest/statistics', [\App\Http\Controllers\WeeklyDigestController::class, 'statistics'])
            ->name('weekly-digest.statistics');
    });

    // Employee Self-Service Portal
    Route::prefix('my')->name('employee-portal.')->group(function () {
        Route::get('dashboard', [\App\Http\Controllers\EmployeePortalController::class, 'dashboard'])
            ->name('dashboard');
        Route::get('profile', [\App\Http\Controllers\EmployeePortalController::class, 'profile'])
            ->name('profile');
        Route::put('profile', [\App\Http\Controllers\EmployeePortalController::class, 'updateProfile'])
            ->name('profile.update');
        Route::get('documents', [\App\Http\Controllers\EmployeePortalController::class, 'documents'])
            ->name('documents');
        Route::post('documents/bulk-download', [\App\Http\Controllers\EmployeePortalController::class, 'bulkDownloadDocuments'])
            ->name('documents.bulk-download');
        Route::post('documents/request-update', [\App\Http\Controllers\EmployeePortalController::class, 'requestDocumentUpdate'])
            ->name('documents.request-update');
        Route::get('payslips', [\App\Http\Controllers\EmployeePortalController::class, 'payslips'])
            ->name('payslips');
        Route::get('attendance', [\App\Http\Controllers\EmployeePortalController::class, 'attendance'])
            ->name('attendance');
        Route::get('request-letter', [\App\Http\Controllers\EmployeePortalController::class, 'requestLetter'])
            ->name('request-letter');
        Route::post('request-letter', [\App\Http\Controllers\EmployeePortalController::class, 'submitLetterRequest'])
            ->name('request-letter.submit');
    });

    // Reports & Analytics System
    Route::prefix('reports')->name('reports.')->middleware('can:reports.view')->group(function () {
        Route::get('/', [\App\Http\Controllers\ReportsController::class, 'index'])
            ->name('index');

        // Employee Reports
        Route::get('employee-list', [\App\Http\Controllers\ReportsController::class, 'employeeList'])
            ->name('employee.list');
        Route::get('employee-demographics', [\App\Http\Controllers\ReportsController::class, 'employeeDemographics'])
            ->name('employee.demographics');
        Route::get('department-analysis', [\App\Http\Controllers\ReportsController::class, 'departmentAnalysis'])
            ->name('department.analysis');
        Route::get('position-analysis', [\App\Http\Controllers\ReportsController::class, 'positionAnalysis'])
            ->name('position.analysis');

        // Contract Reports
        Route::get('contract-status', [\App\Http\Controllers\ReportsController::class, 'contractStatus'])
            ->name('contract.status');
        Route::get('contract-expiry', [\App\Http\Controllers\ReportsController::class, 'contractExpiry'])
            ->name('contract.expiry');
        Route::get('contract-analysis', [\App\Http\Controllers\ReportsController::class, 'contractAnalysis'])
            ->name('contract.analysis');

        // Payroll Reports
        Route::get('payroll-summary', [\App\Http\Controllers\ReportsController::class, 'payrollSummary'])
            ->name('payroll.summary');
        Route::get('salary-analysis', [\App\Http\Controllers\ReportsController::class, 'salaryAnalysis'])
            ->name('salary.analysis');
        Route::get('payslip-report', [\App\Http\Controllers\ReportsController::class, 'payslipReport'])
            ->name('payslip.report');

        // Attendance Reports
        Route::get('attendance-summary', [\App\Http\Controllers\ReportsController::class, 'attendanceSummary'])
            ->name('attendance.summary');
        Route::get('overtime-report', [\App\Http\Controllers\ReportsController::class, 'overtimeReport'])
            ->name('overtime.report');
        Route::get('attendance-trends', [\App\Http\Controllers\ReportsController::class, 'attendanceTrends'])
            ->name('attendance.trends');

        // Document Reports
        Route::get('document-inventory', [\App\Http\Controllers\ReportsController::class, 'documentInventory'])
            ->name('document.inventory');
        Route::get('document-expiry', [\App\Http\Controllers\ReportsController::class, 'documentExpiry'])
            ->name('document.expiry');
        Route::get('compliance-report', [\App\Http\Controllers\ReportsController::class, 'complianceReport'])
            ->name('compliance.report');

        // Saved reports
        Route::get('saved', [\App\Http\Controllers\SavedReportController::class, 'index'])
            ->name('saved.index');
        Route::post('saved', [\App\Http\Controllers\SavedReportController::class, 'store'])
            ->name('saved.store');
        Route::delete('saved/{savedReport}', [\App\Http\Controllers\SavedReportController::class, 'destroy'])
            ->name('saved.destroy');
    });

    // HR Letter Generator System
    Route::prefix('letters')->name('letters.')->group(function () {
        // Letter Templates Management
        Route::prefix('templates')->name('templates.')->middleware('can:letters.manage')->group(function () {
            Route::get('/', [\App\Http\Controllers\LetterController::class, 'index'])
                ->name('index');
            Route::get('create', [\App\Http\Controllers\LetterController::class, 'createTemplate'])
                ->name('create');
            Route::post('/', [\App\Http\Controllers\LetterController::class, 'storeTemplate'])
                ->name('store');
            Route::get('{template}', [\App\Http\Controllers\LetterController::class, 'showTemplate'])
                ->name('show');
            Route::get('{template}/edit', [\App\Http\Controllers\LetterController::class, 'editTemplate'])
                ->name('edit');
            Route::put('{template}', [\App\Http\Controllers\LetterController::class, 'updateTemplate'])
                ->name('update');
            Route::delete('{template}', [\App\Http\Controllers\LetterController::class, 'destroyTemplate'])
                ->name('destroy');
            Route::post('seed', [\App\Http\Controllers\LetterController::class, 'seedTemplates'])
                ->name('seed');
        });

        // Letter Generation
        Route::get('generate', [\App\Http\Controllers\LetterController::class, 'generateForm'])
            ->middleware('can:letters.generate')
            ->name('generate');
        Route::post('preview', [\App\Http\Controllers\LetterController::class, 'preview'])
            ->middleware('can:letters.generate')
            ->name('preview');
        Route::post('generate', [\App\Http\Controllers\LetterController::class, 'generate'])
            ->middleware('can:letters.generate')
            ->name('store');

        // Generated Letters Management
        Route::get('/', [\App\Http\Controllers\LetterController::class, 'generatedLetters'])
            ->middleware('can:letters.view')
            ->name('index');
        Route::get('{letter}', [\App\Http\Controllers\LetterController::class, 'show'])
            ->middleware('can:letters.view')
            ->name('show');
        Route::post('{letter}/approve', [\App\Http\Controllers\LetterController::class, 'approve'])
            ->middleware('can:letters.manage')
            ->name('approve');
        Route::post('{letter}/reject', [\App\Http\Controllers\LetterController::class, 'reject'])
            ->middleware('can:letters.manage')
            ->name('reject');
        Route::get('{letter}/download-pdf', [\App\Http\Controllers\LetterController::class, 'downloadPdf'])
            ->middleware('can:letters.view')
            ->name('download-pdf');
    });
});

// Language switching (available to all users)
Route::get('language/{locale}', function ($locale) {
    if (in_array($locale, ['en', 'ar'])) {
        session(['locale' => $locale]);
    }
    return redirect()->back();
})->name('language.switch');

Route::middleware(['auth'])->group(function () {
    Route::prefix('reports/exports')->name('reports.exports.')->group(function () {
        Route::post('{report}', [ReportExportsController::class, 'store'])->name('store');
        Route::get('{correlation}', [ReportExportsController::class, 'show'])->name('show');
        Route::get('{correlation}/status', [ReportExportsController::class, 'status'])->name('status');
        Route::get('{correlation}/download', [ReportExportsController::class, 'download'])->name('download');
    });
});

