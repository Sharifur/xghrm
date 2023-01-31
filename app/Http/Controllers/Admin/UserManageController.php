<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\ConvertUserToEmployeeRequest;
use App\Http\Requests\EmployeeRequest;
use App\Models\Employee;
use App\Models\EmployeeCategory;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Inertia\Inertia;

class UserManageController extends Controller
{
    public function index(){
        $category_list = EmployeeCategory::where('status',1)->get()->map(function ($item){
            return ['value' => $item->id,'label' => $item->title];
        });
        $allEmployees  = Employee::orderBy('id','desc')->get()->map(function ($item){
            return ['value' => $item->id,'label' => $item->name];
        });
        return Inertia::render('Backend/UserManage/AllUsers',[
            'users' => User::with('employee')->paginate(10),
            'category' => $category_list,
            'employees' => $allEmployees
        ]);
    }

    public function delete(Request $request){
        User::find($request->id)->delete();
      return  back();
    }
    public function view($id){
      return Inertia::render('Backend/UserManage/View',['userInfo' => User::find($id)]);
    }
    public function edit($id){
      return Inertia::render('Backend/UserManage/Edit',['userInfo' => User::find($id)]);
    }
    public function ban_user(Request $request){
        User::find($request->id)->update([
            'banned' => $request->banned === 0 ? 1 : 0,
        ]);
     return back();
    }
    public function resend_verify_mail(Request $request){
        $user = User::find($request->id);
        event(new Registered($user));
     return back();
    }
    public function change_password(Request $request){
        $this->validate($request,[
           'id' => 'required',
           'password' => 'required|confirmed'
        ]);
        User::find($request->id)->update(['password' => Hash::make($request->password)]);
     return back();
    }
    public function disable_mail_verify(Request $request){
        $user = User::find($request->id)->update([
            'email_verified_at' => is_null($request->email_verified_at) ? Carbon::now() : null
        ]);
        event(new Registered($user));
     return back();
    }
    public function update(Request $request){

        $this->validate($request,[
            'name' => 'nullable|string',
            'email' => 'nullable|string',
            'phone' => 'nullable|string',
            'address' => 'nullable|string',
            'state' => 'nullable|string',
            'city' => 'nullable|string',
            'zipcode' => 'nullable|string',
            'country' => 'nullable|string',
            'image' => 'nullable|numeric',
        ]);

        User::find($request->id)->update([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'address' => $request->address,
            'state' => $request->state,
            'city' => $request->city,
            'zipcode' => $request->zipcode,
            'country' => $request->country,
            'image' => $request->image,
        ]);

        return back();
    }

    public function convert_to_employee(ConvertUserToEmployeeRequest $request){

        if ($request->employee_type === 'existing'){
            Employee::find($request->existing_employee_id)->update(['user_id' => $request->userId]);
            return back();
        }

        $user_details = User::find($request->userId);
        Employee::create([
            'user_id' => $user_details->id,
            'address' => $request->address ?? $user_details->address,
            'catId' =>  $request->catId,
            'dateOfBirth' => $request->dateOfBirth,
            'joinDate' => $request->joinDate,
            'emergencyNumber' => $request->emergencyNumber,
            'employee_type' => $request->employee_type,
            'imageId' =>  $request->imageId,
            'status' =>  $request->status,
            'personalInfo' =>  $request->personalInfo
        ]);

        return back();
    }
}
