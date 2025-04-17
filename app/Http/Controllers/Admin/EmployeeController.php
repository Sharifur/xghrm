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
//    public function attenadance_check_post(Request $request){
//
//        $logQeuery =  AttendanceLog::query()->where(['employee_id' =>  $request->employee_id]);
//        if (!empty($request->startDate)){
//            $logQeuery->whereBetween('date_time',[Carbon::parse($request->startDate)->startOfMonth(),Carbon::parse($request->startDate)->endOfMonth()]);
//        }
//
//        if (!empty($request->filter)){
//            $logQeuery->whereIn("type",$request->filter);
//        }else{
//            $logQeuery->whereIn("type",["holiday","C/In","C/Out","leave","sick-leave","work-from-home","paid-leave"]);
//        }
//
//
//        $logs = $logQeuery->where("status",1)->orderBy("date_time")->get();
//        $logsInfo = [];
//
//        foreach($logs as $log){
//            $parsed_date = Carbon::parse($log->date_time)->format("d-m-Y");
//            if (isset($logsInfo[$parsed_date])){
//                //alreayd have this day index
//                if ($log->type == "C/Out"){
//                    //added Out_time
//                    $logsInfo[$parsed_date][str_replace("c/","",strtolower($log->type))."_time"] =
//                        $log->type === "holiday" ? " ": Carbon::parse($log->date_time)->format('g:i A');
//
//                    //todo if in time available then calculate total office hour
//                    if (isset($logsInfo[$parsed_date]["in_time"])){
//                        $dt_str = $parsed_date." ".$logsInfo[$parsed_date]["in_time"];
//                        $check_intime = Carbon::parse($dt_str);
//                        $logsInfo[$parsed_date]["working_hour"] = $check_intime->diff(Carbon::parse($log->date_time))->format("%H:%I:%S");
//                    }
//                }
//                $logsInfo[$parsed_date]["working_nature"] = $this->workNature($log->type);
//            }else{
//                //added in_time
//                $logsInfo[$parsed_date] = [
//                    str_replace("c/","",strtolower($log->type))."_time" =>
//                        $log->type === "holiday" ? " ": Carbon::parse($log->date_time)->format('g:i A'),
//                    "working_nature" => $this->workNature($log->type),
//                    "dateTime" => $log->date_time
//
//                ];
//            }
//            //if found cout/cin then show total office hour
//        }
//
//        $holidayCount = $logs->where('type','holiday')->count();
//        $leaveCount = $logs->where('type','leave')->count();
//        $OfficeDays = 0;
//        $sickLeaveCount = $logs->where('type','sick-leave')->count();
//        $paidLeaveCount =$logs->where('type','paid-leave')->count();
//        $workFormHome =$logs->where('type','work-from-home')->count();
//        foreach( $logsInfo as $log){
//            if($log['working_nature'] === 'Office'){
//                $OfficeDays++;
//            }
//        }
//
//
//
//        return response()->json([
//            'logs' => $logsInfo,
//              'holidayCount' => $holidayCount ?? 0 ,
//              'leaveCount' => $leaveCount?? 0,
//              'OfficeDays' => $OfficeDays ?? 0,
//              'sickLeaveCount' => $sickLeaveCount ?? 0,
//              'paidLeaveCount' => $paidLeaveCount ?? 0,
//              'workFormHome' => $workFormHome ?? 0,
//        ]);
//    }
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

        // New variables for tracking late arrivals and working hours
        $lateArrivalCount = 0;
        $totalWorkingHours = 0;
        $daysWithCompleteData = 0;
        $totalOfficeWorkingHours = 0;
        $officeWorkingDays = 0;

        foreach($logs as $log){
            $parsed_date = Carbon::parse($log->date_time)->format("d-m-Y");
            $working_nature = $this->workNature($log->type);

            // Skip holiday, leave, sick-leave, and paid-leave completely
            if(in_array($log->type, ["holiday", "leave", "sick-leave", "paid-leave"])) {
                if(!isset($logsInfo[$parsed_date])) {
                    $logsInfo[$parsed_date] = [
                        "working_nature" => $working_nature,
                        "dateTime" => $log->date_time
                    ];
                }
                continue;
            }

            if (isset($logsInfo[$parsed_date])){
                //already have this day index
                if ($log->type == "C/Out"){
                    //added Out_time
                    $out_time = Carbon::parse($log->date_time)->format('g:i A');
                    $logsInfo[$parsed_date][str_replace("c/","",strtolower($log->type))."_time"] = $out_time;
                    $check_outtime = Carbon::parse($log->date_time);

                    //if in time available then calculate total office hour
                    if (isset($logsInfo[$parsed_date]["in_time"])){
                        $dt_str = $parsed_date." ".$logsInfo[$parsed_date]["in_time"];
                        $check_intime = Carbon::parse($dt_str);
                    } else {
                        // If no check-in time, assume 10:00 AM
                        $check_intime = Carbon::parse($parsed_date . " 10:00 AM");
                        $logsInfo[$parsed_date]["in_time"] = "10:00 AM";
                        $logsInfo[$parsed_date]["in_time_assumed"] = true;
                    }

                    // Calculate working hours in decimal form (hours.minutes)
                    $diffInMinutes = $check_outtime->diffInMinutes($check_intime);

                    // Subtract 1 hour (60 minutes) for lunch break
                    $adjustedMinutes = $diffInMinutes - 60;
                    if ($adjustedMinutes < 0) $adjustedMinutes = 0;

                    $hours = floor($adjustedMinutes / 60);
                    $minutes = $adjustedMinutes % 60;
                    $decimalHours = round($hours + ($minutes / 60), 2);

                    // Store both formatted time and decimal hours for calculations
                    $logsInfo[$parsed_date]["working_hour"] = $check_intime->diff($check_outtime)->format("%H:%I:%S");
                    $logsInfo[$parsed_date]["working_hour_decimal"] = $decimalHours;

                    // Add to total for average calculation (only if not holiday, leave, sick-leave, or paid-leave)
                    $totalWorkingHours += $decimalHours;
                    $daysWithCompleteData++;

                    // Only add to office hours total if not working from home
                    if ($working_nature !== 'Work From Home') {
                        $totalOfficeWorkingHours += $decimalHours;
                        $officeWorkingDays++;
                    }
                }
                $logsInfo[$parsed_date]["working_nature"] = $working_nature;
            } else {
                //added in_time
                if ($log->type == "C/In") {
                    $in_time = Carbon::parse($log->date_time)->format('g:i A');
                    $logsInfo[$parsed_date] = [
                        str_replace("c/","",strtolower($log->type))."_time" => $in_time,
                        "working_nature" => $working_nature,
                        "dateTime" => $log->date_time
                    ];

                    // Check if employee arrived after 10:00 AM
                    $inTime = Carbon::parse($log->date_time);
                    $tenAM = Carbon::parse($parsed_date . " 10:15 AM");

                    if ($inTime->gt($tenAM)) {
                        $logsInfo[$parsed_date]["late_arrival"] = true;
                        $lateArrivalCount++;
                    } else {
                        $logsInfo[$parsed_date]["late_arrival"] = false;
                    }
                } else if ($log->type == "C/Out") {
                    // If we have checkout but no checkin
                    $out_time = Carbon::parse($log->date_time)->format('g:i A');
                    $check_outtime = Carbon::parse($log->date_time);

                    // Assume 10:00 AM check-in
                    $check_intime = Carbon::parse($parsed_date . " 10:00 AM");

                    $logsInfo[$parsed_date] = [
                        "out_time" => $out_time,
                        "in_time" => "10:00 AM",
                        "in_time_assumed" => true,
                        "working_nature" => $working_nature,
                        "dateTime" => $log->date_time
                    ];

                    // Calculate working hours
                    $diffInMinutes = $check_outtime->diffInMinutes($check_intime);
                    $adjustedMinutes = $diffInMinutes - 60; // Subtract lunch
                    if ($adjustedMinutes < 0) $adjustedMinutes = 0;

                    $hours = floor($adjustedMinutes / 60);
                    $minutes = $adjustedMinutes % 60;
                    $decimalHours = round($hours + ($minutes / 60), 2);

                    $logsInfo[$parsed_date]["working_hour"] = $check_intime->diff($check_outtime)->format("%H:%I:%S");
                    $logsInfo[$parsed_date]["working_hour_decimal"] = $decimalHours;

                    $totalWorkingHours += $decimalHours;
                    $daysWithCompleteData++;

                    if ($working_nature !== 'Work From Home') {
                        $totalOfficeWorkingHours += $decimalHours;
                        $officeWorkingDays++;
                    }
                } else {
                    $logsInfo[$parsed_date] = [
                        "working_nature" => $working_nature,
                        "dateTime" => $log->date_time
                    ];
                }
            }
        }

        // Check for days with only check-in times and no check-out
        foreach($logsInfo as $date => &$log) {
            if (isset($log["in_time"]) && !isset($log["out_time"]) &&
                !in_array($log["working_nature"], ["Holiday", "Leave", "Sick Leave", "Paid Leave"])) {
                // Assume 7:00 PM check-out
                $check_intime = Carbon::parse($date . " " . $log["in_time"]);
                $check_outtime = Carbon::parse($date . " 7:00 PM");

                $log["out_time"] = "7:00 PM";
                $log["out_time_assumed"] = true;

                // Calculate working hours
                $diffInMinutes = $check_outtime->diffInMinutes($check_intime);
                $adjustedMinutes = $diffInMinutes - 60; // Subtract lunch
                if ($adjustedMinutes < 0) $adjustedMinutes = 0;

                $hours = floor($adjustedMinutes / 60);
                $minutes = $adjustedMinutes % 60;
                $decimalHours = round($hours + ($minutes / 60), 2);

                $log["working_hour"] = $check_intime->diff($check_outtime)->format("%H:%I:%S");
                $log["working_hour_decimal"] = $decimalHours;

                $totalWorkingHours += $decimalHours;
                $daysWithCompleteData++;

                if ($log["working_nature"] !== 'Work From Home') {
                    $totalOfficeWorkingHours += $decimalHours;
                    $officeWorkingDays++;
                }
            }
        }

        // Calculate average working hours if we have valid data
        $avgWorkingHours = $daysWithCompleteData > 0 ? round($totalWorkingHours / $daysWithCompleteData, 2) : 0;

        // Calculate average working hours for office days only (excluding work-from-home)
        $avgOfficeWorkingHours = $officeWorkingDays > 0 ? round($totalOfficeWorkingHours / $officeWorkingDays, 2) : 0;

        // Format average working hours as HH:MM
        $avgHours = floor($avgWorkingHours);
        $avgMinutes = round(($avgWorkingHours - $avgHours) * 60);
        // Adjust for cases where minutes equal 60
        if ($avgMinutes == 60) {
            $avgHours++;
            $avgMinutes = 0;
        }
        $formattedAvgWorkingHours = sprintf("%02d:%02d", $avgHours, $avgMinutes);

        // Format average office working hours as HH:MM
        $avgOfficeHours = floor($avgOfficeWorkingHours);
        $avgOfficeMinutes = round(($avgOfficeWorkingHours - $avgOfficeHours) * 60);
        // Adjust for cases where minutes equal 60
        if ($avgOfficeMinutes == 60) {
            $avgOfficeHours++;
            $avgOfficeMinutes = 0;
        }
        $formattedAvgOfficeWorkingHours = sprintf("%02d:%02d", $avgOfficeHours, $avgOfficeMinutes);

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
            'holidayCount' => $holidayCount ?? 0,
            'leaveCount' => $leaveCount ?? 0,
            'OfficeDays' => $OfficeDays ?? 0,
            'sickLeaveCount' => $sickLeaveCount ?? 0,
            'paidLeaveCount' => $paidLeaveCount ?? 0,
            'workFormHome' => $workFormHome ?? 0,
            'lateArrivalCount' => $lateArrivalCount ?? 0,
            'avgWorkingHours' => $formattedAvgWorkingHours,
            'avgOfficeWorkingHours' => $formattedAvgOfficeWorkingHours,
            'avgWorkingHoursDecimal' => $avgWorkingHours,
            'avgOfficeWorkingHoursDecimal' => $avgOfficeWorkingHours,
            'daysWithCompleteData' => $daysWithCompleteData,
            'officeWorkingDays' => $officeWorkingDays
        ]);
    }
    public function index(){

        $allEmployees  = Employee::with(['category','user'])
        ->with('attendanceLog',function($q){
            $q->whereYear('date_time',Carbon::today())->WhereIn('type',['leave','sick-leave','paid-leave']);
        })
        ->orderBy('status','desc')
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
        $AdvanceSalary = AdvanceSalary::where(['employee_id' => $id])
            ->whereMonth("month",Carbon::parse($request->month))
            ->whereYear('month',Carbon::now()->year)
            ->get()->pluck("amount")->sum();

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
