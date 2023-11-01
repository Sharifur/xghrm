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
        $allSalaries = AdvanceSalary::with('employee')->orderBy('created_at','desc')->paginate(10)->through(function($item){
           $item->monthName = Carbon::parse($item->month)->format('M');
           $item->year = Carbon::parse($item->month)->format('Y');
           $item->created = Carbon::parse($item->created_at)->format('Y M d');
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

    public function edit($id){
        $all_employee = Employee::where('status',1)->get()->map(function ($item){
            return ['label' => $item->name,'value' => $item->id];
        });
        $advance_salary =  AdvanceSalary::with(['employee' => function($q){
            $q->with('category');
        }])->where('id',$id)->first();
      $advance_salaryData = [
        'month' =>  Carbon::parse($advance_salary->month)->format('Y-m-d'),
        'amount' => $advance_salary->amount,
        'employee_id' => $advance_salary->employee_id,
        'name' => $advance_salary?->employee?->name,
        'category' => $advance_salary?->employee?->category?->title
      ];

        return Inertia::render('Backend/AdvanceSalary/Edit',[
            'all_employee' => $all_employee,
            'advance_salary' => $advance_salaryData
        ]);
    }

    public function view($id){
        $advance_salary =  AdvanceSalary::with(['employee' => function($q){
            $q->with('category');
        }])->where('id',$id)->first();

      $advance_salaryData = [
        'month' =>  Carbon::parse($advance_salary->month)->format('Y-m-d'),
        'amount' => $advance_salary->amount,
        'employee_id' => $advance_salary->employee_id,
        'name' => $advance_salary?->employee?->name,
        'category' => $advance_salary?->employee?->category?->title
      ];

        return Inertia::render('Backend/AdvanceSalary/View',[
            'advance_salary' => $advance_salaryData
        ]);
    }

    // /store
    public function store(AdvanceSalaryRequest $request){
        $advanceSalary = AdvanceSalary::where('employee_id',$request->employee_id)->whereDate('created_at','=',Carbon::today())->first();
        if(is_null( $advanceSalary)){
            AdvanceSalary::create([
                'amount' => $request->amount,
                'employee_id' => $request->employee_id,
                'month' => $request->month,
            ]);
        }elseif($advanceSalary->created_at->isSameDay(Carbon::today())){
            $advanceSalary->amount = $request->amount;
            $advanceSalary->month = $request->month;
            $advanceSalary->save();
        }
        return response()->json('ok');
    }
    public function delete(Request $request,$id){
        AdvanceSalary::find($id)->delete();
        return back();
    }


}
