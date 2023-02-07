<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use App\Models\Product;
use App\Models\User;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\Artisan;

class AdminDashboardController extends Controller
{
    public function index(){
        return Inertia::render('Backend/Dashboard',[
           'products' => Product::count(),
            'users' => User::count(),
            'tickets' => 00,
            'admins' => Admin::count(),
        ]);
    }
    public function databaseUpdate(){
        setEnvValue(['APP_ENV' => 'local']);
        Artisan::call('migrate', ['--force' => true ]);
        Artisan::call('db:seed', ['--force' => true ]);
        Artisan::call('cache:clear');
        setEnvValue(['APP_ENV' => 'production']);
        return back();
    }
}
