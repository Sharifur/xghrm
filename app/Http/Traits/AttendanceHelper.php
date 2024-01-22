<?php
namespace App\Http\Traits;

trait AttendanceHelper
{
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
