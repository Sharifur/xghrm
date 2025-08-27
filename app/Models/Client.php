<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Client extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'email',
        'phone',
        'payment_terms',
        'notes',
        'is_active',
        'created_by',
        'updated_by'
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];

    // Relationships
    public function creator()
    {
        return $this->belongsTo(\App\Models\Admin::class, 'created_by');
    }

    public function updater()
    {
        return $this->belongsTo(\App\Models\Admin::class, 'updated_by');
    }

    public function revenues()
    {
        return $this->hasMany(Revenue::class);
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    // Accessors
    public function getFormattedPaymentTermsAttribute()
    {
        $terms = [
            'net_15' => 'Net 15 days',
            'net_30' => 'Net 30 days',
            'net_60' => 'Net 60 days',
            'immediate' => 'Immediate'
        ];

        return $terms[$this->payment_terms] ?? $this->payment_terms;
    }

    // Methods
    public function getTotalRevenueAttribute()
    {
        return $this->revenues()->where('status', 'paid')->sum('amount');
    }

    public function getPendingRevenueAttribute()
    {
        return $this->revenues()->whereIn('status', ['pending', 'overdue'])->sum('amount');
    }
}