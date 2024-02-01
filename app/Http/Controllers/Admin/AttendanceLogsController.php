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
    public function attendanceRequest(Request $request){
        $attendance_logsQuery = AttendanceLog::query();

        if (!empty($request->get("filter"))){
            $attendance_logsQuery->where("status",$request->get("filter"));
        }
        $attendance_logs  = $attendance_logsQuery->with('employee')->where('status' , 0)->orderBy('id','desc')->paginate(10)->withQueryString();

        $employees = Employee::where('status',1)->get()->map(function ($item){
            return ['label' => $item->name,'value' => $item->id];
        });

        return Inertia::render('Backend/AttendanceLogs/Index',[
            'attendance_logs' => $attendance_logs,
            'employees' => $employees,
            'page_type' => 'pending',
        ]);
    }

    public function index(Request $request){


        //add filter option based on user selected param

        $attendance_logsQuery = AttendanceLog::query();

        $attendance_logsQuery->when(!empty($request->employee),function ($q) use($request){
            $q->where('employee_id',$request->employee);
        });

        $attendance_logsQuery->when(!empty($request->status),function ($q) use($request){
            $q->where('status',$request->status);
        });
        $attendance_logsQuery->when(!empty($request->type),function ($q) use($request){
            $q->where('type',$request->type);
        });
        $attendance_logsQuery->when(!empty($request->date),function ($q) use($request){
            $q->whereMonth('date_time',Carbon::parse($request->date)->month);
            $q->whereYear('date_time',Carbon::parse($request->date)->year);
        });

        $attendance_logs  = $attendance_logsQuery
            ->with('employee')
            ->orderBy('id','desc')
            ->paginate(10)
            ->withQueryString();

        $employees = Employee::where('status',1)->get()->map(function ($item){
            return ['label' => $item->name,'value' => $item->id];
        });
        return Inertia::render('Backend/AttendanceLogs/Index',[
            'attendance_logs' => $attendance_logs,
            'employees' => $employees,
            'page_type' => 'index',
            'status' =>$request->status,
            'employee' => $request->employee,
            'type' => $request->type,
            'date' => $request->date,
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

    public function approve_all_pending_request() {

        AttendanceLog::where(['status' => 0])->update([
            "status" => 1
        ]);

        return back();
    }
}
