<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\EmployeeController;

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

// Authentication routes will be added when needed
// require __DIR__.'/auth.php';

// Application routes (temporarily without auth for development)
// Note: SetLocale middleware is already applied globally via web middleware group

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

// Language switching
Route::get('language/{locale}', function ($locale) {
    if (in_array($locale, ['en', 'ar'])) {
        session(['locale' => $locale]);
    }
    return redirect()->back();
})->name('language.switch');
