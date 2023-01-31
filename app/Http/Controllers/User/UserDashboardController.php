<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;

class UserDashboardController extends Controller
{
    public function index(){
        return Inertia::render('User/UserDashboard',[

        ]);
    }

    public function change_password(){
        return Inertia::render('User/ChangePassword');
    }
    public function update_change_password(Request $request){

    }

    public function change_info(){
        return Inertia::render('User/ChangeInfo');
    }

    public function update_change_info(Request $request){

    }
}
