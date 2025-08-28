<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Client;
use App\Models\Revenue;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ClientController extends Controller
{
    public function index()
    {
        $clients = Client::with(['creator', 'updater'])
            ->withCount('revenues')
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json([
            'success' => true,
            'clients' => $clients
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'nullable|email|max:255',
            'phone' => 'nullable|string|max:20',
            'payment_terms' => 'required|in:net_15,net_30,net_60,immediate',
            'notes' => 'nullable|string'
        ]);

        try {
            $client = Client::create([
                'name' => $request->name,
                'email' => $request->email,
                'phone' => $request->phone,
                'payment_terms' => $request->payment_terms,
                'notes' => $request->notes,
                'created_by' => Auth::guard('admin')->id(),
                'updated_by' => Auth::guard('admin')->id()
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Client created successfully',
                'client' => $client
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to create client: ' . $e->getMessage()
            ], 500);
        }
    }

    public function show($id)
    {
        try {
            $client = Client::with(['creator', 'updater', 'revenues'])
                ->findOrFail($id);

            return response()->json([
                'success' => true,
                'client' => $client
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Client not found'
            ], 404);
        }
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'nullable|email|max:255',
            'phone' => 'nullable|string|max:20',
            'payment_terms' => 'required|in:net_15,net_30,net_60,immediate',
            'notes' => 'nullable|string'
        ]);

        try {
            $client = Client::findOrFail($id);
            
            $client->update([
                'name' => $request->name,
                'email' => $request->email,
                'phone' => $request->phone,
                'payment_terms' => $request->payment_terms,
                'notes' => $request->notes,
                'updated_by' => Auth::guard('admin')->id()
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Client updated successfully',
                'client' => $client
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update client: ' . $e->getMessage()
            ], 500);
        }
    }

    public function destroy($id)
    {
        try {
            $client = Client::findOrFail($id);
            
            // Check if client has any revenues
            if ($client->revenues()->count() > 0) {
                return response()->json([
                    'success' => false,
                    'message' => 'Cannot delete client with existing revenue records. Please remove all revenue records first.'
                ], 400);
            }

            $client->delete();

            return response()->json([
                'success' => true,
                'message' => 'Client deleted successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete client: ' . $e->getMessage()
            ], 500);
        }
    }

    public function toggleStatus($id)
    {
        try {
            $client = Client::findOrFail($id);
            $client->update([
                'is_active' => !$client->is_active,
                'updated_by' => Auth::guard('admin')->id()
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Client status updated successfully',
                'client' => $client
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update client status: ' . $e->getMessage()
            ], 500);
        }
    }

    // Revenue Management Methods
    public function getRevenues(Request $request)
    {
        $period = $request->get('period', 'current');
        $now = now();
        
        $query = Revenue::with('client');
        
        // Apply period filtering
        switch ($period) {
            case 'current':
                $query->whereMonth('created_at', $now->month)
                      ->whereYear('created_at', $now->year);
                break;
            case 'quarter':
                $currentQuarter = ceil($now->month / 3);
                $startMonth = ($currentQuarter - 1) * 3 + 1;
                $endMonth = $currentQuarter * 3;
                $query->whereBetween('created_at', [
                    $now->copy()->month($startMonth)->startOfMonth(),
                    $now->copy()->month($endMonth)->endOfMonth()
                ]);
                break;
            case 'year':
                $query->whereYear('created_at', $now->year);
                break;
            case 'all':
                // No additional filtering for 'all'
                break;
            default:
                // Default to current month
                $query->whereMonth('created_at', $now->month)
                      ->whereYear('created_at', $now->year);
                break;
        }
        
        $revenues = $query->orderBy('created_at', 'desc')->get();

        return response()->json([
            'success' => true,
            'revenues' => $revenues,
            'period' => $period
        ]);
    }

    public function storeRevenue(Request $request)
    {
        $request->validate([
            'client_id' => 'required|exists:clients,id',
            'service_type' => 'required|in:webflow_template,shopify_app,web_development,consulting,maintenance,envato,other',
            'amount' => 'required|numeric|min:0',
            'currency' => 'required|in:BDT,USD',
            'status' => 'required|in:paid,pending,overdue',
            'expected_date' => 'nullable|date',
            'invoice_date' => 'nullable|date',
            'description' => 'nullable|string|max:255',
            'notes' => 'nullable|string'
        ]);

        try {
            $revenue = Revenue::create([
                'client_id' => $request->client_id,
                'service_type' => $request->service_type,
                'amount' => $request->amount,
                'currency' => $request->currency,
                // Let the model's boot method handle bdt_amount calculation
                'status' => $request->status,
                'expected_date' => $request->expected_date,
                'invoice_date' => $request->invoice_date,
                'paid_date' => $request->status === 'paid' ? now() : null,
                'description' => $request->description,
                'notes' => $request->notes,
                'created_by' => Auth::guard('admin')->id(),
                'updated_by' => Auth::guard('admin')->id()
            ]);

            // Load the client relationship
            $revenue->load('client');

            return response()->json([
                'success' => true,
                'message' => 'Revenue logged successfully',
                'revenue' => $revenue
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to log revenue: ' . $e->getMessage()
            ], 500);
        }
    }

    public function updateRevenue(Request $request, $id)
    {
        $request->validate([
            'client_id' => 'required|exists:clients,id',
            'service_type' => 'required|in:webflow_template,shopify_app,web_development,consulting,maintenance,envato,other',
            'amount' => 'required|numeric|min:0',
            'currency' => 'required|in:BDT,USD',
            'status' => 'required|in:paid,pending,overdue',
            'expected_date' => 'nullable|date',
            'invoice_date' => 'nullable|date',
            'description' => 'nullable|string|max:255',
            'notes' => 'nullable|string'
        ]);

        try {
            $revenue = Revenue::findOrFail($id);

            $revenue->update([
                'client_id' => $request->client_id,
                'service_type' => $request->service_type,
                'amount' => $request->amount,
                'currency' => $request->currency,
                // Let the model's boot method handle bdt_amount calculation
                'status' => $request->status,
                'expected_date' => $request->expected_date,
                'invoice_date' => $request->invoice_date,
                'paid_date' => $request->status === 'paid' ? ($revenue->paid_date ?: now()) : null,
                'description' => $request->description,
                'notes' => $request->notes,
                'updated_by' => Auth::guard('admin')->id()
            ]);

            // Load the client relationship
            $revenue->load('client');

            return response()->json([
                'success' => true,
                'message' => 'Revenue updated successfully',
                'revenue' => $revenue
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update revenue: ' . $e->getMessage()
            ], 500);
        }
    }

    public function deleteRevenue($id)
    {
        try {
            $revenue = Revenue::findOrFail($id);
            $revenue->delete();

            return response()->json([
                'success' => true,
                'message' => 'Revenue deleted successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete revenue: ' . $e->getMessage()
            ], 500);
        }
    }
}
