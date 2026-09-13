<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ApiResponseMiddleware
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next)
    {
        // Generate unique request ID for tracking
        $requestId = Str::uuid()->toString();
        $request->headers->set('X-Request-ID', $requestId);

        $response = $next($request);

        // Add common API headers for JSON responses
        if ($response instanceof JsonResponse) {
            $response->headers->set('X-Request-ID', $requestId);
            $response->headers->set('X-API-Version', config('app.api_version', 'v1'));
            $response->headers->set('Content-Type', 'application/json');
            
            // Add security headers
            $response->headers->set('X-Content-Type-Options', 'nosniff');
            $response->headers->set('X-Frame-Options', 'DENY');
            $response->headers->set('X-XSS-Protection', '1; mode=block');
        }

        return $response;
    }
}
