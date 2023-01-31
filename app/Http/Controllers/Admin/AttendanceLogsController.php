<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AttendanceLog;
use App\Models\Employee;
use Illuminate\Http\Request;
use Inertia\Inertia;

class AttendanceLogsController extends Controller
{
    public function index(){
        $attendance_logs = AttendanceLog::with('employee')->orderBy('id','desc')->paginate(10);
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
}
