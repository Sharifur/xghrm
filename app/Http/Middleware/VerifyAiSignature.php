<?php

namespace App\Http\Middleware;

use App\Models\ApiApplication;
use Closure;
use Illuminate\Http\Request;

class VerifyAiSignature
{
    public function handle(Request $request, Closure $next)
    {
        $signature = $request->header('X-Signature');

        if (!$signature) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $app = ApiApplication::where('active', true)->get()->first(function ($app) use ($signature) {
            return hash_equals($app->secret_key, $signature);
        });

        if (!$app) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $app->updateQuietly(['last_used_at' => now()]);

        return $next($request);
    }
}
