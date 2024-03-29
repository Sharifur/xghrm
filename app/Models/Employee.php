<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{
    use HasFactory;

    protected $fillable = [
        'paymentInfo',
        'emergencyNumber' ,
        'address',
        'mobile' ,
        'salary',
        'name' ,
        'email',
        'catId',
        'imageId',
        'joinDate' ,
        'personalInfo',
        'dateOfBirth',
        'status',
        'att_id',
        'user_id',
        'zktecho_user_id',
        'incrementMonth',
    ];

    public function category(){
        return $this->belongsTo(EmployeeCategory::class,'catId');
    }
    public function user(){
        return $this->belongsTo(User::class,'user_id','id');
    }

    public function attendanceLog(){
        return $this->hasMany(AttendanceLog::class,'employee_id');
    }

    protected $casts = [
        'user_id' => 'integer',
        'catId' => 'integer',
        'status' => 'integer',
        'imageId' => 'integer',
        'joinDate' => 'datetime',
        'incrementMonth' => 'datetime',
        'dateOfBirth' => 'datetime'
    ];
}
