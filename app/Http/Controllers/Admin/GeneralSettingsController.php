<?php

namespace App\Http\Controllers\Admin;

use App\Helpers\ZktecoHelper;
use App\Http\Controllers\Controller;
use App\Mail\BasicMail;
use App\Models\AttendanceLog;
use App\Models\Employee;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
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
        $getUsers =  $zkHelper->users();
        $allEmployee = Employee::where('status',1)->get();
        $buildDataForAttendanceLog = [];
        $filename = 'attendance-file/last-30days-attendance'.Str::uuid()->toString().'.csv';
        file_put_contents($filename,'');
        $fl =  fopen(public_path($filename),'w');
        $header_added = false;

        foreach ($getData as $data){

            //build attendance log array for saving in database
            $emp = $allEmployee->where('zktecho_user_id',$data['id'])->first();
            if (is_null($emp)){
                continue;
            }
            if (!$header_added){
                fputcsv($fl,["name",'employee_id' ,'type','date_time','status','uuid']);
            }
            fputcsv($fl,[
                'name' => $emp->att_id,
                'employee_id' => $emp->id,
                'type' => $zkHelper->getType($data['type']),
                'date_time' => $data['timestamp'] ,
                'status' => 1,
                'uuid' => Str::uuid()
            ]);
            $header_added = true;

//            $buildDataForAttendanceLog[] = [
//                'name' => $emp->att_id,
//                'employee_id' => $emp->id,
//                'type' => $zkHelper->getType($data['type']),
//                'date_time' => $data['timestamp'] ,
//                'status' => 1,
//                'uuid' => Str::uuid()
//            ];

        }

        fclose($fl);


        return response()->download($filename);
//        Storage::put('last-30days-attendance'.Str::uuid()->toString().'.csv',)


//        foreach ($buildDataForAttendanceLog as $att){
//            AttendanceLog::updateOrCreate( [
//                'date_time' => Carbon::parse($att['date_time'])->toDateTimeString(),
//                'type' => $att['type'],
//                'employee_id' => $att['employee_id'],
//            ],$att);
//        }
        return back()->with(['msg' => __('Data import success'),'type' => "success"]);
    }
}
