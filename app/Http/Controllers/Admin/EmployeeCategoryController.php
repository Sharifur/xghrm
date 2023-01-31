<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\EmployeeCategoryRequest;
use App\Models\EmployeeCategory;
use Illuminate\Http\Request;
use Inertia\Inertia;

class EmployeeCategoryController extends Controller
{
    public function index(){
        $all_category = EmployeeCategory::orderBy('id','desc')->paginate(10);
        return Inertia::render('Backend/Employees/Category',[
           'categories' => $all_category
        ]);
    }

    public function store(EmployeeCategoryRequest $request){
        EmployeeCategory::create($request->validated());
        return back()->with(['flashMsg' => ['msg' => 'Save Success','type' => 'success']]);
    }
    public function update(EmployeeCategoryRequest $request){
        EmployeeCategory::find($request->id)->update($request->validated());
        return back()->with(['flashMsg' => ['msg' => 'Save Success','type' => 'success']]);
    }
    public function delete(Request $request){
        EmployeeCategory::find($request->id)->delete();
        return back();
    }
}
