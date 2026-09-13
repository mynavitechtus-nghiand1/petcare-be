<?php

namespace App\Services\Logging;

use App\Enums\ErrorCode;
use Illuminate\Support\Facades\Log;
use Throwable;

class LaravelErrorLogger implements ErrorLoggerInterface
{
    public function logError(ErrorCode $errorCode, string $message, array $context = []): void
    {
        $channel = config('core.logging.error_channel', 'single');
        
        Log::channel($channel)->error($message, [
            'error_code' => $errorCode->value,
            'http_status' => $errorCode->getHttpStatus(),
            'severity' => $errorCode->getSeverity(),
            'context' => $context,
            'timestamp' => now()->toISOString(),
            'request_id' => request()->header('X-Request-ID'),
            'user_id' => auth()->id(),
            'ip_address' => request()->ip(),
        ]);
    }
    
    public function logException(Throwable $exception, array $context = []): void
    {
        $channel = config('core.logging.error_channel', 'single');
        
        Log::channel($channel)->error($exception->getMessage(), [
            'exception' => get_class($exception),
            'file' => $exception->getFile(),
            'line' => $exception->getLine(),
            'trace' => app()->environment(['local', 'testing']) ? $exception->getTraceAsString() : null,
            'context' => $context,
            'request_id' => request()->header('X-Request-ID'),
            'user_id' => auth()->id(),
            'url' => request()->fullUrl(),
        ]);
    }
    
    public function logValidationFailure(array $errors, array $context = []): void
    {
        $channel = config('core.logging.validation_channel', 'single');
        
        Log::channel($channel)->info('Validation failed', [
            'errors' => $errors,
            'request_data' => $this->sanitizeRequestData($context['request_data'] ?? []),
            'user_id' => auth()->id(),
            'ip' => request()->ip(),
            'url' => request()->fullUrl(),
            'user_agent' => request()->userAgent(),
        ]);
    }
    
    public function isEnabled(): bool
    {
        return true; // Laravel logging always enabled
    }
    
    private function sanitizeRequestData(array $data): array
    {
        $sensitiveFields = ['password', 'password_confirmation', 'token', 'secret'];
        
        foreach ($sensitiveFields as $field) {
            if (isset($data[$field])) {
                $data[$field] = '[REDACTED]';
            }
        }
        
        return $data;
    }
}
