<?php
namespace App\Http\Traits;

use Carbon\Carbon;

trait AttendanceHelper
{
    public function logAsArray(\Illuminate\Database\Eloquent\Collection|array|\LaravelIdea\Helper\App\Models\_IH_AttendanceLog_C $logs,$dateTime=false)
    {
        $logsInfo = [];
        foreach($logs as $log){
            $parsed_date = Carbon::parse($log->date_time)->format("d-m-Y");
            if (isset($logsInfo[$parsed_date])){
                //alreayd have this day index
                if ($log->type == "C/Out"){
                    //added Out_time
                    $logsInfo[$parsed_date][str_replace("c/","",strtolower($log->type))."_time"] =
                        $log->type === "holiday" ? " ": $log->date_time->format('g:i A');

                    //todo if in time available then calculate total office hour
                    if (isset($logsInfo[$parsed_date]["in_time"])){
                        $dt_str = $parsed_date." ".$logsInfo[$parsed_date]["in_time"];
                        $check_intime = Carbon::parse($dt_str);
                        $logsInfo[$parsed_date]["working_hour"] = $check_intime->diff($log->date_time)->format("%H:%I:%S");
                    }
                }
                $logsInfo[$parsed_date]["working_nature"] = $this->workNature($log->type);
            }else{
                //added in_time
                $logsInfo[$parsed_date] = [
                    str_replace("c/","",strtolower($log->type))."_time" =>
                        $log->type === "holiday" ? " ": $log->date_time->format('g:i A'),
                    "working_nature" => $this->workNature($log->type),
                    "dateTime" => $dateTime ? $log->date_time : $parsed_date

                ];
            }
            //if found cout/cin then show total office hour
        }

        return $logsInfo;
    }

    private function workNature($type){
        return match ($type){
            "holiday" => "Holiday",
            "work-from-home" => "Remote",
            "C/In", "C/Out" => "Office",
            "leave",
            "sick-leave","paid-leave" => "Leave",
            default => " "
        };
    }
}
