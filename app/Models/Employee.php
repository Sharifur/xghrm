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
    ];

    public function category(){
        return $this->belongsTo(EmployeeCategory::class,'catId');
    }
    public function user(){
        return $this->belongsTo(User::class,'user_id','id');
    }

    protected $casts = [
        'user_id' => 'integer',
        'catId' => 'integer',
        'status' => 'integer',
        'imageId' => 'integer',
        'joinDate' => 'datetime',
        'dateOfBirth' => 'datetime'
    ];
}
