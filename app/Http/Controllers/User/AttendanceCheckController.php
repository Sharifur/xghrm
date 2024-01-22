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

        $logQeuery =  AttendanceLog::query()
            ->where(['employee_id' =>  $user->employee?->id]);
        if (!empty($request->startDate)){
            $logQeuery->whereBetween('date_time',[Carbon::parse($request->startDate)->startOfMonth(),Carbon::parse($request->startDate)->endOfMonth()]);
        }

        if (!empty($request->filter)){
            $logQeuery->whereIn("type",$request->filter);
        }else{
            $logQeuery->whereIn("type",["holiday","C/In","C/Out","leave","sick-leave","work-from-home","paid-leave"]);
        }


        $logs = $logQeuery->where("status",1)->orderBy("date_time")->get();

        $logsInfo = $this->logAsArray(logs:$logs,dateTime:true);



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
