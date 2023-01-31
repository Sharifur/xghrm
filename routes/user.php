<?php


Route::group(['prefix' => 'user-home','middleware' => 'auth:web'],function (){
    Route::get('/',[\App\Http\Controllers\User\UserDashboardController::class,'index'])->name('user.home');
    Route::group(['prefix' => 'profile'],function (){
        Route::get('/change-password',[\App\Http\Controllers\User\UserDashboardController::class,'change_password'])->name('user.profile.change.password');
        Route::post('/change-password',[\App\Http\Controllers\User\UserDashboardController::class,'update_change_password']);

        Route::get('/change-info',[\App\Http\Controllers\User\UserDashboardController::class,'change_info'])->name('user.profile.change.info');
        Route::post('/change-info',[\App\Http\Controllers\User\UserDashboardController::class,'update_change_info']);

    });
    Route::group(['prefix' => 'attendance'],function (){
        Route::get('/',[\App\Http\Controllers\User\AttendanceCheckController::class,'index'])->name('user.attendance.index');
        Route::post('/',[\App\Http\Controllers\User\AttendanceCheckController::class,'check']);
    });
});
