<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\AttendanceLog;
use App\Models\Employee;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class AttendanceCheckController extends Controller
{
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

        $logs =  AttendanceLog::query()
            ->where(['employee_id' => optional(Auth::guard('web')->user()->employee)->id])
            ->whereDate('date_time','>',Carbon::parse($request->startDate))
            ->whereDate('date_time' ,'<',Carbon::parse($request->endDate))
            ->OrWhere('type','holiday')->get();

        $holidayCount = $logs->where('type','holiday')->count();
        $leaveCount = $logs->where('type','leave')->count();
        $inCount = $logs->where('type','C/In')->count();
        $outCount = $logs->where('type','C/Out')->count();
        $sickLeaveCount = $logs->where('type','sick-leave')->count();
        $paidLeaveCount =$logs->where('type','paid-leave')->count();
        $workFromHomeCount =$logs->where('type','work-from-home')->count();

        return response()->json([
            'logs' => $logs,
            'holidayCount' => $holidayCount ?? 0 ,
            'leaveCount' => $leaveCount?? 0,
            'inCount' => $inCount ?? 0,
            'outCount' => $outCount ?? 0,
            'sickLeaveCount' => $sickLeaveCount ?? 0,
            'paidLeaveCount' => $paidLeaveCount ?? 0,
            'workFromHomeCount' => $workFromHomeCount ?? 0
        ]);
    }
}
