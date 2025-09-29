<?php

// PASTIKAN NAMA FILE INI ADALAH AuthenticateApiKey.php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

// PASTIKAN NAMA CLASS DI BAWAH INI SESUAI DENGAN NAMA FILE
class AuthenticateApiKey
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $apiKey = $request->header('X-API-KEY');
        $validApiKey = config('app.device_api_key');

        if (!$apiKey || $apiKey !== $validApiKey) {
            Log::warning('Unauthorized API access attempt.', [
                'ip' => $request->ip(),
                'api_key_used' => $apiKey,
            ]);
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        return $next($request);
    }
}

