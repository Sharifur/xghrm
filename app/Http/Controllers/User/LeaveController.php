<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Mail\BasicMail;
use App\Models\AttendanceLog;
use App\Models\Employee;
use App\Models\User;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Inertia\Inertia;

class LeaveController extends Controller
{
    public function index()
    {
        $user_details = Auth::guard('web')->getUser();

        $attendance_logs = AttendanceLog::with('employee')
            ->where('employee_id',$user_details?->employee?->id)
            ->whereIn('type',['leave','sick-leave','paid-leave'])
            ->orderBy('id','desc')
            ->paginate(10);

        $employees = Employee::where('status',1)->get()->map(function ($item){
            return ['label' => $item->name,'value' => $item->id];
        });
        return Inertia::render('User/Leaves/Index',[
            'attendance_logs' => $attendance_logs,
            'employees' => $employees,
        ]);
    }

    public function new_leave(Request $request)
    {
        $userInfo = User::find(\auth("web")->id());
        $request->validate([
            "type"  => "required",
            "date_time"  => "required"
        ]);

        $requested_date = \Illuminate\Support\Carbon::parse($request->date_time);

        if ($requested_date->lessThan(Carbon::today())){
            return response()->json([
                "type" => "danger",
                "msg" => __('please provide a valid date')
            ],422);
        }

        $exists = AttendanceLog::whereDate("date_time","=",\Illuminate\Support\Carbon::parse($request->date_time))->where([ 'employee_id'=> $userInfo->employee->id,  'type'=> $request->type])->exists();



        if ($exists){
            return back()->with([
                "type" => "danger",
                "msg" => __("already send an request")
            ]);
        }

        //todo:: create new attendance log
        if (empty($request->end_date)){
            AttendanceLog::create([
                'employee_id'=> $userInfo->employee->id,
                'type'=> $request->type,
                'date_time'=> $requested_date,
                'name'=> $userInfo->employee->att_id ?? '',
                "status" => 0
            ]);
        }else{
            $date_time = Carbon::parse($request->date_time);
            $end_date = Carbon::parse($request->end_date);
            $listOfDays = CarbonPeriod::create($date_time,$end_date);
            foreach ($listOfDays as $day){
                if ($day->isFriday()) {
                    continue; // Skip Fridays
                }
                AttendanceLog::create([
                    'employee_id'=> $userInfo->employee->id,
                    'type'=> $request->type,
                    'date_time'=> $day,
                    'name'=> $userInfo->employee->att_id ?? '',
                    "status" => 0
                ]);
            }
        }

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

        return back()->with([
            "type" => "success",
            "msg" => "attendance request send success"
        ]);
    }
}
