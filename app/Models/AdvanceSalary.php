<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AdvanceSalary extends Model
{
    use HasFactory;
    protected $fillable = [  'employee_id','month' ,'amount'];
    protected $casts = [
        'month' => 'datetime',
        'amount' => 'double',
        'employee_id' => 'integer',
    ];

    public function employee(){
        return $this->belongsTo(Employee::class,'employee_id');
    }
}
