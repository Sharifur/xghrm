<?php

namespace App\Http\Controllers\Admin;

use App\Helpers\ZktecoHelper;
use App\Http\Controllers\Controller;
use App\Mail\BasicMail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Mail;
use Inertia\Inertia;

class GeneralSettingsController extends Controller
{
    public function settings(){
        return Inertia::render("Backend/GeneralSettings/Settings");
    }
    public function databaseUpdate(){
        setEnvValue(['APP_ENV' => 'local']);
        Artisan::call('migrate', ['--force' => true ]);
        Artisan::call('db:seed', ['--force' => true ]);
        Artisan::call('cache:clear');
        setEnvValue(['APP_ENV' => 'production']);
        return back();
    }
    public function smtpTest(){
        Mail::to('dvrobin4@gmail.com')->send(new BasicMail(['subject' => 'Testing Email Features','message' => __('this is a simple test message')] ));
        return back();
    }

    public function syncData(){
        $zkHelper = ZktecoHelper::init();
        $getData = $zkHelper->getData();
        $getUsers= $zkHelper->users();
        dd($getUsers,$getUsers->where("name","Siam")->first(),$getData);
        dd($getData,$getUsers);
//        ->getAttendance();
//        dd($data);
    }
}
