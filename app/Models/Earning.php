<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Earning extends Model
{
    use HasFactory;
    protected $fillable = ['title','from','month','personal_earning','office_earning','statement','en_username','percentage'];

    protected $casts =[
        'month' => 'datetime',
        'percentage' => 'float'
    ];
}
