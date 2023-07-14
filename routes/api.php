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

Route::group(["prefix" => "user","middleware" => "auth:sanctum","controller" => \App\Http\Controllers\Api\UserController::class],function (){
    Route::post('/change-password', [\App\Http\Controllers\Api\UserController::class, 'changePassword']);
    Route::post('/update-profile', [\App\Http\Controllers\Api\UserController::class, 'changeProfileInfo']);
    Route::get('/info', [\App\Http\Controllers\Api\UserController::class, 'userInfo']);

    //salary related apis
    Route::post("salaries",[\App\Http\Controllers\Api\SalaryController::class,"salariesList"]);
    Route::post("advance/salary",[\App\Http\Controllers\Api\SalaryController::class,"advanceSalariesList"]);

    //attendance related apis
    Route::get("attendance",[\App\Http\Controllers\Api\AttendanceController::class,"atteandacne"]);
    Route::post("attendance/create",[\App\Http\Controllers\Api\AttendanceController::class,"atteandacneCreate"]);

});
