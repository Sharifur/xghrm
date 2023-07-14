<?php

namespace App\Http\Controllers\Admin;

use App\Helpers\CsvReader;
use App\Http\Controllers\Controller;
use App\Http\Requests\AttendanceLogInsertRequest;
use App\Http\Requests\AttendanceRequest;
use App\Models\Attendance;
use App\Models\AttendanceLog;
use App\Models\Employee;
use App\Models\MediaUpload;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Inertia\Inertia;

class AttendanceController extends Controller
{
    public function index(Request $request){

        $atteandancesQuery = Attendance::query();

        if (!empty($request->filter)){
            $atteandancesQuery->where("status",$request->filter);
        }

        $atteandances = $atteandancesQuery->orderBy('id','desc')->paginate(10)->withQueryString();

        return Inertia::render('Backend/Attendance/Index',[
            'attendances' => $atteandances,

        ]);
    }
    public function create(){
        return Inertia::render('Backend/Attendance/Create');
    }
    public function edit($id){
       $attendance =  Attendance::find($id);
        return Inertia::render('Backend/Attendance/Edit',[
            'attendance' => $attendance
        ]);
    }
    public function extract($id){
        $attendance =  Attendance::find($id);
        $employees = Employee::where('status',1)->get()->map(function ($item){
            return ['label' => $item->name,'value' => $item->id];
        });
        $media_details = MediaUpload::find($attendance->file_id);
        $file_path = '';
        if (!is_null($media_details)){
           $file_path = $this->getCsvFilePath($media_details);
        }

        $csv_headers = [];
        if (!empty($file_path)){
            $csv_reader = new CsvReader($file_path);
            $csv_header = $csv_reader->head();
            if (!empty($csv_header)){
                foreach(current($csv_header) as $head){
                    $csv_headers[] = [
                        'label' => $head,
                        'value' => $head
                    ];
                }
            }
        }

        return Inertia::render('Backend/Attendance/ExtractByPerson',[
            'attendance' => $attendance,
            'employees' => $employees,
            'csv_headers' => $csv_headers
        ]);
    }

    public function store(AttendanceRequest $request){
        Attendance::create($request->validated());
        return back();
    }
    public function update(AttendanceRequest $request){
        Attendance::find($request->id)->update($request->validated());
        return back();
    }

    public function delete(Request $request){
        $this->validate($request,[
            'id' => 'required|integer'
        ]);
        Attendance::find($request->id)->delete();
        return back();
    }

    function get_csv_column_values(Request $request){
        $attendance =  Attendance::find($request->attendance_report_id);
        $media_details = MediaUpload::find($attendance->file_id);
        $file_path = '';
        if (!is_null($media_details)){
            $file_path = $this->getCsvFilePath($media_details);
        }
        $csv_headers = [];

        if (!empty($file_path)){
            $csv_reader = new CsvReader($file_path);
            $csv_header = $csv_reader->head();
            $csv_rows = $csv_reader->rows();
            $selected_column_index = array_search($request->csv_column, current($csv_header));
            foreach ($csv_rows as $key => $row){
                if ($key !== 0 && !empty($row)){
                    $column_value = $row[$selected_column_index];
                    $csv_headers[$column_value] = ['label' => $column_value,'value' => $column_value];
                }
            }
        }
        return response()->json($csv_headers);
    }

    public function insert_attendance_log_from_csv_column(AttendanceLogInsertRequest $request){

        $attendance =  Attendance::find($request->attendance_report_id);
        $media_details = MediaUpload::find($attendance->file_id);

        $file_path = '';
        if (!is_null($media_details)){
            $file_path = $this->getCsvFilePath($media_details);
        }
        if ($request->importType === 'individual'){
            $this->individual_import($file_path,$request);
        return back();
        }
        $this->bulk_attendance_import($file_path,$request);

        return back();

    }

    private function getCsvFilePath($media_details)
    {
        $path = public_path('uploads/media-upload/'.$media_details->path);
        if(  file_exists($path)&& !is_dir($path)){
            return  $path;
        }
        return '';
    }

    private function setDataForDB($current_att_type, $current_att_type1,$em=null)
    {
        return [
                'name' => empty($em) ? request()->column_value : $em->att_id,
                'employee_id' => empty($em) ? request()->employee_id : $em->id,
                'type' => $current_att_type,
                'date_time' =>Carbon::parse($current_att_type1)->toDateTimeString() ,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ];
    }

    public function new_attendance_log(Request $request){
        $this->validate($request,[
            'employee_id'=> 'nullable|numeric',
            'type'=> 'required|string',
            'date_time'=> 'required|string'
        ]);
        $employee_details = Employee::find($request->employee_id);
        AttendanceLog::create([
            'employee_id'=> $request->employee_id,
            'type'=> $request->type,
            'date_time'=> Carbon::parse($request->date_time)->addDay(1),
            'name'=> $employee_details->att_id ?? ''
        ]);

        return back();
    }
    private function individual_import($file_path,$request){

        $attendance_logs = [];

        if (!empty($file_path)){
            $csv_reader = new CsvReader($file_path);
            $csv_header = $csv_reader->head();
            $csv_rows = $csv_reader->rows();
            $selected_column_index = array_search($request->csv_column, current($csv_header));
            $selected_attendance_column_index = array_search($request->attendance_column_value, current($csv_header));
            $selected_attendance_type_column_value = array_search($request->attendance_type_column_value, current($csv_header));
            foreach ($csv_rows as $row){
                if ( !empty($row)){
                    $column_value = $row[$selected_column_index];
                    if ($column_value == $request->column_value){
                        $attendance_logs[] = [
                            $row[$selected_attendance_type_column_value] => $row[$selected_attendance_column_index]
                        ];
                    }
                }
            }
        }

        $attendance_arr = [];
        //run loop
        $last_att_type = '';
        $last_att_date = '';
        $last_inserted_id = '';
        foreach($attendance_logs as $attend){
            $current_att_type = array_key_first($attend);
            preg_match('/C\/Out|C\/In/',$current_att_type,$matches);
            $current_att_type = current($matches);
            if(!isset($attend[$current_att_type])){
                    continue;
            }
            $current_att_date = Carbon::parse($attend[$current_att_type]);
            // have to filter for // C/In // C/Out
            if ( $current_att_date->isSameDay($last_att_date)){
                if($last_att_type !== $current_att_type){
                    $attendance_arr[] = $this->setDataForDB($current_att_type,$attend[$current_att_type]);
                }elseif($last_att_type === 'C/Out') {
                    $last = array_key_last($attendance_arr);
                    $attendance_arr[$last] = $this->setDataForDB($current_att_type,$attend[$current_att_type]);
                }

            }else{
                $attendance_arr[] =$this->setDataForDB($current_att_type,$attend[$current_att_type]);
            }
            $last_att_type = $current_att_type;
            $last_att_date = Carbon::parse($attend[$current_att_type]);
        }

        foreach ($attendance_arr as $att){
            AttendanceLog::updateOrCreate( [
                'date_time' => Carbon::parse($att['date_time'])->toDateTimeString(),
                'type' => $att['type'],
                'employee_id' => $att['employee_id'],
            ],$att);
        }

    }
    private function bulk_attendance_import($file_path,$request){

        $all_employee = Employee::where('status',1)->get();
        foreach ($all_employee as $em){

            $attendance_logs = [];
            if (!empty($file_path)){
                $csv_reader = new CsvReader($file_path);
                $csv_header = $csv_reader->head();
                $csv_rows = $csv_reader->rows();
                // $selected_column_index = array_search($em->att_id, current($csv_header));
                $selected_column_index = array_search("Name", current($csv_header));
                $selected_attendance_column_index = array_search($request->attendance_column_value, current($csv_header));
                $selected_attendance_type_column_value = array_search($request->attendance_type_column_value, current($csv_header));
                foreach ($csv_rows as $row){
                    if ( !empty($row)){

                        $column_value = $row[$selected_column_index];
                        // dd($column_value,
                        // strtolower($em->att_id),
                        // $selected_column_index
                        // ,$em->att_id);
                        if (strtolower($column_value) == strtolower($em->att_id)){
                            $attendance_logs[] = [
                                $row[$selected_attendance_type_column_value] => $row[$selected_attendance_column_index]
                            ];
                        }
                    }
                }
            }
// dd($attendance_logs);
            if (empty($attendance_logs)){
                continue;
            }

            $attendance_arr = [];
            //run loop
            $last_att_type = '';
            $last_att_date = '';
            $last_inserted_id = '';
            foreach($attendance_logs as $attend){
                $current_att_type = array_key_first($attend);
                preg_match('/C\/Out|C\/In/',$current_att_type,$matches);
                $current_att_type = current($matches);
                //dd($attend[$current_att_type],$current_att_type,Carbon::parse($attend[$current_att_type]));
                if(!isset($attend[$current_att_type])){
                    continue;
                }
                $current_att_date = Carbon::parse($attend[$current_att_type]);
                // have to filter for // C/In // C/Out
                if ( $current_att_date->isSameDay($last_att_date)){
                    if($last_att_type !== $current_att_type){
                        $attendance_arr[] = $this->setDataForDB($current_att_type,$attend[$current_att_type],$em);
                    }elseif($last_att_type === 'C/Out') {
                        $last = array_key_last($attendance_arr);
                        $attendance_arr[$last] = $this->setDataForDB($current_att_type,$attend[$current_att_type],$em);
                    }

                }else{
                    $attendance_arr[] =$this->setDataForDB($current_att_type,$attend[$current_att_type],$em);
                }
                $last_att_type = $current_att_type;
                $last_att_date = Carbon::parse($attend[$current_att_type]);
            }

            foreach ($attendance_arr as $att){
                AttendanceLog::updateOrCreate( [
                    'date_time' => Carbon::parse($att['date_time'])->toDateTimeString(),
                    'type' => $att['type'],
                    'employee_id' => $em->id,
                ],$att);
            }
        }
    }

}
