<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Traits\AttendanceHelper;
use App\Models\AttendanceLog;
use App\Models\Employee;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class AttendanceCheckController extends Controller
{
    use AttendanceHelper;

    public function index()
    {
        $employee_id = optional(Auth::guard('web')->user()->employee)->id;
        if (is_null($employee_id)){
            return redirect()->route('user.home');
        }
        return Inertia::render('User/AttendanceCheck',[
            'allEmployees' => Employee::find(optional(Auth::guard('web')->user()->employee)->id)
        ]);
    }

    public function check(Request $request){

        $user = Auth::guard('web')->getUser();
        $logQeuery =  AttendanceLog::query()->where(['employee_id' =>  $user->employee?->id]);
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
        $workFormHome =$logs->where('type','work-form-home')->count();
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
}
