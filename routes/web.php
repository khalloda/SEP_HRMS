<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\ContractController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\LetterController;

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
    
    // Employee management routes
    Route::resource('employees', EmployeeController::class);
    
    // Additional employee routes
    Route::post('employees/{employee}/terminate', [EmployeeController::class, 'terminate'])
        ->name('employees.terminate');
    Route::post('employees/{employee}/reactivate', [EmployeeController::class, 'reactivate'])
        ->name('employees.reactivate');
    Route::get('employees-export', [EmployeeController::class, 'export'])
        ->name('employees.export');
    Route::get('employees-statistics', [EmployeeController::class, 'statistics'])
        ->name('employees.statistics');
    Route::get('employees-search', [EmployeeController::class, 'search'])
        ->name('employees.search');
    
    // Employee photo management routes
    Route::post('employees/{employee}/upload-photo', [EmployeeController::class, 'uploadPhoto'])
        ->name('employees.upload-photo');
    Route::get('employees/{employee}/photo', [EmployeeController::class, 'servePhoto'])
        ->name('employee.photo');
    Route::delete('employees/{employee}/photo', [EmployeeController::class, 'deletePhoto'])
        ->name('employees.delete-photo');
        
    // Contract management routes
    Route::resource('contracts', ContractController::class);
    Route::post('contracts/{contract}/renew', [ContractController::class, 'renew'])
        ->name('contracts.renew');
    Route::post('contracts/{contract}/terminate', [ContractController::class, 'terminate'])
        ->name('contracts.terminate');
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
    Route::prefix('reports')->name('reports.')->group(function () {
        Route::get('/', [\App\Http\Controllers\ReportsController::class, 'index'])
            ->name('index');

        // Employee Reports
        Route::get('employee-list', [\App\Http\Controllers\ReportsController::class, 'employeeList'])
            ->name('employee.list');
        Route::get('employee-demographics', [\App\Http\Controllers\ReportsController::class, 'employeeDemographics'])
            ->name('employee.demographics');

        // Contract Reports
        Route::get('contract-status', [\App\Http\Controllers\ReportsController::class, 'contractStatus'])
            ->name('contract.status');

        // Payroll Reports
        Route::get('payroll-summary', [\App\Http\Controllers\ReportsController::class, 'payrollSummary'])
            ->name('payroll.summary');

        // Attendance Reports
        Route::get('attendance-summary', [\App\Http\Controllers\ReportsController::class, 'attendanceSummary'])
            ->name('attendance.summary');

        // Document Reports
        Route::get('document-inventory', [\App\Http\Controllers\ReportsController::class, 'documentInventory'])
            ->name('document.inventory');
    });

    // HR Letter Generator System
    Route::prefix('letters')->name('letters.')->group(function () {
        // Letter Templates Management
        Route::prefix('templates')->name('templates.')->group(function () {
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
            ->name('generate');
        Route::post('preview', [\App\Http\Controllers\LetterController::class, 'preview'])
            ->name('preview');
        Route::post('generate', [\App\Http\Controllers\LetterController::class, 'generate'])
            ->name('store');

        // Generated Letters Management
        Route::get('/', [\App\Http\Controllers\LetterController::class, 'generatedLetters'])
            ->name('index');
        Route::get('{letter}', [\App\Http\Controllers\LetterController::class, 'show'])
            ->name('show');
        Route::post('{letter}/approve', [\App\Http\Controllers\LetterController::class, 'approve'])
            ->name('approve');
        Route::post('{letter}/reject', [\App\Http\Controllers\LetterController::class, 'reject'])
            ->name('reject');
        Route::get('{letter}/download-pdf', [\App\Http\Controllers\LetterController::class, 'downloadPdf'])
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
