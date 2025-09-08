<?php

/*=======================================
    ADMIN DASHBOARD ROUTES
=======================================*/
Route::group(['prefix' => 'admin-home','middleware' => ['auth:admin']],function (){
    Route::get('/',[\App\Http\Controllers\Admin\AdminDashboardController::class,'index'])->name('admin.home');


    /*=================================
        GENERAL SETTINGS ROUTES
    ==================================*/
    Route::group(['prefix' => 'general'],function (){
        Route::get('/settings',[\App\Http\Controllers\Admin\GeneralSettingsController::class,'settings'])->name('admin.general.settings');
        Route::post('/database-upgrade',[\App\Http\Controllers\Admin\GeneralSettingsController::class,'databaseUpdate'])->name('admin.database.upgrade');
        Route::post('/smtp-test',[\App\Http\Controllers\Admin\GeneralSettingsController::class,'smtpTest'])->name('admin.smtp.test');
        Route::get('/sync-data',[\App\Http\Controllers\Admin\GeneralSettingsController::class,'syncData'])->name('admin.general.sync.data');
    });

    /*=================================
       NOTICE ROUTES
   ==================================*/
    Route::group(['prefix' => 'notice'],function (){
        Route::get('/all',[\App\Http\Controllers\Admin\NoticeController::class,'index'])->name('admin.notice');
        Route::post('/new',[\App\Http\Controllers\Admin\NoticeController::class,'store'])->name('admin.notice.create');
    });


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
        Route::get('/attendancelogs/check',[\App\Http\Controllers\Admin\EmployeeController::class,'attenadance_check_global'])->name('admin.employee.attendance.check.global');
        Route::post('/attendancelogs/check',[\App\Http\Controllers\Admin\EmployeeController::class,'attenadance_check_global']);
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
//            Route::post('insert-attendance-logs',[\App\Http\Controllers\Admin\AttendanceController::class,'insert_attendance_log_from_csv_column'])->name('admin.employee.attendance.log.from.csv');
            Route::post('new-attendance-log',[\App\Http\Controllers\Admin\AttendanceController::class,'new_attendance_log'])->name('admin.employee.attendance.log.add');
        });

        /* attendance logs */
        Route::prefix("attendancelogs")->group(function (){
            Route::get('all',[\App\Http\Controllers\Admin\AttendanceLogsController::class,'index'])->name('admin.employee.attendance.logs');
            Route::get('request',[\App\Http\Controllers\Admin\AttendanceLogsController::class,'attendanceRequest'])->name('admin.employee.attendance.request');
            Route::get('create',[\App\Http\Controllers\Admin\AttendanceLogsController::class,'create'])->name('admin.employee.attendance.logs.create');
            Route::post('create',[\App\Http\Controllers\Admin\AttendanceLogsController::class,'store']);
            Route::post('approve',[\App\Http\Controllers\Admin\AttendanceLogsController::class,'approve'])->name('admin.employee.attendance.logs.approve');
            Route::get('edit/{id}',[\App\Http\Controllers\Admin\AttendanceLogsController::class,'edit'])->name('admin.employee.attendance.logs.edit');
            Route::post('update',[\App\Http\Controllers\Admin\AttendanceLogsController::class,'update'])->name('admin.employee.attendance.logs.update');
            Route::post('delete',[\App\Http\Controllers\Admin\AttendanceLogsController::class,'delete'])->name('admin.employee.attendance.logs.delete');
            Route::get('extract/{id}',[\App\Http\Controllers\Admin\AttendanceLogsController::class,'extract'])->name('admin.employee.attendance.logs.extract');
            Route::post('get-logs',[\App\Http\Controllers\Admin\AttendanceLogsController::class,'get_csv_column_values'])->name('admin.employee.attendance.logs.by.period');
            Route::post('approve-all',[\App\Http\Controllers\Admin\AttendanceLogsController::class,'approve_all_pending_request'])->name('admin.employee.attendance.logs.approve.pending');
            Route::post('insert-attendance-logs',[\App\Http\Controllers\Admin\AttendanceController::class,'insert_attendance_log_from_csv_column'])->name('admin.employee.attendance.log.from.csv');
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
        FINANCE ROUTES
    ==================================*/
    Route::group(['prefix' => 'finance'],function (){
        Route::get('/dashboard',[\App\Http\Controllers\Admin\FinanceController::class,'dashboard'])->name('admin.finance.dashboard');
        Route::get('/dashboard/load/{month}',[\App\Http\Controllers\Admin\FinanceController::class,'loadDashboardData'])->name('admin.finance.dashboard.load');
        Route::get('/balance-sheet',[\App\Http\Controllers\Admin\FinanceController::class,'balanceSheet'])->name('admin.finance.balance.sheet');
        Route::post('/balance-sheet/save',[\App\Http\Controllers\Admin\FinanceController::class,'saveBalanceSheet'])->name('admin.finance.balance.sheet.save');
        Route::get('/balance-sheet/load/{date}',[\App\Http\Controllers\Admin\FinanceController::class,'loadBalanceSheet'])->name('admin.finance.balance.sheet.load');
        Route::get('/balance-sheet/export/{date}',[\App\Http\Controllers\Admin\FinanceController::class,'exportBalanceSheet'])->name('admin.finance.balance.sheet.export');
        Route::get('/balance-sheet/forecast/{date}',[\App\Http\Controllers\Admin\FinanceController::class,'generateForecast'])->name('admin.finance.balance.sheet.forecast');
        Route::post('/balance-sheet/apply-forecast',[\App\Http\Controllers\Admin\FinanceController::class,'applyForecast'])->name('admin.finance.balance.sheet.apply.forecast');
        
        // Recurring Expenses Management
        Route::get('/recurring-expenses',[\App\Http\Controllers\Admin\FinanceController::class,'recurringExpenses'])->name('admin.finance.recurring.expenses');
        Route::post('/recurring-expenses/store',[\App\Http\Controllers\Admin\FinanceController::class,'storeRecurringExpense'])->name('admin.finance.recurring.expenses.store');
        Route::put('/recurring-expenses/{id}',[\App\Http\Controllers\Admin\FinanceController::class,'updateRecurringExpense'])->name('admin.finance.recurring.expenses.update');
        Route::delete('/recurring-expenses/{id}',[\App\Http\Controllers\Admin\FinanceController::class,'deleteRecurringExpense'])->name('admin.finance.recurring.expenses.delete');
        
        // Recurring Expense Payment Status Management
        Route::get('/recurring-expenses/with-status',[\App\Http\Controllers\Admin\FinanceController::class,'getRecurringExpensesWithStatus'])->name('admin.finance.recurring.expenses.with-status');
        Route::post('/recurring-expenses/{id}/mark-paid',[\App\Http\Controllers\Admin\FinanceController::class,'markRecurringExpenseAsPaid'])->name('admin.finance.recurring.expenses.mark-paid');
        Route::put('/recurring-expenses/{id}/payment-status',[\App\Http\Controllers\Admin\FinanceController::class,'updateRecurringExpensePaymentStatus'])->name('admin.finance.recurring.expenses.payment-status');
        
        // Assets Management
        Route::get('/assets',[\App\Http\Controllers\Admin\FinanceController::class,'assets'])->name('admin.finance.assets');
        Route::post('/assets/store',[\App\Http\Controllers\Admin\FinanceController::class,'storeAsset'])->name('admin.finance.assets.store');
        Route::put('/assets/{id}',[\App\Http\Controllers\Admin\FinanceController::class,'updateAsset'])->name('admin.finance.assets.update');
        Route::delete('/assets/{id}',[\App\Http\Controllers\Admin\FinanceController::class,'deleteAsset'])->name('admin.finance.assets.delete');
        
        // Equity Management
        Route::get('/equity',[\App\Http\Controllers\Admin\FinanceController::class,'equity'])->name('admin.finance.equity');
        Route::post('/equity/store',[\App\Http\Controllers\Admin\FinanceController::class,'storeEquity'])->name('admin.finance.equity.store');
        Route::put('/equity/{id}',[\App\Http\Controllers\Admin\FinanceController::class,'updateEquity'])->name('admin.finance.equity.update');
        Route::delete('/equity/{id}',[\App\Http\Controllers\Admin\FinanceController::class,'deleteEquity'])->name('admin.finance.equity.delete');
        
        // One-time Expenses
        Route::get('/expenses',[\App\Http\Controllers\Admin\FinanceController::class,'expenses'])->name('admin.finance.expenses');
        Route::get('/expenses/with-search',[\App\Http\Controllers\Admin\FinanceController::class,'getExpensesWithSearch'])->name('admin.finance.expenses.with-search');
        Route::post('/expenses/store',[\App\Http\Controllers\Admin\FinanceController::class,'storeExpense'])->name('admin.finance.expenses.store');
        Route::put('/expenses/{id}',[\App\Http\Controllers\Admin\FinanceController::class,'updateExpense'])->name('admin.finance.expenses.update');
        Route::delete('/expenses/{id}',[\App\Http\Controllers\Admin\FinanceController::class,'deleteExpense'])->name('admin.finance.expenses.delete');
        
        // Recurring Expense Payments
        Route::get('/recurring-payments',[\App\Http\Controllers\Admin\FinanceController::class,'getRecurringPayments'])->name('admin.finance.recurring.payments');
        Route::post('/recurring-payments/store',[\App\Http\Controllers\Admin\FinanceController::class,'storeRecurringPayment'])->name('admin.finance.recurring.payments.store');
        Route::put('/recurring-payments/{id}',[\App\Http\Controllers\Admin\FinanceController::class,'updateRecurringPayment'])->name('admin.finance.recurring.payments.update');
        Route::delete('/recurring-payments/{id}',[\App\Http\Controllers\Admin\FinanceController::class,'deleteRecurringPayment'])->name('admin.finance.recurring.payments.delete');
        Route::post('/recurring-payments/{id}/mark-paid',[\App\Http\Controllers\Admin\FinanceController::class,'markPaymentAsPaid'])->name('admin.finance.recurring.payments.mark-paid');
        
        Route::get('/budgets',[\App\Http\Controllers\Admin\FinanceController::class,'budgets'])->name('admin.finance.budgets');
        Route::get('/reports',[\App\Http\Controllers\Admin\FinanceController::class,'reports'])->name('admin.finance.reports');
        Route::get('/documentation',[\App\Http\Controllers\Admin\FinanceController::class,'documentation'])->name('admin.finance.documentation');

        // Client Management
        Route::get('/clients',[\App\Http\Controllers\Admin\ClientController::class,'index'])->name('admin.finance.clients.index');
        Route::post('/clients',[\App\Http\Controllers\Admin\ClientController::class,'store'])->name('admin.finance.clients.store');
        Route::get('/clients/{id}',[\App\Http\Controllers\Admin\ClientController::class,'show'])->name('admin.finance.clients.show');
        Route::put('/clients/{id}',[\App\Http\Controllers\Admin\ClientController::class,'update'])->name('admin.finance.clients.update');
        Route::delete('/clients/{id}',[\App\Http\Controllers\Admin\ClientController::class,'destroy'])->name('admin.finance.clients.delete');
        Route::patch('/clients/{id}/toggle-status',[\App\Http\Controllers\Admin\ClientController::class,'toggleStatus'])->name('admin.finance.clients.toggle.status');
        
        // Revenue Management
        Route::get('/revenues',[\App\Http\Controllers\Admin\ClientController::class,'getRevenues'])->name('admin.finance.revenues.index');
        Route::post('/revenues',[\App\Http\Controllers\Admin\ClientController::class,'storeRevenue'])->name('admin.finance.revenues.store');
        Route::put('/revenues/{id}',[\App\Http\Controllers\Admin\ClientController::class,'updateRevenue'])->name('admin.finance.revenues.update');
        Route::delete('/revenues/{id}',[\App\Http\Controllers\Admin\ClientController::class,'deleteRevenue'])->name('admin.finance.revenues.delete');
    });

    /*=================================
        LEAVES ROUTES
    ==================================*/
    Route::group(['prefix' => 'leaves'],function (){
        Route::get('/all',[\App\Http\Controllers\Admin\LeavesController::class,'index'])->name('admin.leaves.all');
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
