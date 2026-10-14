<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\EmployeeController;
use App\Http\Controllers\Api\DepartmentController;
use App\Http\Controllers\Api\AttendanceController;
use App\Http\Controllers\Api\LeaveController;
use App\Http\Controllers\Api\PayrollController;
use App\Http\Controllers\Api\TaxRuleController;
use App\Http\Controllers\Api\PayslipSettingController;

Route::middleware('api')->group(function () {
    // Departments
    Route::apiResource('departments', DepartmentController::class);

    // Employees
    Route::apiResource('employees', EmployeeController::class);

    // Attendance
    Route::apiResource('attendances', AttendanceController::class);
    Route::post('attendances/clock-in', [AttendanceController::class, 'clockIn']);
    Route::post('attendances/clock-out', [AttendanceController::class, 'clockOut']);

    // Leaves
    Route::apiResource('leaves', LeaveController::class);
    Route::post('leaves/{id}/approve', [LeaveController::class, 'approve']);
    Route::post('leaves/{id}/reject', [LeaveController::class, 'reject']);

    // Payroll
    Route::apiResource('payrolls', PayrollController::class);
    Route::post('payrolls/generate-period', [PayrollController::class, 'generatePeriod']);

    // Tax Rules
    Route::apiResource('tax-rules', TaxRuleController::class);

    // Payslip Settings
    Route::get('payslip-settings', [PayslipSettingController::class, 'index']);
    Route::put('payslip-settings', [PayslipSettingController::class, 'update']);
    Route::post('payslip-settings/reset', [PayslipSettingController::class, 'reset']);
});
