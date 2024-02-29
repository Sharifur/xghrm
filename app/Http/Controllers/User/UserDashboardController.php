<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Employee;
use App\Models\User;
use Illuminate\Http\Request;
use Inertia\Inertia;

class UserDashboardController extends Controller
{
    public function index(){
        return Inertia::render('User/UserDashboard',[

        ]);
    }

    public function update_payment_info(Request $request){
        if (strtolower($request->method()) === 'post'){
            $request->validate([
                'paymentInfo' => 'required|max:500'
            ]);
            Employee::where('user_id',auth('web')->id())->update([
                'paymentInfo' => $request->paymentInfo
            ]);
            return back();
        }
        return Inertia::render('User/PaymentInfo');
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
