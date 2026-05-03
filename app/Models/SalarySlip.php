<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SalarySlip extends Model
{
    use HasFactory;
    protected $fillable = ['employee_id','month','extraEarningFields','extraDeductionFields','salary','paid_at'];

    protected $casts = [
      'month' => 'datetime',
      'paid_at' => 'datetime',
      'employee_id' => 'integer',
    ];

    public function employee(){
        return $this->belongsTo(Employee::class,"employee_id");
    }
}
