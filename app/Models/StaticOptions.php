<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StaticOptions extends Model
{
    use HasFactory;
    protected $fillable = ['option_value','option_name'];
}
