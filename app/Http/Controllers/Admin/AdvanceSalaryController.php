<?php

namespace App\Http\Controllers\Admin;

use App\Helpers\CsvReader;
use App\Http\Controllers\Controller;
use App\Http\Requests\AdvanceSalaryRequest;
use App\Models\AdvanceSalary;
use App\Models\Attendance;
use App\Models\AttendanceLog;
use App\Models\Employee;
use App\Models\MediaUpload;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Inertia\Inertia;

class AdvanceSalaryController extends Controller
{
    public function index(){
        $allSalaries = AdvanceSalary::with('employee')->orderBy('id','desc')->paginate(10)->through(function($item){
           $item->monthName = Carbon::parse()->format('M'); 
           $item->year = Carbon::parse()->format('Y'); 
           return $item;
        });

        return Inertia::render('Backend/AdvanceSalary/Index',[
            'allSalaries' => $allSalaries,
        ]);
    }
    public function create(){
        $all_employee = Employee::where('status',1)->get()->map(function ($item){
            return ['label' => $item->name,'value' => $item->id];
        });

        return Inertia::render('Backend/AdvanceSalary/Create',[
            'all_employee' => $all_employee
        ]);
    }
    // /store
    public function store(AdvanceSalaryRequest $request){

        $advanceSalary = AdvanceSalary::where('employee_id',$request->employee_id)->whereMonth('month','=',Carbon::parse($request->month))->first();
        if(is_null( $advanceSalary)){
            AdvanceSalary::create([
                'amount' => $request->amount,
                'employee_id' => $request->employee_id,
                'month' => $request->month,
            ]);
        }else{
            $advanceSalary->amount = $request->amount;
            $advanceSalary->month = $request->month;
            $advanceSalary->save();
        }
        return response()->json('ok');
    }
    public function delete(Request $request,$id){
        dd('dd');
        AdvanceSalary::find($id)->delete();
        return response()->json('ok');
    }
    

}
