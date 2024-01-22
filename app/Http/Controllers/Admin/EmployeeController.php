<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\EmployeeRequest;
use App\Http\Traits\AttendanceHelper;
use App\Models\AttendanceLog;
use App\Models\Attendance;
use App\Models\Employee;
use App\Models\EmployeeCategory;
use App\Models\User;
use App\Models\AdvanceSalary;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Inertia\Inertia;
use function GuzzleHttp\Promise\all;

class EmployeeController extends Controller
{
    use AttendanceHelper;

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

        $logQeuery =  AttendanceLog::query()->where(['employee_id' =>  $request->employee_id]);
        if (!empty($request->startDate)){
            $logQeuery->whereBetween('date_time',[Carbon::parse($request->startDate)->startOfMonth(),Carbon::parse($request->startDate)->endOfMonth()]);
        }

        if (!empty($request->filter)){
            $logQeuery->whereIn("type",$request->filter);
        }else{
            $logQeuery->whereIn("type",["holiday","C/In","C/Out","leave","sick-leave","work-from-home","paid-leave"]);
        }


        $logs = $logQeuery->where("status",1)->orderBy("date_time")->get();
        $logsInfo = [];

        foreach($logs as $log){
            $parsed_date = Carbon::parse($log->date_time)->format("d-m-Y");
            if (isset($logsInfo[$parsed_date])){
                //alreayd have this day index
                if ($log->type == "C/Out"){
                    //added Out_time
                    $logsInfo[$parsed_date][str_replace("c/","",strtolower($log->type))."_time"] =
                        $log->type === "holiday" ? " ": Carbon::parse($log->date_time)->format('g:i A');

                    //todo if in time available then calculate total office hour
                    if (isset($logsInfo[$parsed_date]["in_time"])){
                        $dt_str = $parsed_date." ".$logsInfo[$parsed_date]["in_time"];
                        $check_intime = Carbon::parse($dt_str);
                        $logsInfo[$parsed_date]["working_hour"] = $check_intime->diff(Carbon::parse($log->date_time))->format("%H:%I:%S");
                    }
                }
                $logsInfo[$parsed_date]["working_nature"] = $this->workNature($log->type);
            }else{
                //added in_time
                $logsInfo[$parsed_date] = [
                    str_replace("c/","",strtolower($log->type))."_time" =>
                        $log->type === "holiday" ? " ": Carbon::parse($log->date_time)->format('g:i A'),
                    "working_nature" => $this->workNature($log->type),
                    "dateTime" => $log->date_time

                ];
            }
            //if found cout/cin then show total office hour
        }

        $holidayCount = $logs->where('type','holiday')->count();
        $leaveCount = $logs->where('type','leave')->count();
        $OfficeDays = 0;
        $sickLeaveCount = $logs->where('type','sick-leave')->count();
        $paidLeaveCount =$logs->where('type','paid-leave')->count();
        $workFormHome =$logs->where('type','work-from-home')->count();
        foreach( $logsInfo as $log){
            if($log['working_nature'] === 'Office'){
                $OfficeDays++;
            }
        }



        return response()->json([
            'logs' => $logsInfo,
              'holidayCount' => $holidayCount ?? 0 ,
              'leaveCount' => $leaveCount?? 0,
              'OfficeDays' => $OfficeDays ?? 0,
              'sickLeaveCount' => $sickLeaveCount ?? 0,
              'paidLeaveCount' => $paidLeaveCount ?? 0,
              'workFormHome' => $workFormHome ?? 0,
        ]);
    }

    public function index(){

        $allEmployees  = Employee::with(['category','user'])
        ->with('attendanceLog',function($q){
            $q->whereYear('date_time',Carbon::today())->WhereIn('type',['leave','sick-leave','paid-leave']);
        })
        ->orderBy('id','desc')
        ->paginate(10);
        //Attendance
        //todo:: calculate total leave in this year

        return Inertia::render('Backend/Employees/Employee',[
           'allEmployees' => $allEmployees
        ]);
    }
    public function attenadance_check($id){

        return Inertia::render('Backend/Employees/AttendanceCheck',[
            'allEmployees' => Employee::find($id)
         ]);
    }


    public function attenadance_check_global(){
        $all_employee = Employee::where('status',1)->get()->map(function ($item){
            return ['label' => $item->name,'value' => $item->id];
        });

        return Inertia::render('Backend/Employees/AttendanceCheckGlobal',[
           'allEmployees' => $all_employee
        ]);
    }
    public function details($id,Request $request){
        $details =  Employee::with('category')->find($id);
        $details->designation = optional($details->category)->title;
        //todo
        $logs =  AttendanceLog::query()
            ->where(['employee_id' => $id])
            ->where("status",1)
            ->whereBetween('date_time',[Carbon::parse($request->month)->startOfMonth(),Carbon::parse($request->month)->endOfMonth()])
            ->get();

            $logsInfo = [];

            foreach($logs as $log){
                $parsed_date = Carbon::parse($log->date_time)->format("d-m-Y");
                if (isset($logsInfo[$parsed_date])){
                    //alreayd have this day index
                    if ($log->type == "C/Out"){
                        //added Out_time
                        $logsInfo[$parsed_date][str_replace("c/","",strtolower($log->type))."_time"] =
                            $log->type === "holiday" ? " ": Carbon::parse($log->date_time)->format('g:i A');

                        //todo if in time available then calculate total office hour
                        if (isset($logsInfo[$parsed_date]["in_time"])){
                            $dt_str = $parsed_date." ".$logsInfo[$parsed_date]["in_time"];
                            $check_intime = Carbon::parse($dt_str);
                            $logsInfo[$parsed_date]["working_hour"] = $check_intime->diff(Carbon::parse($log->date_time))->format("%H:%I:%S");
                        }
                    }
                    $logsInfo[$parsed_date]["working_nature"] = $this->workNature($log->type);
                }else{
                    //added in_time

                    $logsInfo[$parsed_date] = [
                        str_replace("c/","",strtolower($log->type))."_time" =>
                            $log->type === "holiday" ? " ": Carbon::parse($log->date_time)->format('g:i A'),
                        "working_nature" => $this->workNature($log->type),
                        "dateTime" => $log->date_time

                    ];
                }
                //if found cout/cin then show total office hour
            }

            //todo:: need to work here
        $holidayCount = $logs->where('type','holiday')->count();
        $leaveCount = $logs->where('type','leave')->count();
        $sickLeaveCount = $logs->where('type','sick-leave')->count();
        $paidLeaveCount = $logs->where('type','paid-leave')->count();
        $attenadnceCount = 0;

        foreach( $logsInfo as $log){
            if($log['working_nature'] === 'Office'){
                $attenadnceCount++;
            }
        }

        //advance salary this month
        $AdvanceSalary = AdvanceSalary::where(['employee_id' => $id])->whereMonth("month",Carbon::parse($request->month))->get()->pluck("amount")->sum();

        return response([
            'details' => $details,
            'holidayCount' => $holidayCount ?? 0 ,
            'leaveCount' => $leaveCount?? 0,
            'sickLeaveCount' => $sickLeaveCount ?? 0,
            'paidLeaveCount' => $paidLeaveCount ?? 0,
            'attenadnceCount' => $attenadnceCount ?? 0,
            'AdvanceSalary' => $AdvanceSalary ?? 0
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
