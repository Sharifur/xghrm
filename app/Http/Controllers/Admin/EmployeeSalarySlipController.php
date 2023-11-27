<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\SalarySlipRequest;
use App\Models\SalarySlip;
use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\Employee;
use Carbon\Carbon;
use App\Models\AttendanceLog;

class EmployeeSalarySlipController extends Controller
{
    public function index(){

        $allSalaries = SalarySlip::with('employee')->orderBy('created_at','desc')->paginate(10)->through(function($item){
            $item->monthName = Carbon::parse($item->month)->format('M');
            $item->year = Carbon::parse($item->month)->format('Y');
            $item->created = Carbon::parse($item->created_at)->format('Y M d');
            return $item;
         });

         return Inertia::render('Backend/SalaraySlip/Index',[
             'allSalaries' => $allSalaries,
         ]);
    }

    public function create(){
        $all_employee = Employee::where('status',1)->get()->map(function ($item){
            return ['label' => $item->name,'value' => $item->id];
        });
        return Inertia::render('Backend/SalaraySlip/Create',[
            'all_employee' => $all_employee
        ]);
    }

    public function store(SalarySlipRequest $request){
        $employee_details = Employee::find($request->employee_id);
        $existingSlip = SalarySlip::where('employee_id',$employee_details->id)
            ->whereYear('month', '=', Carbon::parse($request->month)->year)
            ->whereMonth('month','=',Carbon::parse($request->month))->first();
        if(is_null( $existingSlip)){
            SalarySlip::create(array_merge([
                'salary' => $employee_details->salary,
                'employee_id' => $employee_details->id,
                'month' => Carbon::parse($request->month),
                'extraEarningFields' => json_encode($request->extraEarningFields),
                'extraDeductionFields' => json_encode($request->extraDeductionFields),
            ]));
        }else{
            $existingSlip->extraEarningFields = json_encode($request->extraEarningFields);
            $existingSlip->extraDeductionFields = json_encode($request->extraDeductionFields);
            $existingSlip->month = $request->month;
            $existingSlip->save();
        }
    }
    public function update(SalarySlipRequest $request){
        $employee_details = Employee::find($request->employee_id);
        SalarySlip::where('id',$request->salarySlipID)->update([
            'salary' => $employee_details->salary,
            'employee_id' => $employee_details->id,
            'month' => Carbon::parse($request->month),//->format(),
            'extraEarningFields' => json_encode($request->extraEarningFields),
            'extraDeductionFields' => json_encode($request->extraDeductionFields),
        ]);
    }

    public function edit($id){
        $all_employee = Employee::where('status',1)->get()->map(function ($item){
            return ['label' => $item->name,'value' => $item->id];
        });
        $salarySlipData = SalarySlip::with(["employee" => function($q){
             $q->with("category")->get();
        }])->find($id);
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

        return Inertia::render('Backend/SalaraySlip/Edit',[
            'all_employee' => $all_employee,
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

    public function view($id){

        $salarySlipData = SalarySlip::with(["employee" => function($q){
             $q->with("category")->get();
        }])->find($id);
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

        return Inertia::render('Backend/SalaraySlip/View',[
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

    public function delete($id){
        SalarySlip::find($id)->delete();
        return back();
    }
}
