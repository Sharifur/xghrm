<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\EmployeeRequest;
use App\Models\AttendanceLog;
use App\Models\Employee;
use App\Models\EmployeeCategory;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Inertia\Inertia;
use function GuzzleHttp\Promise\all;

class EmployeeController extends Controller
{
    public function new(){
        $category_list = EmployeeCategory::where('status',1)->get()->map(function ($item){
            return ['value' => $item->id,'label' => $item->title];
        });
        return Inertia::render('Backend/Employees/New',[
            'category' => $category_list
        ]);
    }
    public function edit($id){
        $employeeDetails = Employee::find($id);
        $category_list = EmployeeCategory::where('status',1)->get()->map(function ($item){
            return ['value' => $item->id,'label' => $item->title];
        });
        return Inertia::render('Backend/Employees/Edit',[
            'category' => $category_list,
            'employeeDetails' => $employeeDetails,
        ]);
    }
    public function view($id){
        $employeeDetails = Employee::with('category')->find($id);
        return Inertia::render('Backend/Employees/View',[
            'employeeDetails' => $employeeDetails,
        ]);
    }

    public function store(EmployeeRequest $request){
        Employee::create($request->validated());
        return back();
    }
    public function update(EmployeeRequest $request){
        Employee::find($request->id)->update($request->validated());
        return back();
    }
    public function delete(Request $request){
        Employee::find($request->id)->delete();
        return back();
    }
    public function attenadance_check_post(Request $request){
       $logs =  AttendanceLog::query()
        ->where(['employee_id' => $request->employee_id])
        ->whereDate('date_time','>',Carbon::parse($request->startDate))
        ->whereDate('date_time' ,'<',Carbon::parse($request->endDate))
        ->OrWhere('type','holiday')
        ->get();

        $logsInfo = [];
        

       $holidayCount = $logs->where('type','holiday')->count();
       $leaveCount = $logs->where('type','leave')->count();
       $inCount = $logs->where('type','C/In')->count();
       $outCount = $logs->where('type','C/Out')->count();
       $sickLeaveCount = $logs->where('type','sick-leave')->count();
       $paidLeaveCount =$logs->where('type','paid-leave')->count();
       $workFormHome =$logs->where('type','work-form-home')->count();

        return response()->json([
            'logs' => $logs,
              'holidayCount' => $holidayCount ?? 0 ,
              'leaveCount' => $leaveCount?? 0,
              'inCount' => $inCount ?? 0,
              'outCount' => $outCount ?? 0,
              'sickLeaveCount' => $sickLeaveCount ?? 0,
              'paidLeaveCount' => $paidLeaveCount ?? 0,
              'workFormHome' => $workFormHome ?? 0,
        ]);
    }

    public function index(){
        $allEmployees  = Employee::with(['category','user'])->orderBy('id','desc')->paginate(10);
        return Inertia::render('Backend/Employees/Employee',[
           'allEmployees' => $allEmployees
        ]);
    }
    public function attenadance_check($id){
        return Inertia::render('Backend/Employees/AttendanceCheck',[
           'allEmployees' => Employee::find($id)
        ]);
    }
    public function details($id,Request $request){
        $details =  Employee::with('category')->find($id);
        $details->designation = optional($details->category)->title;
        //todo
        $logs =  AttendanceLog::query()
            ->where(['employee_id' => $id])
            ->whereMonth('date_time','>',Carbon::parse($request->month))->get();
        $holidayCount = $logs->where('type','holiday')->count();
        $leaveCount = $logs->where('type','leave')->count();
        $inCount = $logs->where('type','C/In')->count();
        $outCount = $logs->where('type','C/Out')->count();
        $sickLeaveCount = $logs->where('type','sick-leave')->count();
        $paidLeaveCount =$logs->where('type','paid-leave')->count();
        $attenadnceCount = max($inCount ,$outCount);

        return response([
            'details' => $details,
            'holidayCount' => $holidayCount ?? 0 ,
            'leaveCount' => $leaveCount?? 0,
            'inCount' => $inCount ?? 0,
            'outCount' => $outCount ?? 0,
            'sickLeaveCount' => $sickLeaveCount ?? 0,
            'paidLeaveCount' => $paidLeaveCount ?? 0,
            'attenadnceCount' => $attenadnceCount ?? 0
        ]);
    }

    public function convertUser(Request $request){
        $userInfo = User::where('email',$request->email)->first();
        $msg = __('User already Exists');
        $type = 'warning';
        if (is_null($userInfo)){
            $user = User::create([
                'email_verified' => 1,
                'username' => str_replace(' ','_',$request->name),
                'name' => $request->name,
                'email' => $request->email,
                'phone' => $request->mobile,
                'password' => Hash::make(12345678),
            ]);
            Employee::find($request->id)->update([
               'user_id' =>  $user->id
            ]);
            $msg = 'User Create Success';
            $type = 'success';
        }

        return response([
            'msg' => $msg,
            'type' => $type
        ],200);
    }
}
