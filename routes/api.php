<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('/login', [\App\Http\Controllers\Api\UserController::class, 'login']);

/*=================================
    AI AGENT API ROUTES
==================================*/
Route::group(['prefix' => 'ai-agent', 'middleware' => 'ai.signature'], function () {
    // Employees
    Route::get('/employees', [\App\Http\Controllers\Api\Ai\EmployeeController::class, 'index']);
    Route::get('/employees/today/on-leave', [\App\Http\Controllers\Api\Ai\EmployeeController::class, 'todayOnLeave']);
    Route::get('/employees/today/wfh', [\App\Http\Controllers\Api\Ai\EmployeeController::class, 'todayWfh']);
    Route::get('/employees/alerts', [\App\Http\Controllers\Api\Ai\EmployeeController::class, 'alerts']);
    Route::get('/employees/{id}', [\App\Http\Controllers\Api\Ai\EmployeeController::class, 'show']);

    // Leave requests
    Route::get('/leave-requests/pending', [\App\Http\Controllers\Api\Ai\LeaveController::class, 'pending']);
    Route::get('/leave-requests', [\App\Http\Controllers\Api\Ai\LeaveController::class, 'index']);
    Route::post('/leave-requests', [\App\Http\Controllers\Api\Ai\LeaveController::class, 'store']);
    Route::post('/leave-requests/{id}/approve', [\App\Http\Controllers\Api\Ai\LeaveController::class, 'approve']);
    Route::post('/leave-requests/{id}/reject', [\App\Http\Controllers\Api\Ai\LeaveController::class, 'reject']);

    // WFH requests
    Route::get('/wfh-requests/pending', [\App\Http\Controllers\Api\Ai\WfhController::class, 'pending']);
    Route::post('/wfh-requests', [\App\Http\Controllers\Api\Ai\WfhController::class, 'store']);
    Route::post('/wfh-requests/{id}/approve', [\App\Http\Controllers\Api\Ai\WfhController::class, 'approve']);
    Route::post('/wfh-requests/{id}/reject', [\App\Http\Controllers\Api\Ai\WfhController::class, 'reject']);

    // Payslips
    Route::get('/payslips/export/{month}', [\App\Http\Controllers\Api\Ai\PayslipController::class, 'export']);
    Route::get('/payslips/{employeeId}/{month}', [\App\Http\Controllers\Api\Ai\PayslipController::class, 'show']);
    Route::get('/payslips', [\App\Http\Controllers\Api\Ai\PayslipController::class, 'index']);
    Route::post('/payslips/generate', [\App\Http\Controllers\Api\Ai\PayslipController::class, 'generate']);
    Route::patch('/payslips/{id}', [\App\Http\Controllers\Api\Ai\PayslipController::class, 'update']);
    Route::post('/payslips/{id}/approve', [\App\Http\Controllers\Api\Ai\PayslipController::class, 'approve']);
    Route::post('/payslips/{id}/mark-paid', [\App\Http\Controllers\Api\Ai\PayslipController::class, 'markPaid']);
});

Route::group(["prefix" => "user","middleware" => "auth:sanctum","controller" => \App\Http\Controllers\Api\UserController::class],function (){
    Route::post('/change-password', [\App\Http\Controllers\Api\UserController::class, 'changePassword']);
    Route::post('/update-profile', [\App\Http\Controllers\Api\UserController::class, 'changeProfileInfo']);
    Route::get('/info', [\App\Http\Controllers\Api\UserController::class, 'userInfo']);
    Route::get('/leave-list', [\App\Http\Controllers\Api\UserController::class, 'leaveList']);

    //salary related apis
    Route::post("salaries",[\App\Http\Controllers\Api\SalaryController::class,"salariesList"]);
    Route::post("advance/salary",[\App\Http\Controllers\Api\SalaryController::class,"advanceSalariesList"]);

    //attendance related apis
    Route::get("attendance",[\App\Http\Controllers\Api\AttendanceController::class,"atteandacne"]);
    Route::post("attendance/create",[\App\Http\Controllers\Api\AttendanceController::class,"atteandacneCreate"]);

});
