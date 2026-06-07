<?php

namespace App\Http\Middleware;

use App\Models\ApiClient;
use App\Models\ApiRequestLog;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ApiKeyMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        $apiKey = $request->header('X-VOKATIF-KEY');

        if (!$apiKey) {
            return response()->json([
                'success' => false,
                'message' => 'API Key tidak ditemukan. Gunakan header X-VOKATIF-KEY.',
            ], 401);
        }

        $apiClient = ApiClient::where('key_hash', hash('sha256', $apiKey))
            ->where('is_active', true)
            ->first();

        if (!$apiClient) {
            return response()->json([
                'success' => false,
                'message' => 'API Key tidak valid atau sudah tidak aktif.',
            ], 403);
        }

        $request->attributes->set('api_client', $apiClient);

        $response = $next($request);

        ApiRequestLog::create([
            'api_client_id' => $apiClient->id,
            'method' => $request->method(),
            'endpoint' => $request->path(),
            'ip_address' => $request->ip(),
            'status_code' => $response->getStatusCode(),
            'requested_at' => now(),
        ]);

        return $response;
    }
}
