<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AttendanceLog;
use App\Models\Employee;
use Illuminate\Http\Request;
use Inertia\Inertia;

class LeavesController extends Controller
{
    public function index(){
        $attendance_logs = AttendanceLog::with('employee')->whereIn('type',['leave','sick-leave','paid-leave'])->orderBy('id','desc')->paginate(10);
        $employees = Employee::where('status',1)->get()->map(function ($item){
            return ['label' => $item->name,'value' => $item->id];
        });
        return Inertia::render('Backend/Leaves/Index',[
            'attendance_logs' => $attendance_logs,
            'employees' => $employees,
        ]);
    }
}
