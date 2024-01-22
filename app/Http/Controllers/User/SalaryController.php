<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\AttendanceLog;
use App\Models\SalarySlip;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Inertia\Inertia;

class SalaryController extends Controller
{
    public function index(){
        $user = \Auth::guard('web')->getUser();
        $allSalaries = SalarySlip::with('employee')
            ->where(['employee_id' => $user->employee?->id])
            ->orderBy('created_at','desc')
            ->paginate(10)
            ->through(function($item){
                $item->monthName = Carbon::parse($item->month)->format('M');
                $item->year = Carbon::parse($item->month)->format('Y');
                $item->created = Carbon::parse($item->created_at)->format('Y M d');
                return $item;
            });

        return Inertia::render('User/SalaraySlip/Index',[
            'allSalaries' => $allSalaries,
        ]);
    }

    public function view_details($id){

        $user = \Auth::guard('web')->getUser();

        $salarySlipData = SalarySlip::with(["employee" => function($q){
            $q->with("category")->get();
        }])->where(['id' => $id, 'employee_id' =>  $user->employee?->id ])->first();

        $salarySlipData->designation = $salarySlipData->employee?->category?->title;

        $logs =  AttendanceLog::query()
            ->where(['employee_id' => $salarySlipData->employee_id])
            ->whereMonth('date_time',Carbon::parse($salarySlipData->month))
            ->OrWhere('type','holiday')
            ->get();

        $holidayCount = $logs->where('type','holiday')->count();
        $leaveCount = $logs->where('type','leave')->count();
        $inCount = $logs->where('type','C/In')->count();
        $outCount = $logs->where('type','C/Out')->count();
        $sickLeaveCount = $logs->where('type','sick-leave')->count();
        $paidLeaveCount =$logs->where('type','paid-leave')->count();
        $workFormHome =$logs->where('type','work-form-home')->count();
        $attenadnceCount = max($inCount ,$outCount);

        return Inertia::render('User/SalaraySlip/View',[
            'salarySlipData' => $salarySlipData,
            'holidayCount' => $holidayCount ?? 0 ,
            'leaveCount' => $leaveCount?? 0,
            'inCount' => $inCount ?? 0,
            'outCount' => $outCount ?? 0,
            'sickLeaveCount' => $sickLeaveCount ?? 0,
            'paidLeaveCount' => $paidLeaveCount ?? 0,
            'workFormHome' => $workFormHome ?? 0,
            'attenadnceCount' => $attenadnceCount ?? 0
        ]);

    }
}
