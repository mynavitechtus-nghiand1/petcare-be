<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Http\JsonResponse;
use App\Enums\ErrorCode;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use App\Exceptions\BaseException;
use App\Http\Resources\ApiResponse;
use App\Services\Logging\LoggerFactory;
use App\Services\Logging\ErrorLoggerInterface;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Throwable;

class Handler extends ExceptionHandler
{
    private ErrorLoggerInterface $errorLogger;

    /**
     * The list of the inputs that are never flashed to the session on validation exceptions.
     *
     * @var array<int, string>
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    /**
     * Register the exception handling callbacks for the application.
     */
    public function register(): void
    {
        // Initialize logger
        $this->errorLogger = LoggerFactory::createDefault();
        
        $this->reportable(function (Throwable $e) {
            // Enhanced logging with flexible logger
            if ($e instanceof BaseException) {
                $this->errorLogger->logError(
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
                $this->errorLogger->logException($e, [
                    'request_data' => request()->all(),
                    'headers' => request()->headers->all(),
                    'url' => request()->fullUrl(),
                    'method' => request()->method(),
                ]);
            }
        });
    }

    /**
     * Render an exception into an HTTP response.
     */
    public function render($request, Throwable $e): JsonResponse|\Symfony\Component\HttpFoundation\Response
    {
        // Handle API requests with consistent formatting
        if ($request->expectsJson() || $request->is('api/*')) {
            return $this->renderApiException($request, $e);
        }

        return parent::render($request, $e);
    }

    /**
     * Render API exceptions with consistent formatting.
     */
    protected function renderApiException(Request $request, Throwable $e): JsonResponse
    {
        // Handle AuthenticationException first - prevents redirect to login route
        if ($e instanceof AuthenticationException) {
            return ApiResponse::error(
                'Unauthorized',
                401,
                ErrorCode::UNAUTHORIZED->value
            );
        }

        // Handle our custom BaseException
        if ($e instanceof BaseException) {
            return ApiResponse::fromException($e);
        }

        // Handle Laravel ValidationException
        if ($e instanceof ValidationException) {
            return ApiResponse::error(
                'The provided data failed validation',
                422,
                'VALIDATION_FAILED',
                $e->errors()
            );
        }

        // Handle Laravel ModelNotFoundException (e.g. findOrFail when model not found)
        if ($e instanceof ModelNotFoundException) {
            return ApiResponse::notFound('Resource not found');
        }

        // Handle HTTP exceptions (404, 403, etc.)
        if ($e instanceof HttpException) {
            return ApiResponse::error(
                $e->getMessage() ?: $this->getDefaultHttpMessage($e->getStatusCode()),
                $e->getStatusCode(),
                $this->getHttpErrorCode($e->getStatusCode())
            );
        }

        // Handle other exceptions
        return $this->renderGenericException($e);
    }

    /**
     * Convert an authentication exception into a response.
     * Since this application is API-only, always return JSON response instead of redirecting.
     */
    protected function unauthenticated($request, AuthenticationException $exception)
    {
        // Always return JSON response for API-only application
        // No redirect to login route
        return ApiResponse::error(
            'Unauthorized',
            401,
            ErrorCode::UNAUTHORIZED->value
        );
    }

    /**
     * Render generic exceptions.
     */
    protected function renderGenericException(Throwable $e): JsonResponse
    {
        $status = 500;
        $message = 'Internal Server Error';
        $errors = null;
        $errorCode = ErrorCode::SYSTEM_ERROR;

        // Show detailed errors in debug mode
        if (config('app.debug')) {
            $message = $e->getMessage() ?: 'Internal Server Error';
            $errors = [
                'exception' => get_class($e),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => collect($e->getTrace())->take(5)->toArray(), // Limit trace for readability
            ];
        }

        return ApiResponse::error($message, $status, $errorCode?->value, $errors);
    }

    /**
     * Get default HTTP error message.
     */
    protected function getDefaultHttpMessage(int $statusCode): string
    {
        return match($statusCode) {
            401 => 'Unauthorized',
            403 => 'Forbidden', 
            404 => 'Not Found',
            405 => 'Method Not Allowed',
            429 => 'Too Many Requests',
            500 => 'Internal Server Error',
            503 => 'Service Unavailable',
            default => 'HTTP Error',
        };
    }

    /**
     * Get HTTP error code for standardized responses.
     */
    protected function getHttpErrorCode(int $statusCode): string
    {
        return match($statusCode) {
            401 => ErrorCode::UNAUTHORIZED->value,
            403 => ErrorCode::ACCESS_DENIED->value,
            404 => ErrorCode::RESOURCE_NOT_FOUND->value, 
            405 => 'METHOD_NOT_ALLOWED',
            429 => ErrorCode::RATE_LIMIT_EXCEEDED->value,
            500 => ErrorCode::SYSTEM_ERROR->value,
            503 => ErrorCode::SYSTEM_UNAVAILABLE->value,
            default => 'HTTP_ERROR',
        };
    }
}
