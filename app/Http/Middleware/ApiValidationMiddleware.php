<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Http\Resources\ApiResponse;

class ApiValidationMiddleware
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next)
    {
        // Only apply to API routes
        if (!$request->is('api/*')) {
            return $next($request);
        }

        // Enforce JSON for POST, PUT, PATCH requests
        if (in_array($request->method(), ['POST', 'PUT', 'PATCH']) && !$request->isJson()) {
            return ApiResponse::error(
                'API requests must use JSON format',
                400,
                'INVALID_CONTENT_TYPE'
            );
        }

        // Google OAuth callback không có Accept: application/json (browser redirect từ Google)
        if ($request->is('api/*/auth/google*')) {
            return $next($request);
        }

        // Validate required headers for API requests
        $requiredHeaders = ['Accept'];
        foreach ($requiredHeaders as $header) {
            if (!$request->hasHeader($header)) {
                return ApiResponse::error(
                    "Missing required header: {$header}",
                    400,
                    'MISSING_HEADER'
                );
            }

            // Validate Accept header for JSON (*/* is also acceptable)
            $accept = $request->header('Accept');
            if ($header === 'Accept' && !str_contains($accept, 'application/json') && !str_contains($accept, '*/*')) {
                return ApiResponse::error(
                    'Accept header must include application/json',
                    400,
                    'INVALID_ACCEPT_HEADER'
                );
            }
        }

        // Validate API version if provided
        if ($request->hasHeader('X-API-Version')) {
            $supportedVersions = ['v1']; // Add more versions as needed
            $requestedVersion = $request->header('X-API-Version');
            
            if (!in_array($requestedVersion, $supportedVersions)) {
                return ApiResponse::error(
                    "Unsupported API version: {$requestedVersion}",
                    400,
                    'UNSUPPORTED_API_VERSION'
                );
            }
        }

        return $next($request);
    }
}
