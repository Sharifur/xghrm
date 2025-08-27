<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RecurringExpensePayment extends Model
{
    use HasFactory;

    protected $fillable = [
        'expense_category_id',
        'amount',
        'currency',
        'bdt_amount',
        'due_date',
        'period_start',
        'period_end',
        'payment_status',
        'paid_date',
        'payment_notes',
        'payment_method',
        'created_by',
        'updated_by'
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'bdt_amount' => 'decimal:2',
        'due_date' => 'date',
        'period_start' => 'date',
        'period_end' => 'date',
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

            // Auto-set overdue status for unpaid bills past due date
            if ($model->payment_status === 'unpaid' && $model->due_date < now()) {
                $model->payment_status = 'overdue';
            }
        });
    }

    public function expenseCategory()
    {
        return $this->belongsTo(ExpenseCategory::class);
    }

    public function creator()
    {
        return $this->belongsTo(Admin::class, 'created_by');
    }

    public function updater()
    {
        return $this->belongsTo(Admin::class, 'updated_by');
    }

    // Scopes
    public function scopePaid($query)
    {
        return $query->where('payment_status', 'paid');
    }

    public function scopeUnpaid($query)
    {
        return $query->whereIn('payment_status', ['unpaid', 'pending']);
    }

    public function scopeOverdue($query)
    {
        return $query->where('payment_status', 'overdue');
    }

    public function scopeDueThisMonth($query)
    {
        return $query->whereMonth('due_date', now()->month)
                    ->whereYear('due_date', now()->year);
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
}
