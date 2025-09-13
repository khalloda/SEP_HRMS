<?php

use App\Http\Controllers\AttendanceController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// ZKTeco Attendance Integration API
Route::prefix('attendance')->name('api.attendance.')->group(function () {
    // Public endpoints for ZKTeco devices (no authentication required)
    Route::post('/push', [AttendanceController::class, 'pushAttendance'])->name('push');
    Route::get('/health', [AttendanceController::class, 'healthCheck'])->name('health');

    // Protected endpoints requiring API key
    Route::get('/stats', [AttendanceController::class, 'getAttendanceStats'])->name('stats');
    Route::get('/employees', [AttendanceController::class, 'getEmployeeList'])->name('employees');
});
