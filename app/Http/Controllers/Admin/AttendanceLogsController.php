<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AttendanceLog;
use App\Models\Employee;
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
        //fire a push notification so that employee can understand his leave,sick
        return back();
    }
}
