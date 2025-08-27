<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Revenue extends Model
{
    use HasFactory;

    protected $fillable = [
        'client_id',
        'service_type',
        'amount',
        'currency',
        'bdt_amount',
        'status',
        'expected_date',
        'invoice_date',
        'paid_date',
        'description',
        'notes',
        'created_by',
        'updated_by'
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'bdt_amount' => 'decimal:2',
        'expected_date' => 'date',
        'invoice_date' => 'date',
        'paid_date' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];

    // Exchange rate constant (could be moved to config)
    const USD_TO_BDT_RATE = 120;

    // Boot method to auto-calculate BDT amount
    protected static function boot()
    {
        parent::boot();

        static::saving(function ($revenue) {
            if ($revenue->currency === 'USD') {
                $revenue->bdt_amount = $revenue->amount * self::USD_TO_BDT_RATE;
            } else {
                $revenue->bdt_amount = $revenue->amount;
            }
        });
    }

    // Relationships
    public function client()
    {
        return $this->belongsTo(Client::class);
    }

    public function creator()
    {
        return $this->belongsTo(\App\Models\Admin::class, 'created_by');
    }

    public function updater()
    {
        return $this->belongsTo(\App\Models\Admin::class, 'updated_by');
    }

    // Scopes
    public function scopePaid($query)
    {
        return $query->where('status', 'paid');
    }

    public function scopePending($query)
    {
        return $query->whereIn('status', ['pending', 'overdue']);
    }

    public function scopeCurrentMonth($query)
    {
        return $query->whereMonth('created_at', now()->month)
                    ->whereYear('created_at', now()->year);
    }

    // Accessors
    public function getFormattedServiceTypeAttribute()
    {
        $types = [
            'webflow_template' => 'Webflow Template',
            'shopify_app' => 'Shopify App',
            'web_development' => 'Web Development',
            'consulting' => 'Consulting',
            'maintenance' => 'Maintenance Contract',
            'other' => 'Other Service'
        ];

        return $types[$this->service_type] ?? $this->service_type;
    }

    public function getServiceIconAttribute()
    {
        $icons = [
            'webflow_template' => 'fas fa-globe',
            'shopify_app' => 'fas fa-shopping-cart',
            'web_development' => 'fas fa-code',
            'consulting' => 'fas fa-handshake',
            'maintenance' => 'fas fa-cogs',
            'other' => 'fas fa-star'
        ];

        return $icons[$this->service_type] ?? 'fas fa-star';
    }
}