<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ExpenseCategory extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'type',
        'icon',
        'color',
        'is_recurring',
        'default_amount',
        'currency',
        'bdt_amount',
        'tooltip',
        'is_active',
        'sort_order'
    ];

    protected $casts = [
        'default_amount' => 'decimal:2',
        'bdt_amount' => 'decimal:2',
        'is_recurring' => 'boolean',
        'is_active' => 'boolean',
        'sort_order' => 'integer'
    ];

    const USD_TO_BDT_RATE = 120;

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeRecurring($query)
    {
        return $query->where('is_recurring', true);
    }

    public function scopeByType($query, $type)
    {
        return $query->where('type', $type);
    }

    public function expenses()
    {
        return $this->hasMany(Expense::class, 'expense_type', 'name');
    }

    protected static function boot()
    {
        parent::boot();

        static::saving(function ($model) {
            // Auto-calculate BDT amount when saving
            if ($model->currency === 'USD') {
                $model->bdt_amount = $model->default_amount * self::USD_TO_BDT_RATE;
            } else {
                $model->bdt_amount = $model->default_amount;
            }
        });
    }

    public function getBdtAmountAttribute($value)
    {
        if ($this->currency === 'USD') {
            return $this->default_amount * self::USD_TO_BDT_RATE;
        }
        return $this->default_amount;
    }

    public function getFormattedAmountAttribute()
    {
        $symbol = $this->currency === 'USD' ? '$' : '৳';
        return $symbol . number_format($this->default_amount, 2);
    }

    public function getConvertedAmountAttribute()
    {
        if ($this->currency === 'USD') {
            return '৳' . number_format($this->bdt_amount, 2) . ' BDT';
        }
        return null;
    }
}
