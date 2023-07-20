<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Mail\BasicMail;
use App\Models\AttendanceLog;
use App\Models\Employee;
use App\Services\PushNotification;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Inertia\Inertia;

class AttendanceLogsController extends Controller
{
    public function index(Request $request){
        $attendance_logsQuery = AttendanceLog::query();

        if (!empty($request->get("filter"))){
            $attendance_logsQuery->where("status",$request->get("filter"));
        }
        $attendance_logs  = $attendance_logsQuery->with('employee')->orderBy('id','desc')->paginate(10)->withQueryString();

        $employees = Employee::where('status',1)->get()->map(function ($item){
            return ['label' => $item->name,'value' => $item->id];
        });
        return Inertia::render('Backend/AttendanceLogs/Index',[
            'attendance_logs' => $attendance_logs,
            'employees' => $employees,
        ]);
    }
    public function delete(Request $request){
        AttendanceLog::find($request->id)->delete();
        return back();
    }

    public function approve(Request $request){
        AttendanceLog::find($request->id)->update([
            "status" => 1
        ]);
        $att_details = AttendanceLog::with('employee')->find($request->id);
        try{
            $message = 'Hello '.$att_details?->employee?->name.'.<br>';
            $additional_message = 'enjoy your time...';
            if ($att_details->type == 'work-from-home'){
                $additional_message = 'Do not forget to start tracker while you are working.';
            }
            if ($att_details->type == 'sick-leave'){
                $additional_message = 'Take care of your health.';
            }
            $message .= sprintf('your "%s" request for date of "%s" is approved, %s',
                ucwords(str_replace(['-','_'],' ',$att_details->type)),
                Carbon::parse($att_details->date_time)->format('D d-M-Y'),
                $additional_message
            );

            \Mail::to($att_details?->employee?->email)->send(new BasicMail([
                'subject' => sprintf('Your "%s" request has been approved',ucwords(str_replace(['-','_'],' ',$att_details->type))),
                'message' => $message,
            ]));

            //todo push notification
            PushNotification::init()
                ->setServerKey()
                ->setTopicId($att_details?->employee?->user_id)
                ->setData([
                    "id" => $att_details->id,
                    "title" => sprintf('Your "%s" request has been approved',ucwords(str_replace(['-','_'],' ',$att_details->type))),
                    "body" => str_replace("<br>","",$message)
                ])
                ->send();

        }catch (\Exception $e){
            \Log::error($e->getMessage());
        }
        //fire a push notification so that employee can understand his leave,sick
        return back();
    }
}
