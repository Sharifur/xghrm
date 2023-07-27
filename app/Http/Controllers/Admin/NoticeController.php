<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;

class NoticeController extends Controller
{
    public function index(){
        return Inertia::render("Backend/Notice/Index");
    }
    public function store(){
        //store notice
    }
}
