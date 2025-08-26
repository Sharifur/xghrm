<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BalanceSheet;
use App\Models\BalanceSheetItem;
use App\Models\ExpenseCategory;
use App\Models\Expense;
use App\Models\IncomeSource;
use App\Models\OneTimeExpense;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;
use Carbon\Carbon;

class FinanceController extends Controller
{
    public function dashboard()
    {
        $currentMonth = Carbon::now()->format('Y-m');
        
        // Get dynamic financial data for dashboard
        $financialData = $this->getDashboardFinancialData($currentMonth);
        
        return Inertia::render('Backend/Finance/Dashboard', [
            'financialData' => $financialData,
            'currentMonth' => $currentMonth
        ]);
    }

    public function balanceSheet()
    {
        $currentDate = Carbon::now()->format('Y-m');
        $balanceSheet = BalanceSheet::findOrCreateForMonth($currentDate);
        
        // Aggregate dynamic data from all sources
        $this->aggregateDynamicFinancialData($balanceSheet, $currentDate);
        
        $balanceSheetData = $this->prepareBalanceSheetData($balanceSheet);
        
        return Inertia::render('Backend/Finance/BalanceSheet', [
            'currentDate' => $currentDate,
            'balanceSheetData' => $balanceSheetData,
            'categories' => ExpenseCategory::active()->orderBy('type')->orderBy('sort_order')->get(),
            'monthlyExpenses' => $this->getMonthlyExpenseSummary($currentDate),
            'dynamicData' => $this->getDynamicFinancialSummary($currentDate)
        ]);
    }

    public function saveBalanceSheet(Request $request)
    {
        $request->validate([
            'date' => 'required|string|regex:/^\d{4}-\d{2}$/',
            'items' => 'required|array',
            'items.*.name' => 'required|string',
            'items.*.type' => 'required|in:asset,liability,equity',
            'items.*.amount' => 'required|numeric|min:0',
        ]);

        try {
            DB::beginTransaction();

            $balanceSheet = BalanceSheet::findOrCreateForMonth($request->date);
            $balanceSheet->updated_by = Auth::guard('admin')->id();

            // Clear existing items
            $balanceSheet->items()->delete();

            // Create new items
            foreach ($request->items as $index => $itemData) {
                $item = new BalanceSheetItem([
                    'type' => $itemData['type'],
                    'name' => $itemData['name'],
                    'amount' => $itemData['amount'],
                    'tooltip' => $itemData['tooltip'] ?? null,
                    'is_custom' => $itemData['is_custom'] ?? false,
                    'is_recurring' => $itemData['is_recurring'] ?? false,
                    'sort_order' => $index
                ]);

                $balanceSheet->items()->save($item);
                
                // Update average for recurring items
                if ($item->is_recurring) {
                    $item->updateAverageAmount()->save();
                }
            }

            // Recalculate totals and generate forecast
            $balanceSheet->calculateTotals()->generateForecast()->save();

            DB::commit();

            return response()->json([
                'success' => true, 
                'message' => 'Balance sheet saved successfully',
                'balanceSheet' => $this->prepareBalanceSheetData($balanceSheet)
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['success' => false, 'message' => 'Failed to save balance sheet: ' . $e->getMessage()], 500);
        }
    }

    public function loadBalanceSheet($date)
    {
        $balanceSheet = BalanceSheet::findOrCreateForMonth($date);
        
        // Aggregate dynamic data for the requested month
        $this->aggregateDynamicFinancialData($balanceSheet, $date);
        
        $balanceSheetData = $this->prepareBalanceSheetData($balanceSheet);
        
        return response()->json([
            'balanceSheetData' => $balanceSheetData,
            'monthlyExpenses' => $this->getMonthlyExpenseSummary($date),
            'dynamicData' => $this->getDynamicFinancialSummary($date)
        ]);
    }

    public function exportBalanceSheet($date)
    {
        $balanceSheet = BalanceSheet::findOrCreateForMonth($date);
        
        $csv = "Balance Sheet - {$date}\n\n";
        
        // Assets
        $csv .= "ASSETS\n";
        foreach ($balanceSheet->assets as $asset) {
            $csv .= "{$asset->name},{$asset->amount}\n";
        }
        $csv .= "Total Assets,{$balanceSheet->total_assets}\n\n";
        
        // Liabilities
        $csv .= "LIABILITIES\n";
        foreach ($balanceSheet->liabilities as $liability) {
            $csv .= "{$liability->name},{$liability->amount}\n";
        }
        $csv .= "Total Liabilities,{$balanceSheet->total_liabilities}\n\n";
        
        // Equity
        $csv .= "EQUITY\n";
        foreach ($balanceSheet->equity as $equity) {
            $csv .= "{$equity->name},{$equity->amount}\n";
        }
        $csv .= "Total Equity,{$balanceSheet->total_equity}\n\n";
        
        $csv .= "Total Liabilities + Equity," . ($balanceSheet->total_liabilities + $balanceSheet->total_equity) . "\n";
        $csv .= "Balanced," . ($balanceSheet->is_balanced ? 'Yes' : 'No') . "\n";
        
        // Forecast data
        if ($balanceSheet->forecast_data) {
            $csv .= "\n\nFORECAST DATA\n";
            foreach ($balanceSheet->forecast_data as $name => $forecast) {
                $csv .= "{$name},{$forecast['average']},{$forecast['confidence']}%\n";
            }
        }
        
        return response($csv)
            ->header('Content-Type', 'text/csv')
            ->header('Content-Disposition', "attachment; filename=balance_sheet_{$date}.csv");
    }

    private function prepareBalanceSheetData(BalanceSheet $balanceSheet)
    {
        // Initialize with default categories if no items exist
        if ($balanceSheet->items()->count() === 0) {
            $this->initializeBalanceSheetItems($balanceSheet);
            $balanceSheet->refresh();
        }

        return [
            'id' => $balanceSheet->id,
            'month' => $balanceSheet->month,
            'assets' => $balanceSheet->assets->map(function ($item) use ($balanceSheet) {
                return [
                    'id' => $item->id,
                    'name' => $item->name,
                    'amount' => (float) $item->amount,
                    'currency' => $item->currency ?? 'BDT',
                    'bdt_amount' => (float) $item->bdt_amount,
                    'formatted_amount' => $item->formatted_amount,
                    'converted_amount' => $item->converted_amount,
                    'tooltip' => $item->tooltip,
                    'is_custom' => $item->is_custom,
                    'is_recurring' => $item->is_recurring,
                    'average_amount' => (float) $item->average_amount,
                    'forecast' => $this->getForecastData($balanceSheet, $item->name)
                ];
            })->toArray(),
            'liabilities' => $balanceSheet->liabilities->map(function ($item) use ($balanceSheet) {
                return [
                    'id' => $item->id,
                    'name' => $item->name,
                    'amount' => (float) $item->amount,
                    'currency' => $item->currency ?? 'BDT',
                    'bdt_amount' => (float) $item->bdt_amount,
                    'formatted_amount' => $item->formatted_amount,
                    'converted_amount' => $item->converted_amount,
                    'tooltip' => $item->tooltip,
                    'is_custom' => $item->is_custom,
                    'is_recurring' => $item->is_recurring,
                    'average_amount' => (float) $item->average_amount,
                    'forecast' => $this->getForecastData($balanceSheet, $item->name)
                ];
            })->toArray(),
            'equity' => $balanceSheet->equity->map(function ($item) use ($balanceSheet) {
                return [
                    'id' => $item->id,
                    'name' => $item->name,
                    'amount' => (float) $item->amount,
                    'currency' => $item->currency ?? 'BDT',
                    'bdt_amount' => (float) $item->bdt_amount,
                    'formatted_amount' => $item->formatted_amount,
                    'converted_amount' => $item->converted_amount,
                    'tooltip' => $item->tooltip,
                    'is_custom' => $item->is_custom,
                    'is_recurring' => $item->is_recurring,
                    'average_amount' => (float) $item->average_amount,
                    'forecast' => $this->getForecastData($balanceSheet, $item->name)
                ];
            })->toArray(),
            'totals' => [
                'assets' => (float) $balanceSheet->total_assets,
                'liabilities' => (float) $balanceSheet->total_liabilities,
                'equity' => (float) $balanceSheet->total_equity,
                'liabilitiesEquity' => (float) ($balanceSheet->total_liabilities + $balanceSheet->total_equity)
            ],
            'balanced' => $balanceSheet->is_balanced,
            'forecast_data' => $balanceSheet->forecast_data ?? [],
            'created_at' => $balanceSheet->created_at,
            'updated_at' => $balanceSheet->updated_at
        ];
    }

    private function initializeBalanceSheetItems(BalanceSheet $balanceSheet)
    {
        $categories = ExpenseCategory::active()->orderBy('type')->orderBy('sort_order')->get();
        
        foreach ($categories as $category) {
            BalanceSheetItem::createFromCategory($category, $balanceSheet);
        }
        
        $balanceSheet->calculateTotals()->save();
    }

    private function getForecastData(BalanceSheet $balanceSheet, $itemName)
    {
        if ($balanceSheet->forecast_data && isset($balanceSheet->forecast_data[$itemName])) {
            return $balanceSheet->forecast_data[$itemName];
        }
        
        return null;
    }

    public function generateForecast($date)
    {
        try {
            $balanceSheet = BalanceSheet::findOrCreateForMonth($date);
            $balanceSheet->generateForecast()->save();
            
            return response()->json([
                'success' => true,
                'forecast_data' => $balanceSheet->forecast_data,
                'message' => 'Forecast generated successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to generate forecast: ' . $e->getMessage()
            ], 500);
        }
    }

    public function applyForecast(Request $request)
    {
        $request->validate([
            'date' => 'required|string|regex:/^\d{4}-\d{2}$/',
            'items' => 'required|array'
        ]);

        try {
            DB::beginTransaction();

            $balanceSheet = BalanceSheet::findOrCreateForMonth($request->date);
            
            // Apply forecast data to items
            foreach ($request->items as $itemName) {
                if (isset($balanceSheet->forecast_data[$itemName])) {
                    $forecastData = $balanceSheet->forecast_data[$itemName];
                    
                    $item = $balanceSheet->items()->where('name', $itemName)->first();
                    if ($item) {
                        $item->amount = $forecastData['average'];
                        $item->save();
                    } else {
                        // Create new item from forecast
                        $category = ExpenseCategory::where('name', $itemName)->first();
                        if ($category) {
                            $newItem = BalanceSheetItem::createFromCategory($category, $balanceSheet);
                            $newItem->amount = $forecastData['average'];
                            $newItem->save();
                        }
                    }
                }
            }

            // Recalculate totals
            $balanceSheet->calculateTotals()->save();

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Forecast applied successfully',
                'balanceSheet' => $this->prepareBalanceSheetData($balanceSheet)
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Failed to apply forecast: ' . $e->getMessage()
            ], 500);
        }
    }

    public function recurringExpenses()
    {
        $expenses = ExpenseCategory::where('is_recurring', true)
            ->orderBy('type')
            ->orderBy('sort_order')
            ->get();

        return Inertia::render('Backend/Finance/RecurringExpenses', [
            'expenses' => $expenses
        ]);
    }

    public function storeRecurringExpense(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'type' => 'required|in:liability',
            'default_amount' => 'required|numeric|min:0',
            'frequency' => 'required|in:monthly,weekly,yearly',
            'icon' => 'nullable|string',
            'tooltip' => 'nullable|string'
        ]);

        try {
            $expense = ExpenseCategory::create([
                'name' => $request->name,
                'description' => $request->description,
                'type' => $request->type,
                'default_amount' => $request->default_amount,
                'icon' => $request->icon ?: 'fas fa-receipt',
                'color' => '#ffc107',
                'is_recurring' => true,
                'tooltip' => $request->tooltip ?: $request->description,
                'is_active' => true,
                'sort_order' => ExpenseCategory::where('type', $request->type)->max('sort_order') + 1
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Recurring expense created successfully',
                'expense' => $expense
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to create recurring expense: ' . $e->getMessage()
            ], 500);
        }
    }

    public function updateRecurringExpense(Request $request, $id)
    {
        $expense = ExpenseCategory::findOrFail($id);
        
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'default_amount' => 'required|numeric|min:0',
            'icon' => 'nullable|string',
            'tooltip' => 'nullable|string'
        ]);

        try {
            $expense->update([
                'name' => $request->name,
                'description' => $request->description,
                'default_amount' => $request->default_amount,
                'icon' => $request->icon ?: $expense->icon,
                'tooltip' => $request->tooltip ?: $request->description
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Recurring expense updated successfully',
                'expense' => $expense
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update recurring expense: ' . $e->getMessage()
            ], 500);
        }
    }

    public function deleteRecurringExpense($id)
    {
        try {
            $expense = ExpenseCategory::findOrFail($id);
            $expense->delete();

            return response()->json([
                'success' => true,
                'message' => 'Recurring expense deleted successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete recurring expense: ' . $e->getMessage()
            ], 500);
        }
    }

    public function assets()
    {
        $assets = ExpenseCategory::where('type', 'asset')
            ->orderBy('sort_order')
            ->get();

        return Inertia::render('Backend/Finance/Assets', [
            'assets' => $assets
        ]);
    }

    public function storeAsset(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'default_amount' => 'nullable|numeric|min:0',
            'icon' => 'nullable|string',
            'tooltip' => 'nullable|string'
        ]);

        try {
            $asset = ExpenseCategory::create([
                'name' => $request->name,
                'description' => $request->description,
                'type' => 'asset',
                'default_amount' => $request->default_amount ?: 0,
                'icon' => $request->icon ?: 'fas fa-coins',
                'color' => '#28a745',
                'is_recurring' => false,
                'tooltip' => $request->tooltip ?: $request->description,
                'is_active' => true,
                'sort_order' => ExpenseCategory::where('type', 'asset')->max('sort_order') + 1
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Asset created successfully',
                'asset' => $asset
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to create asset: ' . $e->getMessage()
            ], 500);
        }
    }

    public function updateAsset(Request $request, $id)
    {
        $asset = ExpenseCategory::findOrFail($id);
        
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'default_amount' => 'nullable|numeric|min:0',
            'icon' => 'nullable|string',
            'tooltip' => 'nullable|string'
        ]);

        try {
            $asset->update([
                'name' => $request->name,
                'description' => $request->description,
                'default_amount' => $request->default_amount ?: 0,
                'icon' => $request->icon ?: $asset->icon,
                'tooltip' => $request->tooltip ?: $request->description
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Asset updated successfully',
                'asset' => $asset
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update asset: ' . $e->getMessage()
            ], 500);
        }
    }

    public function deleteAsset($id)
    {
        try {
            $asset = ExpenseCategory::findOrFail($id);
            $asset->delete();

            return response()->json([
                'success' => true,
                'message' => 'Asset deleted successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete asset: ' . $e->getMessage()
            ], 500);
        }
    }

    public function equity()
    {
        $equity = ExpenseCategory::where('type', 'equity')
            ->orderBy('sort_order')
            ->get();

        return Inertia::render('Backend/Finance/Equity', [
            'equity' => $equity
        ]);
    }

    public function storeEquity(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'default_amount' => 'nullable|numeric',
            'icon' => 'nullable|string',
            'tooltip' => 'nullable|string'
        ]);

        try {
            $equity = ExpenseCategory::create([
                'name' => $request->name,
                'description' => $request->description,
                'type' => 'equity',
                'default_amount' => $request->default_amount ?: 0,
                'icon' => $request->icon ?: 'fas fa-chart-line',
                'color' => '#17a2b8',
                'is_recurring' => false,
                'tooltip' => $request->tooltip ?: $request->description,
                'is_active' => true,
                'sort_order' => ExpenseCategory::where('type', 'equity')->max('sort_order') + 1
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Equity item created successfully',
                'equity' => $equity
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to create equity item: ' . $e->getMessage()
            ], 500);
        }
    }

    public function updateEquity(Request $request, $id)
    {
        $equity = ExpenseCategory::findOrFail($id);
        
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'default_amount' => 'nullable|numeric',
            'icon' => 'nullable|string',
            'tooltip' => 'nullable|string'
        ]);

        try {
            $equity->update([
                'name' => $request->name,
                'description' => $request->description,
                'default_amount' => $request->default_amount ?: 0,
                'icon' => $request->icon ?: $equity->icon,
                'tooltip' => $request->tooltip ?: $request->description
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Equity item updated successfully',
                'equity' => $equity
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update equity item: ' . $e->getMessage()
            ], 500);
        }
    }

    public function deleteEquity($id)
    {
        try {
            $equity = ExpenseCategory::findOrFail($id);
            $equity->delete();

            return response()->json([
                'success' => true,
                'message' => 'Equity item deleted successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete equity item: ' . $e->getMessage()
            ], 500);
        }
    }

    public function expenses()
    {
        $expenses = OneTimeExpense::with(['creator', 'updater'])
            ->orderBy('expense_date', 'desc')
            ->orderBy('created_at', 'desc')
            ->get();

        return Inertia::render('Backend/Finance/Expenses', [
            'expenses' => $expenses
        ]);
    }

    public function storeExpense(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'amount' => 'required|numeric|min:0',
            'currency' => 'required|in:BDT,USD',
            'expense_date' => 'required|date',
            'category' => 'required|string|max:255',
            'description' => 'nullable|string',
            'notes' => 'nullable|string',
            'icon' => 'nullable|string'
        ]);

        try {
            $expense = OneTimeExpense::create([
                'name' => $request->name,
                'description' => $request->description,
                'amount' => $request->amount,
                'currency' => $request->currency,
                'expense_date' => $request->expense_date,
                'category' => $request->category,
                'icon' => $request->icon ?? 'fas fa-receipt',
                'notes' => $request->notes,
                'created_by' => Auth::guard('admin')->id(),
                'updated_by' => Auth::guard('admin')->id()
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Expense added successfully',
                'expense' => $expense->load(['creator', 'updater'])
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to add expense: ' . $e->getMessage()
            ], 500);
        }
    }

    public function updateExpense(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'amount' => 'required|numeric|min:0',
            'currency' => 'required|in:BDT,USD',
            'expense_date' => 'required|date',
            'category' => 'required|string|max:255',
            'description' => 'nullable|string',
            'notes' => 'nullable|string',
            'icon' => 'nullable|string'
        ]);

        try {
            $expense = OneTimeExpense::findOrFail($id);
            $expense->update([
                'name' => $request->name,
                'description' => $request->description,
                'amount' => $request->amount,
                'currency' => $request->currency,
                'expense_date' => $request->expense_date,
                'category' => $request->category,
                'icon' => $request->icon ?? 'fas fa-receipt',
                'notes' => $request->notes,
                'updated_by' => Auth::guard('admin')->id()
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Expense updated successfully',
                'expense' => $expense->load(['creator', 'updater'])
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update expense: ' . $e->getMessage()
            ], 500);
        }
    }

    public function deleteExpense($id)
    {
        try {
            $expense = OneTimeExpense::findOrFail($id);
            $expense->delete();

            return response()->json([
                'success' => true,
                'message' => 'Expense deleted successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete expense: ' . $e->getMessage()
            ], 500);
        }
    }

    public function budgets()
    {
        return Inertia::render('Backend/Finance/Budgets');
    }

    public function reports()
    {
        return Inertia::render('Backend/Finance/Reports');
    }

    private function aggregateDynamicFinancialData(BalanceSheet $balanceSheet, $month)
    {
        $year = substr($month, 0, 4);
        $monthNumber = substr($month, 5, 2);

        // Get one-time expenses for this month
        $monthlyExpenses = OneTimeExpense::whereYear('expense_date', $year)
            ->whereMonth('expense_date', $monthNumber)
            ->get();

        // Calculate total expenses in BDT
        $totalExpenses = $monthlyExpenses->sum('bdt_amount');

        // Update or create "Monthly Expenses" liability item
        $expenseItem = $balanceSheet->items()->where('name', 'Monthly Expenses')->first();
        if (!$expenseItem) {
            $expenseItem = BalanceSheetItem::create([
                'balance_sheet_id' => $balanceSheet->id,
                'type' => 'liability',
                'name' => 'Monthly Expenses',
                'amount' => 0,
                'currency' => 'BDT',
                'bdt_amount' => 0,
                'tooltip' => 'Auto-calculated from one-time expenses',
                'is_custom' => false,
                'is_recurring' => false,
                'sort_order' => 100
            ]);
        }

        // Update the expense amount
        $expenseItem->amount = $totalExpenses;
        $expenseItem->bdt_amount = $totalExpenses;
        $expenseItem->currency = 'BDT';
        $expenseItem->save();

        // Add cash adjustment for expenses (reduce cash assets)
        $cashItem = $balanceSheet->items()->where('name', 'Cash & Bank')->first();
        if (!$cashItem) {
            $cashItem = BalanceSheetItem::create([
                'balance_sheet_id' => $balanceSheet->id,
                'type' => 'asset',
                'name' => 'Cash & Bank',
                'amount' => 0,
                'currency' => 'BDT',
                'bdt_amount' => 0,
                'tooltip' => 'Cash position after expenses',
                'is_custom' => false,
                'is_recurring' => false,
                'sort_order' => 1
            ]);
        }

        // Recalculate totals
        $balanceSheet->calculateTotals()->save();
    }

    private function getMonthlyExpenseSummary($month)
    {
        $year = substr($month, 0, 4);
        $monthNumber = substr($month, 5, 2);

        return OneTimeExpense::whereYear('expense_date', $year)
            ->whereMonth('expense_date', $monthNumber)
            ->selectRaw('category, COUNT(*) as count, SUM(bdt_amount) as total_amount')
            ->groupBy('category')
            ->orderBy('total_amount', 'desc')
            ->get();
    }

    private function getDynamicFinancialSummary($month)
    {
        $year = substr($month, 0, 4);
        $monthNumber = substr($month, 5, 2);

        $oneTimeExpenses = OneTimeExpense::whereYear('expense_date', $year)
            ->whereMonth('expense_date', $monthNumber)
            ->sum('bdt_amount');

        $recurringExpenses = ExpenseCategory::where('is_recurring', true)
            ->where('type', 'liability')
            ->sum('bdt_amount');

        $totalAssets = ExpenseCategory::where('type', 'asset')
            ->sum('bdt_amount');

        $totalEquity = ExpenseCategory::where('type', 'equity')
            ->sum('bdt_amount');

        return [
            'one_time_expenses' => $oneTimeExpenses,
            'recurring_expenses' => $recurringExpenses,
            'total_expenses' => $oneTimeExpenses + $recurringExpenses,
            'total_assets' => $totalAssets,
            'total_equity' => $totalEquity,
            'net_position' => $totalAssets - ($oneTimeExpenses + $recurringExpenses) + $totalEquity
        ];
    }

    public function loadDashboardData($month)
    {
        $financialData = $this->getDashboardFinancialData($month);
        
        return response()->json([
            'success' => true,
            'financialData' => $financialData
        ]);
    }

    public function documentation()
    {
        return Inertia::render('Backend/Finance/Documentation');
    }

    private function getDashboardFinancialData($month)
    {
        $year = substr($month, 0, 4);
        $monthNumber = substr($month, 5, 2);
        $currentDate = Carbon::create($year, $monthNumber, 1);
        $lastMonth = $currentDate->copy()->subMonth();

        // Get current month expenses
        $currentMonthExpenses = OneTimeExpense::whereYear('expense_date', $year)
            ->whereMonth('expense_date', $monthNumber)
            ->get();

        $totalCurrentExpenses = $currentMonthExpenses->sum('bdt_amount');

        // Get last month expenses for comparison
        $lastMonthExpenses = OneTimeExpense::whereYear('expense_date', $lastMonth->year)
            ->whereMonth('expense_date', $lastMonth->month)
            ->sum('bdt_amount');

        // Calculate expense change percentage
        $expenseChange = $lastMonthExpenses > 0 ? 
            (($totalCurrentExpenses - $lastMonthExpenses) / $lastMonthExpenses) * 100 : 0;

        // Get recurring expenses (monthly liabilities)
        $recurringExpenses = ExpenseCategory::where('is_recurring', true)
            ->where('type', 'liability')
            ->sum('default_amount');

        // Total expenses including recurring
        $totalExpenses = $totalCurrentExpenses + $recurringExpenses;

        // Calculate assets, liabilities, and equity from balance sheet
        $totalAssets = ExpenseCategory::where('type', 'asset')->sum('default_amount');
        $totalLiabilities = ExpenseCategory::where('type', 'liability')->sum('default_amount') + $totalCurrentExpenses;
        $totalEquity = ExpenseCategory::where('type', 'equity')->sum('default_amount');

        // Mock revenue calculation (you can replace with real revenue source)
        $totalRevenue = $totalExpenses * 1.6; // Assuming 60% profit margin
        $lastMonthRevenue = $lastMonthExpenses > 0 ? $lastMonthExpenses * 1.6 : $totalRevenue * 0.9;
        $revenueChange = $lastMonthRevenue > 0 ? 
            (($totalRevenue - $lastMonthRevenue) / $lastMonthRevenue) * 100 : 15;

        $netProfit = $totalRevenue - $totalExpenses;
        $pendingRevenue = $totalRevenue * 0.15; // Assume 15% pending
        $pendingCount = max(1, intval($pendingRevenue / ($totalRevenue / 10)));

        // Calculate monthly recurring revenue (from recurring liabilities as income proxy)
        $monthlyRecurringRevenue = $recurringExpenses * 2; // Assume 2x recurring expenses as recurring revenue
        
        // Calculate next month forecast (5% growth + seasonal adjustments)
        $nextMonthForecast = $totalRevenue * 1.05;

        // Get monthly trend data (last 6 months)
        $trendData = [];
        for ($i = 5; $i >= 0; $i--) {
            $trendMonth = $currentDate->copy()->subMonths($i);
            $monthlyExpenses = OneTimeExpense::whereYear('expense_date', $trendMonth->year)
                ->whereMonth('expense_date', $trendMonth->month)
                ->sum('bdt_amount');
            
            $monthlyRevenue = $monthlyExpenses > 0 ? round($monthlyExpenses * 1.6, 2) : round($totalRevenue * 0.8, 2);

            $trendData[] = [
                'name' => $trendMonth->format('M'),
                'revenue' => $monthlyRevenue,
                'expenses' => round($monthlyExpenses + ($recurringExpenses * 0.8), 2) // Include portion of recurring
            ];
        }

        // Get expense categories breakdown
        $expenseCategories = $currentMonthExpenses->groupBy('category')->map(function ($expenses, $category) {
            $total = round($expenses->sum('bdt_amount'), 2);
            return [
                'name' => $category ?: 'Uncategorized',
                'amount' => $total,
                'icon' => $this->getCategoryIcon($category),
                'color' => $this->getCategoryColor($category)
            ];
        })->sortByDesc('amount')->take(5)->values();

        // Add recurring categories if they don't exist
        $recurringCategories = ExpenseCategory::where('is_recurring', true)
            ->where('type', 'liability')
            ->get()
            ->map(function ($category) {
                return [
                    'name' => $category->name,
                    'amount' => round($category->default_amount, 2),
                    'icon' => $category->icon,
                    'color' => $this->getCategoryColor($category->name)
                ];
            });

        $expenseCategories = $expenseCategories->merge($recurringCategories)
            ->sortByDesc('amount')
            ->take(5)
            ->values();

        // Get recent transactions
        $recentTransactions = $currentMonthExpenses->take(5)->map(function ($expense) {
            return [
                'id' => $expense->id,
                'description' => $expense->name,
                'amount' => round($expense->bdt_amount, 2),
                'type' => 'expense',
                'icon' => $expense->icon ?: 'fas fa-arrow-up',
                'date' => $expense->expense_date,
                'category' => $expense->category
            ];
        });

        // Mock some income transactions for display
        $mockIncomes = collect([
            [
                'id' => 'inc_1',
                'description' => 'Service Revenue',
                'amount' => round($totalRevenue * 0.4, 2),
                'type' => 'income',
                'icon' => 'fas fa-arrow-down',
                'date' => $currentDate->format('Y-m-d')
            ],
            [
                'id' => 'inc_2', 
                'description' => 'Product Sales',
                'amount' => round($totalRevenue * 0.3, 2),
                'type' => 'income',
                'icon' => 'fas fa-arrow-down',
                'date' => $currentDate->subDays(2)->format('Y-m-d')
            ]
        ]);

        $recentTransactions = $mockIncomes->merge($recentTransactions)
            ->sortByDesc('date')
            ->take(5)
            ->values();

        // Budget status calculation
        $budgetStatus = $expenseCategories->map(function ($category) use ($totalExpenses) {
            $budgetAmount = $category['amount'] * 1.2; // 20% buffer
            $used = $category['amount'];
            
            return [
                'name' => $category['name'],
                'used' => $used,
                'total' => $budgetAmount,
                'remaining' => $budgetAmount - $used,
                'icon' => $category['icon']
            ];
        });

        // Financial alerts
        $financialAlerts = [];
        
        // Budget alerts
        foreach ($budgetStatus as $budget) {
            $percentage = ($budget['used'] / $budget['total']) * 100;
            if ($percentage >= 100) {
                $financialAlerts[] = [
                    'id' => 'budget_' . \Str::slug($budget['name']),
                    'type' => 'warning',
                    'title' => 'Budget Exceeded',
                    'message' => $budget['name'] . ' budget is ' . round($percentage) . '% used',
                    'icon' => 'fas fa-exclamation-triangle',
                    'date' => now()
                ];
            }
        }

        // Revenue alerts
        if ($revenueChange > 20) {
            $financialAlerts[] = [
                'id' => 'revenue_growth',
                'type' => 'success',
                'title' => 'Revenue Growth',
                'message' => 'Monthly revenue increased by ' . round($revenueChange, 1) . '%',
                'icon' => 'fas fa-check-circle',
                'date' => now()
            ];
        }

        // Expense alerts
        if ($expenseChange > 15) {
            $financialAlerts[] = [
                'id' => 'expense_increase',
                'type' => 'info',
                'title' => 'Expense Increase',
                'message' => 'Monthly expenses increased by ' . round($expenseChange, 1) . '%',
                'icon' => 'fas fa-info-circle',
                'date' => now()
            ];
        }

        return [
            'totalRevenue' => round($totalRevenue, 2),
            'totalExpenses' => round($totalExpenses, 2),
            'netProfit' => round($netProfit, 2),
            'pendingRevenue' => round($pendingRevenue, 2),
            'pendingCount' => $pendingCount,
            'recurringRevenue' => round($monthlyRecurringRevenue, 2),
            'nextMonthForecast' => round($nextMonthForecast, 2),
            'totalAssets' => round($totalAssets, 2),
            'totalLiabilities' => round($totalLiabilities, 2),
            'totalEquity' => round($totalEquity, 2),
            'revenueChange' => round($revenueChange, 2),
            'expenseChange' => round($expenseChange, 2),
            'trendData' => collect($trendData)->map(function ($item) {
                return [
                    'name' => $item['name'],
                    'revenue' => round($item['revenue'], 2),
                    'expenses' => round($item['expenses'], 2)
                ];
            })->toArray(),
            'expenseCategories' => $expenseCategories,
            'recentTransactions' => $recentTransactions->map(function ($transaction) {
                $transaction['amount'] = round($transaction['amount'], 2);
                return $transaction;
            }),
            'budgetStatus' => $budgetStatus->map(function ($budget) {
                return [
                    'name' => $budget['name'],
                    'used' => round($budget['used'], 2),
                    'total' => round($budget['total'], 2),
                    'remaining' => round($budget['remaining'], 2),
                    'icon' => $budget['icon']
                ];
            }),
            'financialAlerts' => collect($financialAlerts)->take(3)->values()
        ];
    }

    private function getCategoryIcon($category)
    {
        $icons = [
            'Technology & Software' => 'fas fa-laptop',
            'Marketing & Advertising' => 'fas fa-bullhorn', 
            'Office & Operations' => 'fas fa-building',
            'Professional Services' => 'fas fa-handshake',
            'Travel & Transportation' => 'fas fa-car',
            'Food & Dining' => 'fas fa-utensils',
            'Utilities' => 'fas fa-bolt',
            'Rent' => 'fas fa-home',
            'Insurance' => 'fas fa-shield-alt',
            'Supplies' => 'fas fa-box',
        ];

        return $icons[$category] ?? 'fas fa-receipt';
    }

    private function getCategoryColor($category)
    {
        $colors = [
            'Technology & Software' => 'bg-primary',
            'Marketing & Advertising' => 'bg-success',
            'Office & Operations' => 'bg-warning',
            'Professional Services' => 'bg-info',
            'Travel & Transportation' => 'bg-secondary',
            'Food & Dining' => 'bg-danger',
            'Utilities' => 'bg-dark',
            'Rent' => 'bg-primary',
            'Insurance' => 'bg-success',
            'Supplies' => 'bg-warning',
        ];

        return $colors[$category] ?? 'bg-info';
    }
}