<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BalanceSheetItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'balance_sheet_id',
        'type',
        'name',
        'amount',
        'currency',
        'bdt_amount',
        'tooltip',
        'is_custom',
        'is_recurring',
        'frequency',
        'original_amount',
        'average_amount',
        'sort_order'
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'bdt_amount' => 'decimal:2',
        'original_amount' => 'decimal:2',
        'average_amount' => 'decimal:2',
        'is_custom' => 'boolean',
        'is_recurring' => 'boolean',
        'sort_order' => 'integer'
    ];

    const USD_TO_BDT_RATE = 120;

    public function balanceSheet()
    {
        return $this->belongsTo(BalanceSheet::class);
    }

    public function updateAverageAmount()
    {
        if ($this->is_recurring) {
            $previousItems = static::where('name', $this->name)
                ->where('type', $this->type)
                ->where('id', '!=', $this->id)
                ->orderBy('created_at', 'desc')
                ->limit(6)
                ->get();

            if ($previousItems->count() > 0) {
                $totalAmount = $previousItems->sum('amount') + $this->amount;
                $count = $previousItems->count() + 1;
                $this->average_amount = round($totalAmount / $count, 2);
            } else {
                $this->average_amount = $this->amount;
            }
        }

        return $this;
    }

    public static function createFromCategory(ExpenseCategory $category, BalanceSheet $balanceSheet)
    {
        // Calculate monthly amount based on frequency
        $originalAmount = $category->default_amount ?? 0;
        $frequency = $category->frequency ?? 'monthly';
        $monthlyAmount = static::calculateMonthlyAmount($originalAmount, $frequency);
        $monthlyBdtAmount = static::calculateMonthlyAmount($category->bdt_amount ?? $originalAmount, $frequency);
        
        return static::create([
            'balance_sheet_id' => $balanceSheet->id,
            'type' => $category->type,
            'name' => $category->name,
            'amount' => $monthlyAmount,
            'currency' => $category->currency ?? 'BDT',
            'bdt_amount' => $monthlyBdtAmount,
            'original_amount' => $originalAmount,
            'frequency' => $frequency,
            'tooltip' => $category->tooltip . ($frequency !== 'monthly' ? " (Original: ৳" . number_format($originalAmount, 2) . " " . ucfirst($frequency) . ")" : ""),
            'is_custom' => false,
            'is_recurring' => $category->is_recurring,
            'sort_order' => $category->sort_order
        ]);
    }

    public static function calculateMonthlyAmount($amount, $frequency)
    {
        switch ($frequency) {
            case 'yearly':
                return $amount / 12; // Divide yearly by 12 months
            case 'weekly':
                return $amount * 4.33; // Multiply weekly by average weeks per month (52/12)
            case 'daily':
                return $amount * 30.44; // Multiply daily by average days per month (365/12)
            case 'monthly':
            default:
                return $amount; // Monthly remains the same
        }
    }

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
}
