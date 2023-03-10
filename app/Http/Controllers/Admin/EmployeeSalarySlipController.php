<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\SalarySlipRequest;
use App\Models\SalarySlip;
use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\Employee;
use Carbon\Carbon;

class EmployeeSalarySlipController extends Controller
{
    public function index(){
        $all_employee = Employee::where('status',1)->get()->map(function ($item){
            return ['label' => $item->name,'value' => $item->id];
        });
        return Inertia::render('Backend/SalaraySlip/Index',[
            'all_employee' => $all_employee
        ]);
    }

    public function store(SalarySlipRequest $request){
        $employee_details = Employee::find($request->employee_id);
        $existingSlip = SalarySlip::where('employee_id',$employee_details->id)->whereMonth('month','=',Carbon::parse($request->month))->first();
        if(is_null( $existingSlip)){
            SalarySlip::create(array_merge([
                'salary' => $employee_details->salary,
                'employee_id' => $employee_details->id,
                'month' => $request->month,
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
}
