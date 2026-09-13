<?php

namespace App\Http\Resources;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Enums\ErrorCode;
use App\Exceptions\BaseException;
use App\Exceptions\RateLimitException;
use App\Services\Logging\LoggerFactory;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Throwable;
use Illuminate\Support\Facades\Log;

class ApiResponse
{
    /** @var array Keys to redact when logging (sensitive data) */
    private static array $sensitiveKeys = [
        'password', 'password_confirmation', 'token', 'secret', 'authorization',
        'cookie', 'api_key', 'access_token', 'refresh_token',
    ];

    /**
     * Create a successful response
     */
    public static function success(
        mixed $data = null,
        string $message = 'Success',
        int $statusCode = 200,
        array $meta = []
    ): JsonResponse {
        $response = [
            'success' => true,
            'message' => $message,
            'data' => $data,
        ];
        
        if (!empty($meta)) {
            $response['meta'] = $meta;
        }
        
        // Add timestamp if configured
        if (self::getConfig('core.api.response.include_timestamp', true)) {
            $response['timestamp'] = now()->toISOString();
        }
        
        return response()->json($response, $statusCode);
    }
    
    /**
     * Create an error response.
     * Logs once with sanitized context; uses configured log channel (LoggerFactory).
     */
    public static function error(
        string $message = 'An error occurred',
        int $statusCode = 500,
        ?string $errorCode = null,
        mixed $errors = null,
        array $context = []
    ): JsonResponse {
        $channel = self::getConfig('core.logging.error_channel', 'single');
        Log::channel($channel)->error($message, [
            'error_code' => $errorCode,
            'status_code' => $statusCode,
            'url' => request()->fullUrl(),
            'method' => request()->method(),
            'request_id' => request()->header('X-Request-ID'),
            'context' => self::sanitizeForLog($context),
            'errors' => self::sanitizeForLog(is_array($errors) ? $errors : ['value' => $errors]),
        ]);
        return self::buildErrorResponse($message, $statusCode, $errorCode, $errors, $context);
    }
    
    /**
     * Create response from exception.
     * Logs once via LoggerFactory (structured, sanitized; trace only in local/testing).
     */
    public static function fromException(Throwable $exception): JsonResponse
    {
        $logger = LoggerFactory::createDefault();
        $context = [
            'url' => request()->fullUrl(),
            'method' => request()->method(),
            'request_data' => self::sanitizeForLog(request()->all()),
        ];
        $logger->logException($exception, $context);

        if ($exception instanceof BaseException) {
            $response = self::buildErrorResponse(
                $exception->getMessage(),
                $exception->getErrorCode()->getHttpStatus(),
                $exception->getErrorCode()->value,
                $exception instanceof \App\Exceptions\ValidationException ? $exception->getErrors() : null,
                $exception->getContext()
            );

            if ($exception instanceof RateLimitException) {
                $ctx = $exception->getContext();
                $retryAfter = (int)($ctx['retry_after_seconds'] ?? 0);
                $max = (int)($ctx['max_attempts'] ?? 0);
                $remaining = (int)($ctx['remaining_attempts'] ?? 0);
                $response->headers->set('Retry-After', $retryAfter);
                if ($max > 0) {
                    $response->headers->set('X-RateLimit-Limit', $max);
                }
                $response->headers->set('X-RateLimit-Remaining', $remaining);
                $response->headers->set('X-RateLimit-Reset', now()->addMinutes(60)->timestamp);
            }

            return $response;
        }

        $statusCode = $exception instanceof HttpException ? $exception->getStatusCode() : 500;

        return self::buildErrorResponse($exception->getMessage(), $statusCode, null, null, []);
    }
    
    /**
     * Create paginated response (following REST API best practices)
     * 
     * @param \Illuminate\Pagination\LengthAwarePaginator|mixed $collection
     * @param string $message
     * @param \Illuminate\Http\Request|null $request Optional request object for generating pagination links
     * @param array $meta Additional meta data
     * @return JsonResponse
     */
    public static function paginated(
        $collection,
        string $message = 'Data retrieved successfully',
        ?\Illuminate\Http\Request $request = null,
        array $meta = []
    ): JsonResponse {
        if ($collection instanceof \Illuminate\Pagination\LengthAwarePaginator) {
            // Get items from paginator
            $items = $collection->items();
            
            // Resolve JsonResource items to array if needed
            if (!empty($items) && $items[0] instanceof JsonResource) {
                $currentRequest = $request ?: request();
                $items = collect($items)->map(function ($item) use ($currentRequest) {
                    return $item->resolve($currentRequest);
                })->all();
            }
            
            // Format meta data (following REST API best practices)
            $paginationMeta = [
                'page' => $collection->currentPage(),
                'per_page' => $collection->perPage(),
                'total' => $collection->total(),
                'total_pages' => $collection->lastPage(),
            ];
            
            // Merge with additional meta data
            $responseMeta = array_merge($paginationMeta, $meta);
            
            // Build response structure
            $responseData = [
                'success' => true,
                'message' => $message,
                'data' => $items,
                'meta' => $responseMeta,
            ];
            
            // Add timestamp if configured
            if (self::getConfig('core.api.response.include_timestamp', true)) {
                $responseData['timestamp'] = now()->toISOString();
            }
            
            // Add pagination links if request is provided (following REST API best practices)
            if ($request) {
                // Build query string with all current query parameters
                $queryParams = $request->query();
                
                // Generate pagination links
                $currentPage = $collection->currentPage();
                $lastPage = $collection->lastPage();
                
                $responseData['links'] = [
                    'self' => self::buildPaginationUrl($request, $queryParams, $currentPage),
                    'first' => self::buildPaginationUrl($request, $queryParams, 1),
                    'prev' => $currentPage > 1 
                        ? self::buildPaginationUrl($request, $queryParams, $currentPage - 1) 
                        : null,
                    'next' => $currentPage < $lastPage 
                        ? self::buildPaginationUrl($request, $queryParams, $currentPage + 1) 
                        : null,
                    'last' => self::buildPaginationUrl($request, $queryParams, $lastPage),
                ];
            }
            
            return response()->json($responseData, 200);
        }
        
        // Fallback for non-paginated collections
        return self::success($collection, $message, 200, $meta);
    }
    
    /**
     * Build pagination URL with query parameters
     */
    private static function buildPaginationUrl(\Illuminate\Http\Request $request, array $queryParams, int $page): string
    {
        $queryParams['page'] = $page;
        return $request->url() . '?' . http_build_query($queryParams);
    }
    
    /**
     * Create cursor paginated response (for cursor-based pagination)
     * 
     * @param \Illuminate\Pagination\CursorPaginator|mixed $collection
     * @param string $message
     * @param \Illuminate\Http\Request|null $request Optional request object for generating links
     * @param array $meta Additional meta data
     * @return JsonResponse
     */
    public static function cursorPaginated(
        $collection,
        string $message = 'Data retrieved successfully',
        ?\Illuminate\Http\Request $request = null,
        array $meta = []
    ): JsonResponse {
        // Check if collection is CursorPaginator instance
        if ($collection instanceof \Illuminate\Pagination\CursorPaginator) {
            // Get items from paginator
            $items = $collection->items();
            
            // Resolve JsonResource items to array if needed
            if (!empty($items) && $items[0] instanceof JsonResource) {
                $currentRequest = $request ?: request();
                $items = collect($items)->map(function ($item) use ($currentRequest) {
                    return $item->resolve($currentRequest);
                })->all();
            }
            
            // Extract cursor strings - Always parse from URL to ensure string type
            $nextCursor = null;
            $previousCursor = null;
            
            // Extract cursor from nextPageUrl() query parameter
            if ($collection->nextPageUrl()) {
                $nextUrl = parse_url($collection->nextPageUrl());
                if (isset($nextUrl['query'])) {
                    parse_str($nextUrl['query'], $queryParams);
                    $nextCursor = $queryParams['cursor'] ?? null;
                }
            }
            
            // Extract cursor from previousPageUrl() query parameter
            if ($collection->previousPageUrl()) {
                $prevUrl = parse_url($collection->previousPageUrl());
                if (isset($prevUrl['query'])) {
                    parse_str($prevUrl['query'], $queryParams);
                    $previousCursor = $queryParams['cursor'] ?? null;
                }
            }
            
            // Format meta data for cursor pagination
            $paginationMeta = [
                'per_page' => $collection->perPage(),
                'next_cursor' => $nextCursor,
                'prev_cursor' => $previousCursor,
            ];
            
            // Merge with additional meta data
            $responseMeta = array_merge($paginationMeta, $meta);
            
            // Build response structure
            $responseData = [
                'success' => true,
                'message' => $message,
                'data' => $items,
                'meta' => $responseMeta,
            ];
            
            // Add timestamp if configured
            if (self::getConfig('core.api.response.include_timestamp', true)) {
                $responseData['timestamp'] = now()->toISOString();
            }
            
            // Add pagination links (extract from CursorPaginator URLs)
            $responseData['links'] = [
                'next' => $collection->nextPageUrl(),
                'prev' => $collection->previousPageUrl(),
            ];
            
            return response()->json($responseData, 200);
        }
        
        // Fallback for non-CursorPaginator instances
        return self::success($collection, $message, 200, $meta);
    }
    
    /**
     * Create created response
     */
    public static function created(
        mixed $data = null,
        string $message = 'Resource created successfully'
    ): JsonResponse {
        return self::success($data, $message, 201);
    }
    
    /**
     * Create updated response
     */
    public static function updated(
        mixed $data = null,
        string $message = 'Resource updated successfully'
    ): JsonResponse {
        return self::success($data, $message, 200);
    }
    
    /**
     * Create deleted response
     */
    public static function deleted(string $message = 'Resource deleted successfully'): JsonResponse
    {
        return self::success(null, $message, 200);
    }
    
    /**
     * Create not found response
     */
    public static function notFound(string $message = 'Resource not found'): JsonResponse
    {
        return self::error($message, 404, ErrorCode::RESOURCE_NOT_FOUND->value);
    }
    
    /**
     * Create unauthorized response
     */
    public static function unauthorized(string $message = 'Unauthorized'): JsonResponse
    {
        return self::error($message, 401, ErrorCode::AUTHENTICATION_FAILED->value);
    }
    
    /**
     * Create forbidden response
     */
    public static function forbidden(string $message = 'Forbidden'): JsonResponse
    {
        return self::error($message, 403, ErrorCode::ACCESS_DENIED->value);
    }
    
    /**
     * Create validation error response
     */
    public static function validationError(
        array $errors,
        string $message = 'Validation failed'
    ): JsonResponse {
        return self::error($message, 422, ErrorCode::VALIDATION_ERROR->value, $errors);
    }
    
    /**
     * Create conflict response
     */
    public static function conflict(string $message = 'Resource conflict'): JsonResponse
    {
        return self::error($message, 409, ErrorCode::RESOURCE_CONFLICT->value);
    }
    
    /**
     * Create too many requests response
     */
    public static function tooManyRequests(string $message = 'Too many requests'): JsonResponse
    {
        return self::error($message, 429, ErrorCode::RATE_LIMIT_EXCEEDED->value);
    }

    /**
     * Create RFC 7807 compliant error response
     * NEW METHOD - builds on existing fromException()
     */
    public static function problemDetails(
        ErrorCode $errorCode,
        string $detail,
        ?string $instance = null,
        array $extensions = []
    ): JsonResponse {
        $response = [
            'type' => self::buildTypeUri($errorCode),
            'title' => $errorCode->getMessage(),
            'status' => $errorCode->getHttpStatus(), 
            'detail' => $detail,
            'instance' => $instance ?? request()->getRequestUri(),
        ];
        
        // Merge extensions for additional context
        if (!empty($extensions)) {
            $response = array_merge($response, $extensions);
        }
        
        // Maintain existing timestamp logic
        if (self::getConfig('core.api.response.include_timestamp', true)) {
            $response['timestamp'] = now()->toISOString();
        }
        
        return response()->json($response, $errorCode->getHttpStatus());
    }
    
    /**
     * Build RFC 7807 type URI from ErrorCode
     */
    private static function buildTypeUri(ErrorCode $errorCode): string
    {
        $baseUri = self::getConfig('core.api.response.problem_details.base_uri') 
            ?? config('app.url', 'https://api.example.com');
        
        // Convert error code name to kebab-case for human-readable URIs
        $errorName = strtolower(str_replace('_', '-', $errorCode->name));
        return "{$baseUri}/problems/{$errorName}";
    }
    
    /**
     * Check if context should be included based on environment
     */
    private static function shouldIncludeContext(): bool
    {
        $currentEnv = app()->bound('env') ? app('env') : 
            (app()->hasBeenBootstrapped() ? app()->environment() : 'testing');
        return in_array($currentEnv, self::getConfig('core.api.exceptions.include_context_in_environments', ['local', 'testing']));
    }

    /**
     * Build error JSON response (no logging — caller is responsible for logging).
     */
    private static function buildErrorResponse(
        string $message,
        int $statusCode,
        ?string $errorCode,
        mixed $errors,
        array $context
    ): JsonResponse {
        $response = [
            'success' => false,
            'message' => $message,
        ];

        if ($errorCode) {
            $response['error_code'] = $errorCode;
        }

        if ($errors !== null) {
            $response['errors'] = $errors;
        }

        $currentEnv = app()->bound('env') ? app('env') : (app()->hasBeenBootstrapped() ? app()->environment() : 'testing');
        if (!empty($context) && in_array($currentEnv, self::getConfig('core.api.exceptions.include_context_in_environments', ['local', 'testing']))) {
            $response['errors'] = $context;
        }

        if (self::getConfig('core.api.response.include_timestamp', true)) {
            $response['timestamp'] = now()->toISOString();
        }

        return response()->json($response, $statusCode);
    }

    /**
     * Redact sensitive keys from array before logging (passwords, tokens, etc.).
     */
    private static function sanitizeForLog(mixed $data): mixed
    {
        if (!is_array($data)) {
            return $data;
        }
        $out = [];
        foreach ($data as $key => $value) {
            $keyLower = is_string($key) ? strtolower($key) : '';
            $redact = false;
            foreach (self::$sensitiveKeys as $sensitive) {
                if (str_contains($keyLower, $sensitive)) {
                    $redact = true;
                    break;
                }
            }
            $out[$key] = $redact ? '[REDACTED]' : (is_array($value) ? self::sanitizeForLog($value) : $value);
        }
        return $out;
    }

    /**
     * Safe config helper for testing
     */
    protected static function getConfig(string $key, mixed $default = null): mixed
    {
        try {
            return config($key, $default);
        } catch (\Throwable $e) {
            return $default;
        }
    }
}
