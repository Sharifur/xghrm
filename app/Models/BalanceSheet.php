<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class BalanceSheet extends Model
{
    use HasFactory;

    protected $fillable = [
        'month',
        'year',
        'month_number',
        'total_assets',
        'total_liabilities',
        'total_equity',
        'is_balanced',
        'forecast_data',
        'created_by',
        'updated_by'
    ];

    protected $casts = [
        'total_assets' => 'decimal:2',
        'total_liabilities' => 'decimal:2',
        'total_equity' => 'decimal:2',
        'is_balanced' => 'boolean',
        'forecast_data' => 'array',
        'year' => 'integer',
        'month_number' => 'integer'
    ];

    public function items()
    {
        return $this->hasMany(BalanceSheetItem::class)->orderBy('sort_order');
    }

    public function assets()
    {
        return $this->hasMany(BalanceSheetItem::class)->where('type', 'asset')->orderBy('sort_order');
    }

    public function liabilities()
    {
        return $this->hasMany(BalanceSheetItem::class)->where('type', 'liability')->orderBy('sort_order');
    }

    public function equity()
    {
        return $this->hasMany(BalanceSheetItem::class)->where('type', 'equity')->orderBy('sort_order');
    }

    public function creator()
    {
        return $this->belongsTo(Admin::class, 'created_by');
    }

    public function updater()
    {
        return $this->belongsTo(Admin::class, 'updated_by');
    }

    public static function findOrCreateForMonth($month)
    {
        $carbonDate = Carbon::createFromFormat('Y-m', $month);
        
        return static::firstOrCreate(
            ['month' => $month],
            [
                'year' => $carbonDate->year,
                'month_number' => $carbonDate->month,
                'total_assets' => 0,
                'total_liabilities' => 0,
                'total_equity' => 0,
                'is_balanced' => false
            ]
        );
    }

    public function calculateTotals()
    {
        // Use BDT amounts for proper totals calculation
        $this->total_assets = $this->assets()->sum('bdt_amount');
        $this->total_liabilities = $this->liabilities()->sum('bdt_amount');
        $this->total_equity = $this->equity()->sum('bdt_amount');
        $this->is_balanced = abs($this->total_assets - ($this->total_liabilities + $this->total_equity)) < 0.01;
        
        return $this;
    }

    public function generateForecast()
    {
        $previousMonths = static::where('month', '<', $this->month)
            ->orderBy('month', 'desc')
            ->limit(6)
            ->get();

        $forecastData = [];
        
        if ($previousMonths->count() > 0) {
            $categories = ExpenseCategory::where('is_recurring', true)->get();
            
            foreach ($categories as $category) {
                $averageAmount = 0;
                $count = 0;
                
                foreach ($previousMonths as $prevMonth) {
                    $item = $prevMonth->items()->where('name', $category->name)->first();
                    if ($item) {
                        $averageAmount += $item->amount;
                        $count++;
                    }
                }
                
                if ($count > 0) {
                    $forecastData[$category->name] = [
                        'average' => round($averageAmount / $count, 2),
                        'type' => $category->type,
                        'confidence' => min(100, ($count / min(6, $previousMonths->count())) * 100)
                    ];
                }
            }
        }

        $this->forecast_data = $forecastData;
        return $this;
    }
}
