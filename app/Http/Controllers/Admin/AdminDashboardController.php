<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Mail\BasicMail;
use App\Models\Admin;
use App\Models\Product;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
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

}
