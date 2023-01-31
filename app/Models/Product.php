<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = ['title','author','enItemId','category','releaseDate','thumbnail','developer'];

    public function dev(){
        return $this->belongsTo(Employee::class,'developer');
    }
    protected $casts = [
        'category' => 'integer',
        'enItemId' => 'integer',
        'author' => 'integer',
        'releaseDate' => 'datetime'
    ];
}
