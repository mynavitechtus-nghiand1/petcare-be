<?php

namespace App\Services\Logging;

use App\Enums\ErrorCode;
use Throwable;

class SentryErrorLogger implements ErrorLoggerInterface
{
    public function logError(ErrorCode $errorCode, string $message, array $context = []): void
    {
        if (!$this->isEnabled()) {
            return;
        }
        
        if (function_exists('\Sentry\withScope')) {
            \Sentry\withScope(function ($scope) use ($errorCode, $message, $context) {
                $scope->setTag('error_code', $errorCode->value);
                $scope->setTag('severity', $errorCode->getSeverity());
                $scope->setTag('http_status', (string)$errorCode->getHttpStatus());
                $scope->setContext('error_details', $context);
                $scope->setUser([
                    'id' => auth()->id(),
                    'ip_address' => request()->ip(),
                ]);
                
                \Sentry\captureMessage($message);
            });
        }
    }
    
    public function logException(Throwable $exception, array $context = []): void
    {
        if (!$this->isEnabled()) {
            return;
        }
        
        if (function_exists('\Sentry\withScope')) {
            \Sentry\withScope(function ($scope) use ($exception, $context) {
                $scope->setContext('additional_context', $context);
                $scope->setUser([
                    'id' => auth()->id(),
                    'ip_address' => request()->ip(),
                ]);
                
                \Sentry\captureException($exception);
            });
        }
    }
    
    public function logValidationFailure(array $errors, array $context = []): void
    {
        if (!$this->isEnabled()) {
            return;
        }
        
        if (function_exists('\Sentry\withScope')) {
            \Sentry\withScope(function ($scope) use ($errors, $context) {
                $scope->setTag('event_type', 'validation_failure');
                $scope->setContext('validation_errors', $errors);
                $scope->setContext('request_context', $context);
                
                \Sentry\captureMessage('Validation failed');
            });
        }
    }
    
    public function isEnabled(): bool
    {
        return app()->bound('sentry') && 
               config('core.logging.sentry.enabled', false) &&
               !empty(config('sentry.dsn')) &&
               function_exists('\Sentry\captureMessage');
    }
}
