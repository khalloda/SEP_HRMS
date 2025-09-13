<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\ContractController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Auth\AuthController;

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
});

// Language switching (available to all users)
Route::get('language/{locale}', function ($locale) {
    if (in_array($locale, ['en', 'ar'])) {
        session(['locale' => $locale]);
    }
    return redirect()->back();
})->name('language.switch');
