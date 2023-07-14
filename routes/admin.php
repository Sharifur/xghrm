<?php

/*=======================================
    ADMIN DASHBOARD ROUTES
=======================================*/
Route::group(['prefix' => 'admin-home','middleware' => ['auth:admin']],function (){
    Route::get('/',[\App\Http\Controllers\Admin\AdminDashboardController::class,'index'])->name('admin.home');
    Route::post('/database-upgrade',[\App\Http\Controllers\Admin\AdminDashboardController::class,'databaseUpdate'])->name('admin.database.upgrade');

    /*=================================
        EMPLOYEE ROUTES
    ==================================*/
    Route::group(['prefix' => 'employee'],function (){
        /* employee */
        Route::get('/all',[\App\Http\Controllers\Admin\EmployeeController::class,'index'])->name('admin.employee.all');
        Route::get('/new',[\App\Http\Controllers\Admin\EmployeeController::class,'new'])->name('admin.employee.new');
        Route::get('/edit/{id}',[\App\Http\Controllers\Admin\EmployeeController::class,'edit'])->name('admin.employee.edit');
        Route::get('/view/{id}',[\App\Http\Controllers\Admin\EmployeeController::class,'view'])->name('admin.employee.view');
        Route::post('/convert-user',[\App\Http\Controllers\Admin\EmployeeController::class,'convertUser'])->name('admin.employee.convert.user');
        Route::post('/new',[\App\Http\Controllers\Admin\EmployeeController::class,'store']);
        Route::post('/update',[\App\Http\Controllers\Admin\EmployeeController::class,'update'])->name('admin.employee.update');
        Route::post('/delete',[\App\Http\Controllers\Admin\EmployeeController::class,'delete'])->name('admin.employee.delete');
        Route::get('/attendance-check/{id}',[\App\Http\Controllers\Admin\EmployeeController::class,'attenadance_check'])->name('admin.employee.attendance.check');
        Route::post('/attendance-check-post',[\App\Http\Controllers\Admin\EmployeeController::class,'attenadance_check_post'])->name('admin.employee.attendance.post');
        Route::post('/details/{id}',[\App\Http\Controllers\Admin\EmployeeController::class,'details'])->name('admin.employee.details');

        Route::get('/advance-salary/all',[\App\Http\Controllers\Admin\AdvanceSalaryController::class,'index'])->name('admin.employee.advance.salary.all');
        Route::get('/advance-salary/new',[\App\Http\Controllers\Admin\AdvanceSalaryController::class,'create'])->name('admin.employee.advance.salary.new');
        Route::post('/advance-salary/store',[\App\Http\Controllers\Admin\AdvanceSalaryController::class,'store'])->name('admin.employee.advance.salary.store');
        Route::get('/advance-salary/edit/{id}',[\App\Http\Controllers\Admin\AdvanceSalaryController::class,'edit'])->name('admin.employee.advance.salary.edit');
        Route::get('/advance-salary/view/{id}',[\App\Http\Controllers\Admin\AdvanceSalaryController::class,'view'])->name('admin.employee.advance.salary.view');
        Route::post('/advance-salary/update',[\App\Http\Controllers\Admin\AdvanceSalaryController::class,'update'])->name('admin.employee.advance.salary.update');
        Route::post('/advance-salary/delete/{id}',[\App\Http\Controllers\Admin\AdvanceSalaryController::class,'delete'])->name('admin.employee.advance.salary.delete');

        /* category */
        Route::group(['prefix' => 'category'],function (){
            Route::get('all',[\App\Http\Controllers\Admin\EmployeeCategoryController::class,'index'])->name('admin.employee.category');
            Route::post('new',[\App\Http\Controllers\Admin\EmployeeCategoryController::class,'store'])->name('admin.employee.category.new');
            Route::post('update',[\App\Http\Controllers\Admin\EmployeeCategoryController::class,'update'])->name('admin.employee.category.update');
            Route::post('delete',[\App\Http\Controllers\Admin\EmployeeCategoryController::class,'delete'])->name('admin.employee.category.delete');
        });

        /* attendance */
        Route::group(['prefix' => 'attendance'],function (){
            Route::get('all',[\App\Http\Controllers\Admin\AttendanceController::class,'index'])->name('admin.employee.attendance');
            Route::get('create',[\App\Http\Controllers\Admin\AttendanceController::class,'create'])->name('admin.employee.attendance.create');
            Route::post('create',[\App\Http\Controllers\Admin\AttendanceController::class,'store']);
            Route::get('edit/{id}',[\App\Http\Controllers\Admin\AttendanceController::class,'edit'])->name('admin.employee.attendance.edit');
            Route::post('update',[\App\Http\Controllers\Admin\AttendanceController::class,'update'])->name('admin.employee.attendance.update');
            Route::post('delete',[\App\Http\Controllers\Admin\AttendanceController::class,'delete'])->name('admin.employee.attendance.delete');
            Route::get('extract/{id}',[\App\Http\Controllers\Admin\AttendanceController::class,'extract'])->name('admin.employee.attendance.extract');
            Route::post('get-column-values',[\App\Http\Controllers\Admin\AttendanceController::class,'get_csv_column_values'])->name('admin.employee.attendance.csv.column.value');
            Route::post('insert-attendance-logs',[\App\Http\Controllers\Admin\AttendanceController::class,'insert_attendance_log_from_csv_column'])->name('admin.employee.attendance.log.from.csv');
            Route::post('new-attendance-log',[\App\Http\Controllers\Admin\AttendanceController::class,'new_attendance_log'])->name('admin.employee.attendance.log.add');
        });

        /* attendance logs */
        Route::group(['prefix' => 'attendancelogs'],function (){
            Route::get('all',[\App\Http\Controllers\Admin\AttendanceLogsController::class,'index'])->name('admin.employee.attendance.logs');
            Route::get('create',[\App\Http\Controllers\Admin\AttendanceLogsController::class,'create'])->name('admin.employee.attendance.logs.create');
            Route::post('create',[\App\Http\Controllers\Admin\AttendanceLogsController::class,'store']);
            Route::post('approve',[\App\Http\Controllers\Admin\AttendanceLogsController::class,'approve'])->name('admin.employee.attendance.logs.approve');
            Route::get('edit/{id}',[\App\Http\Controllers\Admin\AttendanceLogsController::class,'edit'])->name('admin.employee.attendance.logs.edit');
            Route::post('update',[\App\Http\Controllers\Admin\AttendanceLogsController::class,'update'])->name('admin.employee.attendance.logs.update');
            Route::post('delete',[\App\Http\Controllers\Admin\AttendanceLogsController::class,'delete'])->name('admin.employee.attendance.logs.delete');
            Route::get('extract/{id}',[\App\Http\Controllers\Admin\AttendanceLogsController::class,'extract'])->name('admin.employee.attendance.logs.extract');
            Route::post('get-logs',[\App\Http\Controllers\Admin\AttendanceLogsController::class,'get_csv_column_values'])->name('admin.employee.attendance.logs.by.period');Route::post('insert-attendance-logs',[\App\Http\Controllers\Admin\AttendanceController::class,'insert_attendance_log_from_csv_column'])->name('admin.employee.attendance.log.from.csv');
        });

        /* salary slip */
        Route::group(['prefix' => 'salary-slip'],function (){
            Route::get('all',[\App\Http\Controllers\Admin\EmployeeSalarySlipController::class,'index'])->name('admin.employee.salary.slip');
            Route::get('create',[\App\Http\Controllers\Admin\EmployeeSalarySlipController::class,'create'])->name('admin.employee.salary.slip.create');
            Route::post('create',[\App\Http\Controllers\Admin\EmployeeSalarySlipController::class,'store']);
            Route::get('edit/{id}',[\App\Http\Controllers\Admin\EmployeeSalarySlipController::class,'edit'])->name('admin.employee.salary.slip.edit');
            Route::get('view/{id}',[\App\Http\Controllers\Admin\EmployeeSalarySlipController::class,'view'])->name('admin.employee.salary.slip.view');
            Route::post('update',[\App\Http\Controllers\Admin\EmployeeSalarySlipController::class,'update'])->name('admin.employee.salary.slip.update');
            Route::post('delete/{id}',[\App\Http\Controllers\Admin\EmployeeSalarySlipController::class,'delete'])->name('admin.employee.salary.slip.delete');
        });

    });

    /*=================================
        EARNING ROUTES
    ==================================*/
    Route::group(['prefix' => 'earning'],function (){
        Route::get('/all',[\App\Http\Controllers\Admin\EarningController::class,'index'])->name('admin.earning.all');
        Route::get('/new',[\App\Http\Controllers\Admin\EarningController::class,'new'])->name('admin.earning.new');
        Route::post('/new',[\App\Http\Controllers\Admin\EarningController::class,'store']);
        Route::post('/update',[\App\Http\Controllers\Admin\EarningController::class,'update'])->name('admin.earning.update');
        Route::post('/delete',[\App\Http\Controllers\Admin\EarningController::class,'delete'])->name('admin.earning.delete');
        Route::get('/calculate/{id}',[\App\Http\Controllers\Admin\EarningController::class,'calculate'])->name('admin.earning.calculate');
        Route::get('/settings',[\App\Http\Controllers\Admin\EarningController::class,'settings_page'])->name('admin.earning.settings');
        Route::post('/settings',[\App\Http\Controllers\Admin\EarningController::class,'update_settings']);
    });
    /*=================================
        LEAVES ROUTES
    ==================================*/
    Route::group(['prefix' => 'leaves'],function (){
        Route::get('/all',[\App\Http\Controllers\Admin\LeavesController::class,'index'])->name('admin.leaves.all');
    });

    /*=================================
       PRODUCT ROUTES
   ==================================*/
    Route::group(['prefix' => 'products'],function (){
        Route::get('/all',[\App\Http\Controllers\Admin\ProductsController::class,'index'])->name('admin.products.all');
        Route::get('/new',[\App\Http\Controllers\Admin\ProductsController::class,'new'])->name('admin.products.new');
        Route::post('/new',[\App\Http\Controllers\Admin\ProductsController::class,'store']);
        Route::get('/edit/{id}',[\App\Http\Controllers\Admin\ProductsController::class,'edit'])->name('admin.products.edit');
        Route::get('/view/{id}',[\App\Http\Controllers\Admin\ProductsController::class,'view'])->name('admin.products.view');
        Route::post('/update',[\App\Http\Controllers\Admin\ProductsController::class,'update'])->name('admin.products.update');
        Route::post('/delete',[\App\Http\Controllers\Admin\ProductsController::class,'delete'])->name('admin.products.delete');
        Route::post('/calculate',[\App\Http\Controllers\Admin\ProductsController::class,'calculate'])->name('admin.products.fetch');
    });

  /*=================================
       USERS ROUTES
   ==================================*/
    Route::group(['prefix' => 'users'],function (){
        Route::get('/all',[\App\Http\Controllers\Admin\UserManageController::class,'index'])->name('admin.users.all');
        Route::get('/new',[\App\Http\Controllers\Admin\UserManageController::class,'new'])->name('admin.users.new');
        Route::post('/new',[\App\Http\Controllers\Admin\UserManageController::class,'store']);
        Route::get('/edit/{id}',[\App\Http\Controllers\Admin\UserManageController::class,'edit'])->name('admin.users.edit');
        Route::get('/view/{id}',[\App\Http\Controllers\Admin\UserManageController::class,'view'])->name('admin.users.view');
        Route::post('/update',[\App\Http\Controllers\Admin\UserManageController::class,'update'])->name('admin.users.update');
        Route::post('/delete',[\App\Http\Controllers\Admin\UserManageController::class,'delete'])->name('admin.users.delete');
        Route::post('/convert-to-employee',[\App\Http\Controllers\Admin\UserManageController::class,'convert_to_employee'])->name('admin.users.make.employee');
        Route::post('/resend-verify-mail',[\App\Http\Controllers\Admin\UserManageController::class,'resend_verify_mail'])->name('admin.users.resend.verify.mail');
        Route::post('/ban-user',[\App\Http\Controllers\Admin\UserManageController::class,'ban_user'])->name('admin.users.ban.user');
        Route::post('/change-password',[\App\Http\Controllers\Admin\UserManageController::class,'change_password'])->name('admin.users.change.password');
        Route::post('/disable-mail-verify',[\App\Http\Controllers\Admin\UserManageController::class,'disable_mail_verify'])->name('admin.users.disable.mail.verify');
    });

});
