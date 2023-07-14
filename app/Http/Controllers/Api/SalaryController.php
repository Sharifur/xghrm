<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Services\UserServices;
use App\Models\AdvanceSalary;
use App\Models\AttendanceLog;
use App\Models\Employee;
use App\Models\SalarySlip;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class SalaryController extends Controller
{

    public function salariesList(){
        $userInfo = User::find(\auth("sanctum")->id());

        if (is_null($userInfo)){
            return response()->json([
                "type" => "danger",
                "msg" => __("user not found")
            ],422);
        }

        $allSalaries = SalarySlip::with(["employee" => function ($query){
                $query->select("id","name");
            }])
            ->select(["id","employee_id","salary","month","extraEarningFields","extraDeductionFields"])
            ->where("employee_id",$userInfo?->employee?->id)
            ->orderBy('id','desc')->paginate(10)->through(function($item){
            $item->monthName = Carbon::parse($item->month)->format('M');
            $item->year = Carbon::parse($item->month)->format('Y');
            $item->created = Carbon::parse($item->created_at)->format('Y M d');
            return $item;
        });
        return response()->json([
            "type" => "success",
            "salaries" => $allSalaries
        ]);
    }

    public function advanceSalariesList(){
        $userInfo = User::find(\auth("sanctum")->id());

        if (is_null($userInfo)){
            return response()->json([
                "type" => "danger",
                "msg" => __("user not found")
            ],422);
        }

        $allSalaries = AdvanceSalary::with(["employee" => function ($query){
                $query->select("id","name");
            }])
            ->select(["id",'employee_id','month' ,'amount'])
            ->where("employee_id",$userInfo?->employee?->id)
            ->orderBy('id','desc')->paginate(10)->through(function($item){
            $item->monthName = Carbon::parse($item->month)->format('M');
            $item->year = Carbon::parse($item->month)->format('Y');
            $item->created = Carbon::parse($item->created_at)->format('Y-M-d');
            return $item;
        });
        return response()->json([
            "type" => "success",
            "salaries" => $allSalaries
        ]);
    }


}
