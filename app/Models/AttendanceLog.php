<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AttendanceLog extends Model
{
    use HasFactory;
    protected $fillable = ['name','date_time','type','employee_id'];

    protected $casts = [
      'employee_id' => 'integer',
    ];

    public function employee(){
        return $this->hasOne(Employee::class,'id','employee_id');
    }
}
