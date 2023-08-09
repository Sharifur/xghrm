<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Services\UserServices;
use App\Mail\BasicMail;
use App\Models\AdvanceSalary;
use App\Models\AttendanceLog;
use App\Models\Employee;
use App\Models\SalarySlip;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class AttendanceController extends Controller
{

    public function atteandacne(Request $request){
        $userInfo = User::find(\auth("sanctum")->id());

        if (is_null($userInfo)){
            return response()->json([
                "type" => "danger",
                "msg" => __("user not found")
            ],422);
        }

        $logQeuery =  AttendanceLog::query()->where(['employee_id' => $userInfo?->employee?->id]);
        if (!empty($request->startDate)){
            $logQeuery->whereDate('date_time','>',Carbon::parse($request->startDate));
        }
        if (!empty($request->endDate)){
            $logQeuery->whereDate('date_time' ,'<',Carbon::parse($request->endDate));
        }
        if (!empty($request->month)){
            $logQeuery->whereMonth('date_time' ,'=',Carbon::parse($request->month));
        }
        if (!empty($request->filter)){
            $logQeuery->whereIn("type",$request->filter);
        }else{
            $logQeuery->whereIn("type",["holiday","C/In","C/Out","leave","sick-leave","work-from-home"]);
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
                    "working_nature" => $this->workNature($log->type)

                ];
            }
            //if found cout/cin then show total office hour
        }

        $holidayCount = $logs->where('type','holiday')->count();
        $leaveCount = $logs->where('type','leave')->count();
        $inCount = $logs->where('type','C/In')->count();
        $outCount = $logs->where('type','C/Out')->count();
        $sickLeaveCount = $logs->where('type','sick-leave')->count();
        $paidLeaveCount =$logs->where('type','paid-leave')->count();
        $workFormHome =$logs->where('type','work-from-home')->count();

        //profile data by date with a group of check in, out time , or type

        return response()->json([
            "type" => "success",
            'logs' => $logsInfo,
            'holidayCount' => $holidayCount ?? 0 ,
            'leaveCount' => $leaveCount?? 0,
            'inCount' => $inCount ?? 0,
            'outCount' => $outCount ?? 0,
            'sickLeaveCount' => $sickLeaveCount ?? 0,
            'paidLeaveCount' => $paidLeaveCount ?? 0,
            'workFormHome' => $workFormHome ?? 0,
        ]);
    }

    private function workNature($type){
        return match ($type){
            "holiday" => "Holiday",
            "C/In", "C/Out" => "Office",
            "leave","sick-leave", => "Leave",
            "work-from-home" => "Remote"
        };
    }

    public function atteandacneCreate(Request $request){
        $userInfo = User::find(\auth("sanctum")->id());

        if (is_null($userInfo)){
            return response()->json([
                "type" => "danger",
                "msg" => __("user not found")
            ],422);
        }

        $validation = Validator::make($request->all(),[
           "type"  => "required",
           "date_time"  => "required"
        ]);

        if ($validation->fails()){
            return response()->json([
                "type" => "danger",
                "msg" => $validation->errors()
            ],422);
        }
        $requested_date = \Illuminate\Support\Carbon::parse($request->date_time);

        if ($requested_date->lessThan(Carbon::today())){
            return response()->json([
                "type" => "danger",
                "msg" => __('please provide a valid date')
            ],422);
        }

        $exists = AttendanceLog::whereDate("date_time","=",\Illuminate\Support\Carbon::parse($request->date_time))->where([ 'employee_id'=> $userInfo->employee->id,  'type'=> $request->type])->exists();



        if ($exists){
            return response()->json([
                "type" => "danger",
                "msg" => __("already send an request")
            ],422);
        }

        //todo:: create new attendance log
        AttendanceLog::create([
            'employee_id'=> $userInfo->employee->id,
            'type'=> $request->type,
            'date_time'=> $requested_date,
            'name'=> $userInfo->employee->att_id ?? '',
            "status" => 0
        ]);
        try{
            $message = sprintf('A "%s" request received from "%s" for the date of "%s"',ucwords(str_replace(['-','_'],' ',$request->type)),$userInfo->name,Carbon::parse($request->date_time)->format('D d-M-Y'));
            \Mail::to('hr@xgenious.com')->send(new BasicMail([
                'subject' => sprintf('A new "%s" request received.',ucwords(str_replace(['-','_'],' ',$request->type))),
                'message' => $message,
            ]));
            \Mail::to('dvrobin4@gmail.com')->send(new BasicMail([
                'subject' => sprintf('A new "%s" request received.',ucwords(str_replace(['-','_'],' ',$request->type))),
                'message' => $message,
            ]));
        }catch (\Exception $e){
            \Log::error($e->getMessage());
        }

        return response()->json([
            "type" => "success",
            "msg" => "attendance request send success"
        ]);
    }
}
