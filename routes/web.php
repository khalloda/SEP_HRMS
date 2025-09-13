<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\EmployeeController;
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
Route::get('/', function () {
    return view('dashboard');
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
});

// Language switching (available to all users)
Route::get('language/{locale}', function ($locale) {
    if (in_array($locale, ['en', 'ar'])) {
        session(['locale' => $locale]);
    }
    return redirect()->back();
})->name('language.switch');
