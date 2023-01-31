<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Attendance extends Model
{
    use HasFactory;
    protected $fillable = ['name','file_id','end_date','start_date'];

    protected $casts = [
      'start_date' => 'datetime',
      'end_date' => 'datetime',
    ];
}
