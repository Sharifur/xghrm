<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use App\Models\Product;
use App\Models\User;
use Illuminate\Http\Request;
use Inertia\Inertia;

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
}
