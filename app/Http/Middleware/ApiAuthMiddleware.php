<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Exceptions\BaseException;
use App\Enums\ErrorCode;

class ApiAuthMiddleware
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next)
    {
        // Check for Authorization header
        if (!$request->hasHeader('Authorization')) {
            throw new BaseException(
                ErrorCode::UNAUTHORIZED,
                'Authorization header is required'
            );
        }

        $authHeader = $request->header('Authorization');
        
        // Validate Authorization header format
        if (!str_starts_with($authHeader, 'Bearer ')) {
            throw new BaseException(
                ErrorCode::TOKEN_INVALID,
                'Authorization header must use Bearer token format',
                ['provided_format' => substr($authHeader, 0, 20) . '...']
            );
        }

        $token = $request->bearerToken();
        
        // Validate token presence
        if (!$token) {
            throw new BaseException(
                ErrorCode::UNAUTHORIZED,
                'Bearer token is required'
            );
        }

        // Validate token format (basic validation)
        if (!$this->isValidTokenFormat($token)) {
            throw new BaseException(
                ErrorCode::TOKEN_INVALID,
                'Invalid token format',
                [
                    'token_length' => strlen($token),
                    'expected_min_length' => 40
                ]
            );
        }

        // Additional token validation can be added here
        // For now, we'll do basic format validation
        // In real implementation, you'd validate against database/cache

        return $next($request);
    }

    /**
     * Validate token format
     */
    protected function isValidTokenFormat(string $token): bool
    {
        // Basic validation: token should be at least 40 characters
        // and contain only alphanumeric characters and specific symbols
        return strlen($token) >= 40 && 
               preg_match('/^[a-zA-Z0-9_\-\.]+$/', $token);
    }
}
