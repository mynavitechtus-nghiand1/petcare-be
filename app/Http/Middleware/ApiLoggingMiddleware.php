<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ApiLoggingMiddleware
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next)
    {
        $startTime = microtime(true);

        // Log incoming request
        $this->logRequest($request);

        $response = $next($request);

        // Log outgoing response
        $this->logResponse($request, $response, $startTime);

        return $response;
    }

    /**
     * Log incoming API request.
     */
    protected function logRequest(Request $request): void
    {
        // Only log API requests
        if (!$request->is('api/*')) {
            return;
        }

        $data = [
            'type' => 'api_request',
            'method' => $request->getMethod(),
            'url' => $request->fullUrl(),
            'path' => $request->path(),
            'ip' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'user_id' => $request->user()?->id,
            'request_id' => $request->header('X-Request-ID'),
            'headers' => $this->sanitizeHeaders($request->headers->all()),
            'query_params' => $request->query(),
        ];

        // Log request body for non-GET requests
        if (!$request->isMethod('GET')) {
            $data['body'] = $this->sanitizeRequestBody($request->all());
        }

        Log::channel('api')->info('API Request', $data);
    }

    /**
     * Log API response.
     */
    protected function logResponse(Request $request, $response, float $startTime): void
    {
        // Only log API responses
        if (!$request->is('api/*')) {
            return;
        }

        $duration = round((microtime(true) - $startTime) * 1000, 2);

        $data = [
            'type' => 'api_response',
            'method' => $request->getMethod(),
            'url' => $request->fullUrl(),
            'path' => $request->path(),
            'status' => $response->getStatusCode(),
            'duration_ms' => $duration,
            'memory_mb' => round(memory_get_peak_usage(true) / 1024 / 1024, 2),
            'request_id' => $request->header('X-Request-ID'),
            'user_id' => $request->user()?->id,
        ];

        // Log error response details
        if ($response->getStatusCode() >= 400) {
            $responseContent = $response->getContent();
            if ($responseContent && $this->isJson($responseContent)) {
                $data['error_response'] = json_decode($responseContent, true);
            }
            
            Log::channel('api')->error('API Error Response', $data);
        } else {
            Log::channel('api')->info('API Response', $data);
        }

        // Log slow requests
        if ($duration > config('core.api.logging.slow_request_threshold', 1000)) {
            Log::channel('api')->warning('Slow API Request', array_merge($data, [
                'type' => 'slow_request',
                'threshold_ms' => config('core.api.logging.slow_request_threshold', 1000)
            ]));
        }
    }

    /**
     * Sanitize headers to remove sensitive information.
     */
    protected function sanitizeHeaders(array $headers): array
    {
        $sensitiveHeaders = [
            'authorization',
            'cookie', 
            'x-api-key',
            'x-auth-token',
            'x-access-token'
        ];
        
        foreach ($sensitiveHeaders as $header) {
            $headerKey = strtolower($header);
            foreach ($headers as $key => $value) {
                if (strtolower($key) === $headerKey) {
                    $headers[$key] = ['***REDACTED***'];
                    break;
                }
            }
        }

        return $headers;
    }

    /**
     * Sanitize request body to remove sensitive fields.
     */
    protected function sanitizeRequestBody(array $data): array
    {
        $sensitiveFields = [
            'password',
            'password_confirmation', 
            'current_password',
            'token',
            'access_token',
            'refresh_token',
            'api_key',
            'secret',
            'private_key',
            'credit_card',
            'ssn',
            'social_security_number'
        ];
        
        return $this->recursiveSanitize($data, $sensitiveFields);
    }

    /**
     * Recursively sanitize nested arrays.
     */
    protected function recursiveSanitize(array $data, array $sensitiveFields): array
    {
        foreach ($data as $key => $value) {
            if (is_array($value)) {
                $data[$key] = $this->recursiveSanitize($value, $sensitiveFields);
            } elseif (in_array(strtolower($key), array_map('strtolower', $sensitiveFields))) {
                $data[$key] = '***REDACTED***';
            }
        }

        return $data;
    }

    /**
     * Check if string is valid JSON.
     */
    protected function isJson(string $string): bool
    {
        json_decode($string);
        return json_last_error() === JSON_ERROR_NONE;
    }
}
