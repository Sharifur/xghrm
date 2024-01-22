<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\User;
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
        $request->validate([
            'password' => 'required|confirmed|min:6'
        ]);
        $user_id = \Auth::guard('web')->id();
        User::find($user_id)->update([
            'password' => \Hash::make($request->password)
        ]);

        \Auth::guard('web')->logout();

        return redirect()->to(route('login'));
    }

}
