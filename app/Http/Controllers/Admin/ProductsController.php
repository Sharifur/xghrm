<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\ProductRequest;
use App\Models\Employee;
use App\Models\Product;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ProductsController extends Controller
{

    public function index(){
        $all_products = Product::with('dev')->orderBy('id','desc')->paginate(10);

        return Inertia::render('Backend/Products/Product',[
           'products' => $all_products
        ]);
    }
    public function new(){
        $developers = Employee::all()->map(function ($item){
           return  ['label' => $item->name, 'value' => $item->id];
        });
        return Inertia::render('Backend/Products/New',[
            'developers' => $developers
        ]);
    }

    public function store(ProductRequest $request){
        Product::create($request->validated());
        return back();
    }
    public function update(ProductRequest $request){
        Product::find($request->id)->update($request->validated());
        return back();
    }

    public function delete(Request $request){
        Product::find($request->id)->delete();
        return back();
    }
    public function view($id){
        return Inertia::render('Backend/Products/View',[
           'product' => Product::with('dev')->find($id)
        ]);
    }
    public function edit($id){
        $developers = Employee::all()->map(function ($item){
            return  ['label' => $item->name, 'value' => $item->id];
        });
        return Inertia::render('Backend/Products/Edit',[
           'product' => Product::find($id),
            'developers' => $developers
        ]);
    }
}
