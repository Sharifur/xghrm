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
        'frequency',
        'currency',
        'bdt_amount',
        'tooltip',
        'is_active',
        'sort_order',
        'last_paid_date',
        'payment_status',
        'next_due_date',
        'payment_notes'
    ];

    protected $casts = [
        'default_amount' => 'decimal:2',
        'bdt_amount' => 'decimal:2',
        'is_recurring' => 'boolean',
        'is_active' => 'boolean',
        'sort_order' => 'integer',
        'last_paid_date' => 'date',
        'next_due_date' => 'date'
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

    public function recurringPayments()
    {
        return $this->hasMany(RecurringExpensePayment::class);
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

    // Payment Status Calculation Methods
    public function isDueForPayment()
    {
        if (!$this->is_recurring) {
            return false;
        }

        $now = now();
        $lastPaid = $this->last_paid_date;

        if (!$lastPaid) {
            return true; // Never paid before
        }

        switch ($this->frequency) {
            case 'monthly':
                return $lastPaid->format('Y-m') !== $now->format('Y-m');
            case 'yearly':
                return $lastPaid->format('Y') !== $now->format('Y');
            case 'weekly':
                return $lastPaid->diffInWeeks($now) >= 1;
            default:
                return false;
        }
    }

    public function isOverdue()
    {
        if (!$this->is_recurring || !$this->next_due_date) {
            return false;
        }

        return now()->gt($this->next_due_date);
    }

    public function calculateNextDueDate()
    {
        if (!$this->is_recurring) {
            return null;
        }

        $lastPaid = $this->last_paid_date ?: now()->subMonth();

        switch ($this->frequency) {
            case 'monthly':
                return $lastPaid->copy()->addMonth();
            case 'yearly':
                return $lastPaid->copy()->addYear();
            case 'weekly':
                return $lastPaid->copy()->addWeek();
            default:
                return null;
        }
    }

    public function getCurrentPaymentStatus()
    {
        if (!$this->is_recurring) {
            return 'not_recurring';
        }

        if ($this->isOverdue()) {
            return 'overdue';
        }

        if ($this->isDueForPayment()) {
            return 'due';
        }

        return 'paid';
    }

    public function markAsPaid($paidDate = null, $notes = null)
    {
        $paidDate = $paidDate ?: now();
        
        $this->update([
            'last_paid_date' => $paidDate,
            'payment_status' => 'paid',
            'next_due_date' => $this->calculateNextDueDate(),
            'payment_notes' => $notes
        ]);

        return $this;
    }

    public function getPendingAmount()
    {
        if (!$this->is_recurring) {
            return 0;
        }

        $status = $this->getCurrentPaymentStatus();
        
        if (in_array($status, ['due', 'overdue'])) {
            return $this->bdt_amount;
        }

        return 0;
    }

    // Scopes for payment status
    public function scopeDueForPayment($query)
    {
        return $query->where('is_recurring', true)
                    ->where(function($q) {
                        $q->whereNull('last_paid_date')
                          ->orWhere(function($q2) {
                              $q2->where('frequency', 'monthly')
                                 ->whereRaw('DATE_FORMAT(last_paid_date, "%Y-%m") != ?', [now()->format('Y-m')]);
                          })
                          ->orWhere(function($q2) {
                              $q2->where('frequency', 'yearly')
                                 ->whereRaw('YEAR(last_paid_date) != ?', [now()->year]);
                          })
                          ->orWhere(function($q2) {
                              $q2->where('frequency', 'weekly')
                                 ->where('last_paid_date', '<', now()->subWeek());
                          });
                    });
    }

    public function scopeOverdue($query)
    {
        return $query->where('is_recurring', true)
                    ->where('next_due_date', '<', now());
    }
}
