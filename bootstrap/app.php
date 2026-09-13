<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use App\Exceptions\RateLimitException;
use App\Http\Resources\ApiResponse;
use App\Exceptions\BaseException;
use App\Services\Logging\LoggerFactory;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException as LaravelValidationException;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Illuminate\Auth\AuthenticationException;
use App\Enums\ErrorCode;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
        apiPrefix: 'api',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        // Global middleware - applies to ALL routes
        $middleware->append(\App\Http\Middleware\LanguageMiddleware::class);
        
        // API Middleware Stack - Application-level middleware
        $middleware->group('api', [
            \App\Http\Middleware\LanguageMiddleware::class,
            \App\Http\Middleware\ApiValidationMiddleware::class,
            \App\Http\Middleware\ApiResponseMiddleware::class,
            \App\Http\Middleware\ApiLoggingMiddleware::class,
            // \Laravel\Sanctum\Http\Middleware\EnsureFrontendRequestsAreStateful::class, // Will add after Sanctum installation
            'throttle:api',
            \Illuminate\Routing\Middleware\SubstituteBindings::class,
        ]);

        // Register middleware aliases - Application-level middleware
        $middleware->alias([
            'auth' => \App\Http\Middleware\Authenticate::class, // Custom Authenticate middleware for API-only
            'api.auth' => \App\Http\Middleware\ApiAuthMiddleware::class,
            'api.rate' => \App\Http\Middleware\ApiRateLimitMiddleware::class,
            'api.exception' => \App\Http\Middleware\ApiExceptionHandler::class, // Foundation middleware
            'admin'   => \App\Http\Middleware\EnsureAdmin::class,
            'ability' => \Laravel\Sanctum\Http\Middleware\CheckAbilities::class,
            'abilities' => \Laravel\Sanctum\Http\Middleware\CheckForAnyAbility::class,
            'role' => \Spatie\Permission\Middleware\RoleMiddleware::class,
            'permission' => \Spatie\Permission\Middleware\PermissionMiddleware::class,
            'role_or_permission' => \Spatie\Permission\Middleware\RoleOrPermissionMiddleware::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        $logger = LoggerFactory::createDefault();

        $exceptions->report(function (Throwable $e) use ($logger) {
            if ($e instanceof BaseException) {
                $logger->logError(
                    $e->getErrorCode(),
                    $e->getMessage(),
                    [
                        'context' => $e->getContext(),
                        'request_data' => request()->all(),
                        'headers' => request()->headers->all(),
                        'url' => request()->fullUrl(),
                        'method' => request()->method(),
                    ]
                );
            } else {
                $logger->logException($e, [
                    'request_data' => request()->all(),
                    'headers' => request()->headers->all(),
                    'url' => request()->fullUrl(),
                    'method' => request()->method(),
                ]);
            }
        });

        // Centralized exception rendering for API & testing
        $exceptions->render(function (Throwable $e, Request $request) {
            if (!($request->expectsJson() || $request->is('api/*') || app()->environment('testing'))) {
                return null; // use default HTML rendering for non-API
            }

            // Our custom BaseException
            if ($e instanceof BaseException) {
                $response = ApiResponse::fromException($e);

                if ($e instanceof RateLimitException) {
                    $context = $e->getContext();
                    $retryAfter = (int)($context['retry_after_seconds'] ?? 0);
                    $max = (int)($context['max_attempts'] ?? 0);
                    $remaining = (int)($context['remaining_attempts'] ?? 0);
                    $response->headers->set('Retry-After', $retryAfter);
                    if ($max > 0) {
                        $response->headers->set('X-RateLimit-Limit', $max);
                    }
                    $response->headers->set('X-RateLimit-Remaining', $remaining);
                    $response->headers->set('X-RateLimit-Reset', now()->addMinutes(60)->timestamp);
                }

                return $response;
            }

            // Handle AuthenticationException - must be before RouteNotFoundException
            // This prevents Laravel from trying to redirect to login route
            if ($e instanceof AuthenticationException) {
                return ApiResponse::error(
                    'Unauthorized',
                    401,
                    ErrorCode::UNAUTHORIZED->value
                );
            }

            // Laravel validation exceptions
            if ($e instanceof LaravelValidationException or $e instanceof App\Exceptions\ValidationException) {
                return ApiResponse::error(
                    'The provided data failed validation',
                    422,
                    ErrorCode::VALIDATION_ERROR->value,
                    $e->errors()
                );
            }

            // HTTP exceptions (404, 403, ...)
            if ($e instanceof HttpException) {
                $status = $e->getStatusCode();
                $message = $e->getMessage() ?: match ($status) {
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

            // Fallback for other exceptions (uses Problem Details if enabled)
            return ApiResponse::fromException($e);
        });
    })->create();
