<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ApiApplication extends Model
{
    use HasFactory;

    protected $table = 'api_applications';

    protected $fillable = ['name', 'description', 'secret_key', 'active', 'last_used_at'];

    protected $casts = [
        'active' => 'boolean',
        'last_used_at' => 'datetime',
    ];

    protected $hidden = ['secret_key'];

    public function getKeyPrefixAttribute(): string
    {
        return substr($this->secret_key, 0, 8) . '...';
    }
}
