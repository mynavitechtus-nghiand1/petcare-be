<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Resources\ApiResponse;
use App\Exceptions\BaseException;
use Symfony\Component\HttpFoundation\Response as SymfonyResponse;
use Illuminate\Validation\ValidationException as LaravelValidationException;
use App\Exceptions\ValidationException;
use Illuminate\Auth\AuthenticationException;
use Symfony\Component\HttpKernel\Exception\HttpException;
use App\Enums\ErrorCode;
use Throwable;

class ApiExceptionHandler
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): SymfonyResponse
    {
        try {
            return $next($request);
        } catch (Throwable $exception) {
            // Only handle JSON/API requests
            if ($this->shouldHandleAsApi($request)) {
                return $this->handleApiException($exception, $request);
            }

            // Re-throw for non-API requests
            throw $exception;
        }
    }
    
    /**
     * Determine if the request should be handled as an API request
     */
    protected function shouldHandleAsApi(Request $request): bool
    {
        return $request->expectsJson() ||
               $request->is('api/*') ||
               $request->header('Accept') === 'application/json' ||
               str_contains($request->header('Content-Type', ''), 'application/json');
    }
    
    /**
     * Handle API exceptions
     */
    protected function handleApiException(Throwable $exception, Request $request): SymfonyResponse
    {
        // Log the exception
        $this->logException($exception, $request);

        // Laravel validation exceptions
        if ($exception instanceof LaravelValidationException or $exception instanceof ValidationException) {
            return ApiResponse::error(
                'The provided data failed validation',
                422,
                ErrorCode::VALIDATION_ERROR->value,
                $exception->getErrors()
            );
        } else if ($exception instanceof AuthenticationException) {
            return ApiResponse::error(
                'Unauthorized',
                401,
                ErrorCode::UNAUTHORIZED->value
            );
        }

        // HTTP exceptions (404, 403, ...)
        if ($exception instanceof HttpException) {
            $status = $exception->getStatusCode();
            $message = $exception->getMessage() ?: match ($status) {
                401 => 'Unauthorized',
                403 => 'Forbidden',
                404 => 'Not Found',
                405 => 'Method Not Allowed',
                429 => 'Too Many Requests',
                500 => 'Internal Server Error',
                503 => 'Service Unavailable',
                default => 'HTTP Error',
            };

            $code = match ($status) {
                401 => ErrorCode::UNAUTHORIZED->value,
                403 => ErrorCode::ACCESS_DENIED->value,
                404 => ErrorCode::RESOURCE_NOT_FOUND->value,
                405 => 'METHOD_NOT_ALLOWED',
                429 => ErrorCode::RATE_LIMIT_EXCEEDED->value,
                500 => ErrorCode::SYSTEM_ERROR->value,
                503 => ErrorCode::SYSTEM_UNAVAILABLE->value,
                default => 'HTTP_ERROR',
            };

            return ApiResponse::error($message, $status, $code);
        }

        // Return formatted API response
        return ApiResponse::fromException($exception);
    }
    
    /**
     * Log the exception with context
     */
    protected function logException(Throwable $exception, Request $request): void
    {
        $context = [
            'url' => $request->fullUrl(),
            'method' => $request->method(),
            'user_id' => $request->user()?->id,
            'ip' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'request_id' => $request->header('X-Request-ID'),
        ];
        
        // Add request data for non-GET requests (be careful with sensitive data)
        if (!$request->isMethod('GET')) {
            $requestData = $request->all();
            
            // Remove sensitive fields
            $sensitiveFields = ['password', 'password_confirmation', 'token', 'api_key', 'secret'];
            foreach ($sensitiveFields as $field) {
                if (isset($requestData[$field])) {
                    $requestData[$field] = '[REDACTED]';
                }
            }
            
            $context['request_data'] = $requestData;
        }
        
        // Log based on exception type and severity
        if ($exception instanceof BaseException) {
            $severity = $exception->getErrorCode()->getSeverity();
            
            match($severity) {
                'critical', 'high' => logger()->error($exception->getMessage(), array_merge($context, [
                    'exception' => $exception,
                    'error_code' => $exception->getErrorCode()->value,
                    'exception_context' => $exception->getContext(),
                ])),
                'medium' => logger()->warning($exception->getMessage(), array_merge($context, [
                    'error_code' => $exception->getErrorCode()->value,
                    'exception_context' => $exception->getContext(),
                ])),
                'low' => logger()->info($exception->getMessage(), array_merge($context, [
                    'error_code' => $exception->getErrorCode()->value,
                ])),
                default => logger()->error($exception->getMessage(), array_merge($context, [
                    'exception' => $exception,
                ]))
            };
        } else {
            // Log other exceptions as errors
            logger()->error($exception->getMessage(), array_merge($context, [
                'exception' => $exception,
            ]));
        }
    }
}
