<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MediaUpload extends Model
{
    use HasFactory;
    protected $fillable = ['title','path','alt','size','dimensions','extension','type'];
}
