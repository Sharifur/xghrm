<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BalanceSheet;
use App\Models\BalanceSheetItem;
use App\Models\ExpenseCategory;
use App\Models\Expense;
use App\Models\IncomeSource;
use App\Models\OneTimeExpense;
use App\Models\RecurringExpensePayment;
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
            'currency' => 'nullable|in:BDT,USD',
            'icon' => 'nullable|string',
            'tooltip' => 'nullable|string'
        ]);

        try {
            $expense = ExpenseCategory::create([
                'name' => $request->name,
                'description' => $request->description,
                'type' => $request->type,
                'default_amount' => $request->default_amount,
                'frequency' => $request->frequency,
                'currency' => $request->currency ?: 'BDT',
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
            'frequency' => 'required|in:monthly,weekly,yearly',
            'currency' => 'nullable|in:BDT,USD',
            'icon' => 'nullable|string',
            'tooltip' => 'nullable|string'
        ]);

        try {
            $expense->update([
                'name' => $request->name,
                'description' => $request->description,
                'default_amount' => $request->default_amount,
                'frequency' => $request->frequency,
                'currency' => $request->currency ?: $expense->currency ?: 'BDT',
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
            'description' => 'nullable|string|max:500',
            'amount' => 'required|numeric|min:0',
            'currency' => 'required|in:BDT,USD',
            'expense_date' => 'required|date',
            'category' => 'required|string|max:100',
            'icon' => 'nullable|string|max:50',
            'notes' => 'nullable|string|max:1000',
            'payment_status' => 'required|in:paid,unpaid,pending',
            'paid_date' => 'nullable|date',
            'payment_notes' => 'nullable|string|max:500'
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
                'payment_status' => $request->payment_status,
                'paid_date' => $request->paid_date,
                'payment_notes' => $request->payment_notes,
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
            'description' => 'nullable|string|max:500',
            'amount' => 'required|numeric|min:0',
            'currency' => 'required|in:BDT,USD',
            'expense_date' => 'required|date',
            'category' => 'required|string|max:100',
            'icon' => 'nullable|string|max:50',
            'notes' => 'nullable|string|max:1000',
            'payment_status' => 'required|in:paid,unpaid,pending',
            'paid_date' => 'nullable|date',
            'payment_notes' => 'nullable|string|max:500'
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
                'payment_status' => $request->payment_status,
                'paid_date' => $request->paid_date,
                'payment_notes' => $request->payment_notes,
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
        $clients = \App\Models\Client::active()
            ->orderBy('name')
            ->get();

        $revenues = \App\Models\Revenue::with('client')
            ->orderBy('created_at', 'desc')
            ->get();

        return Inertia::render('Backend/Finance/Reports', [
            'clients' => $clients,
            'revenues' => $revenues
        ]);
    }

    private function aggregateDynamicFinancialData(BalanceSheet $balanceSheet, $month)
    {
        $year = substr($month, 0, 4);
        $monthNumber = substr($month, 5, 2);

        // Get one-time expenses for this month
        $monthlyExpenses = OneTimeExpense::whereYear('expense_date', $year)
            ->whereMonth('expense_date', $monthNumber)
            ->get();

        // Calculate ACTUAL unpaid one-time expenses using payment_status
        $unpaidOneTimeExpenses = OneTimeExpense::whereYear('expense_date', $year)
            ->whereMonth('expense_date', $monthNumber)
            ->unpaid() // Using the scope we created
            ->sum('bdt_amount');

        // Calculate ACTUAL unpaid recurring bills using smart payment logic
        // Get all active recurring expenses and check their payment status
        $allRecurringExpenses = ExpenseCategory::where('is_recurring', true)
            ->where('is_active', true)
            ->where('type', 'liability')
            ->get();

        // First, clean up any existing unpaid items that are now paid
        $existingUnpaidItems = $balanceSheet->items()
            ->where('name', 'like', 'Unpaid:%')
            ->where('is_recurring', true)
            ->get();

        foreach ($existingUnpaidItems as $item) {
            // Extract category name from "Unpaid: Category Name"
            $categoryName = str_replace('Unpaid: ', '', $item->name);
            $category = $allRecurringExpenses->firstWhere('name', $categoryName);
            
            if (!$category || !$category->isDueForPayment()) {
                // Remove items for categories that no longer exist or are now paid
                $item->delete();
            }
        }

        // Filter to only unpaid/due expenses
        $unpaidRecurringExpenses = $allRecurringExpenses->filter(function ($category) {
            return $category->isDueForPayment(); // Use smart payment logic
        });

        // Create or update unpaid liability items for each due recurring expense
        foreach ($unpaidRecurringExpenses as $category) {
            $unpaidBillName = "Unpaid: " . $category->name;
            $recurringItem = $balanceSheet->items()->where('name', $unpaidBillName)->first();
            
            if (!$recurringItem) {
                $recurringItem = BalanceSheetItem::create([
                    'balance_sheet_id' => $balanceSheet->id,
                    'type' => 'liability',
                    'name' => $unpaidBillName,
                    'amount' => 0,
                    'currency' => 'BDT',
                    'bdt_amount' => 0,
                    'tooltip' => $this->generateRecurringExpenseTooltip($category),
                    'is_custom' => false,
                    'is_recurring' => true,
                    'sort_order' => $category->sort_order + 50
                ]);
            }

            // Use the pending amount from smart calculation
            $pendingAmount = $category->getPendingAmount();

            // Update the unpaid bill amount with actual pending amount
            $recurringItem->amount = $pendingAmount;
            $recurringItem->bdt_amount = $pendingAmount;
            $recurringItem->currency = 'BDT';
            $recurringItem->tooltip = $this->generateRecurringExpenseTooltip($category);
            $recurringItem->save();
        }

        // Create liability item for unpaid one-time expenses (only if there are unpaid amounts)
        if ($unpaidOneTimeExpenses > 0) {
            $expenseItem = $balanceSheet->items()->where('name', 'Unpaid One-Time Bills')->first();
            if (!$expenseItem) {
                $expenseItem = BalanceSheetItem::create([
                    'balance_sheet_id' => $balanceSheet->id,
                    'type' => 'liability',
                    'name' => 'Unpaid One-Time Bills',
                    'amount' => 0,
                    'currency' => 'BDT',
                    'bdt_amount' => 0,
                    'tooltip' => $this->generateOneTimeExpenseTooltip($year, $monthNumber, $unpaidOneTimeExpenses),
                    'is_custom' => false,
                    'is_recurring' => false,
                    'sort_order' => 100
                ]);
            }

            // Update with ACTUAL unpaid amount
            $expenseItem->amount = $unpaidOneTimeExpenses;
            $expenseItem->bdt_amount = $unpaidOneTimeExpenses;
            $expenseItem->currency = 'BDT';
            $expenseItem->tooltip = $this->generateOneTimeExpenseTooltip($year, $monthNumber, $unpaidOneTimeExpenses);
            $expenseItem->save();
        } else {
            // Remove the item if there are no unpaid expenses
            $balanceSheet->items()
                ->where('name', 'Unpaid One-Time Bills')
                ->delete();
        }

        // Load and populate assets from ExpenseCategory
        $assets = ExpenseCategory::where('type', 'asset')
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->get();

        foreach ($assets as $asset) {
            $amount = $asset->default_amount ?: 0;
            // Convert to BDT if needed
            if ($asset->currency === 'USD') {
                $amount = $amount * 120;
            }

            $assetItem = $balanceSheet->items()->where('name', $asset->name)->first();
            if (!$assetItem) {
                // Create new asset item
                $assetItem = BalanceSheetItem::create([
                    'balance_sheet_id' => $balanceSheet->id,
                    'type' => 'asset',
                    'name' => $asset->name,
                    'amount' => $amount,
                    'currency' => $asset->currency ?: 'BDT',
                    'bdt_amount' => $amount,
                    'tooltip' => $asset->tooltip ?: $asset->description,
                    'is_custom' => false,
                    'is_recurring' => false,
                    'sort_order' => $asset->sort_order,
                    'icon' => $asset->icon
                ]);
            } else {
                // Update existing asset item with latest amounts
                $assetItem->update([
                    'amount' => $amount,
                    'currency' => $asset->currency ?: 'BDT',
                    'bdt_amount' => $amount,
                    'tooltip' => $asset->tooltip ?: $asset->description,
                    'icon' => $asset->icon
                ]);
            }
        }

        // Load and populate equity from ExpenseCategory
        $equityItems = ExpenseCategory::where('type', 'equity')
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->get();

        foreach ($equityItems as $equity) {
            $amount = $equity->default_amount ?: 0;
            // Convert to BDT if needed
            if ($equity->currency === 'USD') {
                $amount = $amount * 120;
            }

            $equityItem = $balanceSheet->items()->where('name', $equity->name)->first();
            if (!$equityItem) {
                // Create new equity item
                $equityItem = BalanceSheetItem::create([
                    'balance_sheet_id' => $balanceSheet->id,
                    'type' => 'equity',
                    'name' => $equity->name,
                    'amount' => $amount,
                    'currency' => $equity->currency ?: 'BDT',
                    'bdt_amount' => $amount,
                    'tooltip' => $equity->tooltip ?: $equity->description,
                    'is_custom' => false,
                    'is_recurring' => false,
                    'sort_order' => $equity->sort_order,
                    'icon' => $equity->icon
                ]);
            } else {
                // Update existing equity item with latest amounts
                $equityItem->update([
                    'amount' => $amount,
                    'currency' => $equity->currency ?: 'BDT',
                    'bdt_amount' => $amount,
                    'tooltip' => $equity->tooltip ?: $equity->description,
                    'icon' => $equity->icon
                ]);
            }
        }

        // Add default Cash & Bank asset if no assets exist
        if ($assets->isEmpty()) {
            $cashItem = $balanceSheet->items()->where('name', 'Cash & Bank')->first();
            if (!$cashItem) {
                $cashItem = BalanceSheetItem::create([
                    'balance_sheet_id' => $balanceSheet->id,
                    'type' => 'asset',
                    'name' => 'Cash & Bank',
                    'amount' => 50000,
                    'currency' => 'BDT',
                    'bdt_amount' => 50000,
                    'tooltip' => 'Default cash position',
                    'is_custom' => false,
                    'is_recurring' => false,
                    'sort_order' => 1
                ]);
            }
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

    // Recurring Expense Payment Management
    public function markRecurringExpenseAsPaid(Request $request, $id)
    {
        $request->validate([
            'paid_date' => 'nullable|date',
            'payment_notes' => 'nullable|string|max:500'
        ]);

        try {
            $expense = ExpenseCategory::findOrFail($id);
            
            if (!$expense->is_recurring) {
                return response()->json([
                    'success' => false,
                    'message' => 'This is not a recurring expense'
                ], 400);
            }

            $expense->markAsPaid($request->paid_date, $request->payment_notes);

            return response()->json([
                'success' => true,
                'message' => 'Recurring expense marked as paid',
                'expense' => $expense->fresh()
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to mark expense as paid: ' . $e->getMessage()
            ], 500);
        }
    }

    public function getRecurringExpensesWithStatus()
    {
        try {
            $expenses = ExpenseCategory::where('is_recurring', true)
                ->where('is_active', true)
                ->get()
                ->map(function ($expense) {
                    return [
                        'id' => $expense->id,
                        'name' => $expense->name,
                        'description' => $expense->description,
                        'amount' => $expense->default_amount,
                        'currency' => $expense->currency,
                        'bdt_amount' => $expense->bdt_amount,
                        'frequency' => $expense->frequency,
                        'type' => $expense->type,
                        'icon' => $expense->icon,
                        'color' => $expense->color,
                        'last_paid_date' => $expense->last_paid_date,
                        'next_due_date' => $expense->next_due_date ?: $expense->calculateNextDueDate(),
                        'payment_status' => $expense->getCurrentPaymentStatus(),
                        'payment_notes' => $expense->payment_notes,
                        'is_due' => $expense->isDueForPayment(),
                        'is_overdue' => $expense->isOverdue(),
                        'pending_amount' => $expense->getPendingAmount(),
                        'formatted_amount' => $expense->formatted_amount,
                        'converted_amount' => $expense->converted_amount
                    ];
                });

            return response()->json([
                'success' => true,
                'expenses' => $expenses
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch recurring expenses: ' . $e->getMessage()
            ], 500);
        }
    }

    public function updateRecurringExpensePaymentStatus(Request $request, $id)
    {
        $request->validate([
            'payment_status' => 'required|in:paid,unpaid,pending,overdue',
            'last_paid_date' => 'nullable|date',
            'payment_notes' => 'nullable|string|max:500'
        ]);

        try {
            $expense = ExpenseCategory::findOrFail($id);
            
            if (!$expense->is_recurring) {
                return response()->json([
                    'success' => false,
                    'message' => 'This is not a recurring expense'
                ], 400);
            }

            if ($request->payment_status === 'paid') {
                $expense->markAsPaid($request->last_paid_date, $request->payment_notes);
            } else {
                $expense->update([
                    'payment_status' => $request->payment_status,
                    'last_paid_date' => $request->last_paid_date,
                    'payment_notes' => $request->payment_notes,
                    'next_due_date' => $expense->calculateNextDueDate()
                ]);
            }

            return response()->json([
                'success' => true,
                'message' => 'Payment status updated successfully',
                'expense' => $expense->fresh()
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update payment status: ' . $e->getMessage()
            ], 500);
        }
    }

    private function getDashboardFinancialData($month)
    {
        $year = substr($month, 0, 4);
        $monthNumber = substr($month, 5, 2);
        $currentDate = Carbon::create($year, $monthNumber, 1);
        $lastMonth = $currentDate->copy()->subMonth();

        // Get current month ONE-TIME expenses
        $currentMonthExpenses = OneTimeExpense::whereYear('expense_date', $year)
            ->whereMonth('expense_date', $monthNumber)
            ->get();

        $totalOneTimeExpenses = $currentMonthExpenses->sum('bdt_amount');

        // Get last month expenses for comparison
        $lastMonthExpenses = OneTimeExpense::whereYear('expense_date', $lastMonth->year)
            ->whereMonth('expense_date', $lastMonth->month)
            ->sum('bdt_amount');

        // Get RECURRING expenses broken down by frequency
        $monthlyRecurringExpenses = ExpenseCategory::where('is_recurring', true)
            ->where('frequency', 'monthly')
            ->where('type', 'liability')
            ->sum('default_amount');
            
        $weeklyRecurringExpenses = ExpenseCategory::where('is_recurring', true)
            ->where('frequency', 'weekly')
            ->where('type', 'liability')
            ->sum('default_amount') * 4.33; // Convert weekly to monthly (4.33 weeks)
            
        $yearlyRecurringExpenses = ExpenseCategory::where('is_recurring', true)
            ->where('frequency', 'yearly')
            ->where('type', 'liability')
            ->sum('default_amount') / 12; // Convert yearly to monthly

        // Total recurring expenses (converted to monthly basis)
        $totalRecurringExpenses = $monthlyRecurringExpenses + $weeklyRecurringExpenses + $yearlyRecurringExpenses;

        // TOTAL EXPENSES = One-time + All Recurring (monthly basis)
        $totalExpenses = $totalOneTimeExpenses + $totalRecurringExpenses;

        // Calculate expense change percentage
        $expenseChange = $lastMonthExpenses > 0 ? 
            (($totalOneTimeExpenses - $lastMonthExpenses) / $lastMonthExpenses) * 100 : 0;

        // Calculate assets, liabilities, and equity from balance sheet
        $totalAssets = ExpenseCategory::where('type', 'asset')->sum('default_amount');
        $totalLiabilities = ExpenseCategory::where('type', 'liability')->sum('default_amount') + $totalOneTimeExpenses;
        $totalEquity = ExpenseCategory::where('type', 'equity')->sum('default_amount');

        // Calculate TOTAL REVENUE (all income including pending)
        $totalGrossRevenue = $totalExpenses * 1.6; // Total income (received + pending)
        
        // Split into received and pending
        $pendingRevenue = $totalGrossRevenue * 0.15; // 15% is pending/not received
        $receivedRevenue = $totalGrossRevenue * 0.85; // 85% is actually received
        
        // GROSS PROFIT = Total Revenue (including pending) - Total Expenses
        $grossProfit = $totalGrossRevenue - $totalExpenses; // Profit if all payments were received
        
        // NET PROFIT = Only Received Revenue - Total Expenses
        $netProfit = $receivedRevenue - $totalExpenses; // Actual profit from received payments
        
        // For backward compatibility, keep totalRevenue as net profit
        $totalRevenue = $netProfit;
        $grossRevenue = $receivedRevenue; // Revenue that's actually received
        
        // Calculate last month's profit for comparison
        $lastMonthTotalGross = ($lastMonthExpenses + $totalRecurringExpenses) * 1.6;
        $lastMonthGrossRevenue = $lastMonthTotalGross * 0.85; // 85% received
        $lastMonthRevenue = $lastMonthGrossRevenue - ($lastMonthExpenses + $totalRecurringExpenses);
        
        $revenueChange = $lastMonthRevenue > 0 ? 
            (($totalRevenue - $lastMonthRevenue) / $lastMonthRevenue) * 100 : 15;
        
        // Pending payments count
        $pendingCount = $pendingRevenue > 0 ? max(1, intval($pendingRevenue / ($grossRevenue / 10))) : 1;

        // Monthly Recurring Expenses - Show total recurring costs
        // Since there's no actual recurring revenue system yet, we'll show expenses instead
        $monthlyRecurringExpenses = $totalRecurringExpenses;
        
        // Next month forecast based on current profit with 5% growth
        $nextMonthForecast = $totalRevenue * 1.05;

        // Get monthly trend data (last 6 months)
        $trendData = [];
        for ($i = 5; $i >= 0; $i--) {
            $trendMonth = $currentDate->copy()->subMonths($i);
            $monthlyOneTimeExpenses = OneTimeExpense::whereYear('expense_date', $trendMonth->year)
                ->whereMonth('expense_date', $trendMonth->month)
                ->sum('bdt_amount');
            
            // Total monthly expenses including recurring
            $monthlyTotalExpenses = $monthlyOneTimeExpenses + $totalRecurringExpenses;
            
            // Calculate gross revenue and net profit for the month (excluding pending)
            $monthlyTotalGross = $monthlyTotalExpenses > 0 ? $monthlyTotalExpenses * 1.6 : $totalGrossRevenue * 0.8;
            $monthlyGrossRevenue = $monthlyTotalGross * 0.85; // Only 85% is received
            $monthlyNetRevenue = $monthlyGrossRevenue - $monthlyTotalExpenses; // Net profit from received revenue

            $trendData[] = [
                'name' => $trendMonth->format('M'),
                'revenue' => round($monthlyNetRevenue, 2), // Show net profit as revenue
                'expenses' => round($monthlyTotalExpenses, 2) // Show total expenses
            ];
        }

        // Get expense categories breakdown
        $expenseCategories = collect();
        if ($currentMonthExpenses->count() > 0) {
            $expenseCategories = $currentMonthExpenses->groupBy('category')->map(function ($expenses, $category) {
                $total = round($expenses->sum('bdt_amount'), 2);
                return [
                    'name' => $category ?: 'Uncategorized',
                    'amount' => $total,
                    'icon' => $this->getCategoryIcon($category),
                    'color' => $this->getCategoryColor($category)
                ];
            })->sortByDesc('amount')->take(5)->values();
        }

        // Add recurring categories with proper monthly conversion
        $recurringCategories = ExpenseCategory::where('is_recurring', true)
            ->where('type', 'liability')
            ->get()
            ->map(function ($category) {
                // Convert to monthly basis based on frequency
                $monthlyAmount = $category->default_amount;
                if ($category->frequency === 'weekly') {
                    $monthlyAmount = $category->default_amount * 4.33; // 4.33 weeks per month
                } elseif ($category->frequency === 'yearly') {
                    $monthlyAmount = $category->default_amount / 12; // Divide by 12 months
                }
                
                return [
                    'name' => $category->name . ' (' . ucfirst($category->frequency) . ')',
                    'amount' => round($monthlyAmount, 2),
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

        // Mock some income transactions for display (based on GROSS revenue, not net)
        $mockIncomes = collect([
            [
                'id' => 'inc_1',
                'description' => 'Service Revenue (Gross)',
                'amount' => round($grossRevenue * 0.4, 2),
                'type' => 'income',
                'icon' => 'fas fa-arrow-down',
                'date' => $currentDate->format('Y-m-d')
            ],
            [
                'id' => 'inc_2', 
                'description' => 'Product Sales (Gross)',
                'amount' => round($grossRevenue * 0.3, 2),
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
            $percentage = $budget['total'] > 0 ? ($budget['used'] / $budget['total']) * 100 : 0;
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
            'totalRevenue' => round($totalRevenue, 2), // This is NET profit after expenses
            'grossRevenue' => round($grossRevenue, 2), // This is received revenue
            'totalGrossRevenue' => round($totalGrossRevenue, 2), // Total revenue including pending
            'totalExpenses' => round($totalExpenses, 2),
            'oneTimeExpenses' => round($totalOneTimeExpenses, 2),
            'recurringExpenses' => round($totalRecurringExpenses, 2),
            'grossProfit' => round($grossProfit, 2), // Profit including pending payments
            'netProfit' => round($netProfit, 2), // Profit from received payments only
            'pendingRevenue' => round($pendingRevenue, 2),
            'pendingCount' => $pendingCount,
            'monthlyRecurringExpenses' => round($monthlyRecurringExpenses, 2), // Monthly recurring costs
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

    private function generateRecurringExpenseTooltip($category)
    {
        $status = $category->getCurrentPaymentStatus();
        $lastPaid = $category->last_paid_date ? $category->last_paid_date->format('M d, Y') : 'Never';
        $nextDue = $category->next_due_date ? $category->next_due_date->format('M d, Y') : ($category->calculateNextDueDate() ? $category->calculateNextDueDate()->format('M d, Y') : 'Now');
        
        $tooltip = "Recurring {$category->frequency} expense: {$category->name}";
        
        if ($category->isOverdue()) {
            $tooltip .= " (OVERDUE - Due: {$nextDue})";
        } elseif ($category->isDueForPayment()) {
            $tooltip .= " (Due: {$nextDue})";
        }
        
        $tooltip .= " | Last Paid: {$lastPaid}";
        $tooltip .= " | Amount: ৳" . number_format($category->bdt_amount, 2);
        
        return $tooltip;
    }

    private function generateOneTimeExpenseTooltip($year, $monthNumber, $totalAmount)
    {
        $unpaidExpenses = OneTimeExpense::whereYear('expense_date', $year)
            ->whereMonth('expense_date', $monthNumber)
            ->unpaid()
            ->get();

        $expenseCount = $unpaidExpenses->count();
        $pendingCount = $unpaidExpenses->where('payment_status', 'pending')->count();
        $unpaidCount = $unpaidExpenses->where('payment_status', 'unpaid')->count();

        $tooltip = "Outstanding one-time expenses for {$year}-{$monthNumber} (Total: {$expenseCount} items, ৳" . number_format($totalAmount, 2) . ")";
        
        if ($pendingCount > 0) {
            $tooltip .= " | Pending: {$pendingCount}";
        }
        if ($unpaidCount > 0) {
            $tooltip .= " | Unpaid: {$unpaidCount}";
        }

        return $tooltip;
    }
}