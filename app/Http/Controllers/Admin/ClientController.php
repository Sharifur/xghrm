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
    public function getRevenues()
    {
        $revenues = Revenue::with('client')
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json([
            'success' => true,
            'revenues' => $revenues
        ]);
    }

    public function storeRevenue(Request $request)
    {
        $request->validate([
            'client_id' => 'required|exists:clients,id',
            'service_type' => 'required|in:webflow_template,shopify_app,web_development,consulting,maintenance,other',
            'amount' => 'required|numeric|min:0',
            'currency' => 'required|in:BDT,USD',
            'status' => 'required|in:paid,pending,overdue',
            'expected_date' => 'nullable|date',
            'invoice_date' => 'nullable|date',
            'description' => 'nullable|string|max:255',
            'notes' => 'nullable|string'
        ]);

        try {
            // Convert amount to BDT for consistent storage
            $amount = $request->amount;
            $bdtAmount = $request->currency === 'USD' ? $amount * Revenue::USD_TO_BDT_RATE : $amount;

            $revenue = Revenue::create([
                'client_id' => $request->client_id,
                'service_type' => $request->service_type,
                'amount' => $amount,
                'currency' => $request->currency,
                'bdt_amount' => $bdtAmount,
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
            'service_type' => 'required|in:webflow_template,shopify_app,web_development,consulting,maintenance,other',
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

            // Convert amount to BDT for consistent storage
            $amount = $request->amount;
            $bdtAmount = $request->currency === 'USD' ? $amount * Revenue::USD_TO_BDT_RATE : $amount;
            
            $revenue->update([
                'client_id' => $request->client_id,
                'service_type' => $request->service_type,
                'amount' => $amount,
                'currency' => $request->currency,
                'bdt_amount' => $bdtAmount,
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
