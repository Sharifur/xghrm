<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OneTimeExpense extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description', 
        'amount',
        'currency',
        'bdt_amount',
        'expense_date',
        'category',
        'icon',
        'receipt_path',
        'notes',
        'payment_status',
        'paid_date',
        'payment_notes',
        'created_by',
        'updated_by'
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'bdt_amount' => 'decimal:2',
        'expense_date' => 'date',
        'paid_date' => 'date'
    ];

    const USD_TO_BDT_RATE = 120;

    protected static function boot()
    {
        parent::boot();

        static::saving(function ($model) {
            // Auto-calculate BDT amount when saving
            if ($model->currency === 'USD') {
                $model->bdt_amount = $model->amount * self::USD_TO_BDT_RATE;
            } else {
                $model->bdt_amount = $model->amount;
            }
        });
    }

    public function creator()
    {
        return $this->belongsTo(Admin::class, 'created_by');
    }

    public function updater()
    {
        return $this->belongsTo(Admin::class, 'updated_by');
    }

    public function getBdtAmountAttribute($value)
    {
        if ($this->currency === 'USD') {
            return $this->amount * self::USD_TO_BDT_RATE;
        }
        return $this->amount;
    }

    public function getFormattedAmountAttribute()
    {
        $symbol = $this->currency === 'USD' ? '$' : '৳';
        return $symbol . number_format($this->amount, 2);
    }

    public function getConvertedAmountAttribute()
    {
        if ($this->currency === 'USD') {
            return '৳' . number_format($this->bdt_amount, 2) . ' BDT';
        }
        return null;
    }

    public function scopeByDate($query, $date)
    {
        return $query->whereDate('expense_date', $date);
    }

    public function scopeByMonth($query, $month)
    {
        return $query->whereMonth('expense_date', $month);
    }

    public function scopeByYear($query, $year)
    {
        return $query->whereYear('expense_date', $year);
    }

    public function scopeByCategory($query, $category)
    {
        return $query->where('category', $category);
    }

    public function scopePaid($query)
    {
        return $query->where('payment_status', 'paid');
    }

    public function scopeUnpaid($query)
    {
        return $query->whereIn('payment_status', ['unpaid', 'pending']);
    }

    public function scopePending($query)
    {
        return $query->where('payment_status', 'pending');
    }
}