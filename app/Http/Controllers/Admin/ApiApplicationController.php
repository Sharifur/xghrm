<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ApiApplication;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ApiApplicationController extends Controller
{
    public function index()
    {
        $apps = ApiApplication::orderBy('created_at', 'desc')->get()->map(function ($app) {
            return [
                'id' => $app->id,
                'name' => $app->name,
                'description' => $app->description,
                'key_prefix' => substr($app->secret_key, 0, 8) . '...',
                'active' => $app->active,
                'last_used_at' => $app->last_used_at?->toISOString(),
                'created_at' => $app->created_at->toISOString(),
            ];
        });

        return Inertia::render('Backend/ApiApplications/Index', [
            'apps' => $apps,
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:191',
            'description' => 'nullable|string|max:500',
        ]);

        $secret = bin2hex(random_bytes(32));

        $app = ApiApplication::create([
            'name' => $request->name,
            'description' => $request->description,
            'secret_key' => $secret,
            'active' => true,
        ]);

        return back()->with('flashMsg', [
            'msg' => 'Application created.',
            'type' => 'success',
            'secret' => $secret,
            'app_id' => $app->id,
        ]);
    }

    public function toggleActive(Request $request, $id)
    {
        $app = ApiApplication::findOrFail($id);
        $app->update(['active' => !$app->active]);

        return back()->with('flashMsg', [
            'msg' => 'Status updated.',
            'type' => 'success',
        ]);
    }

    public function regenerate($id)
    {
        $app = ApiApplication::findOrFail($id);
        $secret = bin2hex(random_bytes(32));
        $app->update(['secret_key' => $secret]);

        return back()->with('flashMsg', [
            'msg' => 'Secret regenerated.',
            'type' => 'success',
            'secret' => $secret,
            'app_id' => $app->id,
        ]);
    }

    public function destroy($id)
    {
        ApiApplication::findOrFail($id)->delete();

        return back()->with('flashMsg', [
            'msg' => 'Application deleted.',
            'type' => 'success',
        ]);
    }
}
