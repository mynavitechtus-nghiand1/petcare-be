<?php

namespace App\Services\Logging;

use App\Enums\ErrorCode;
use Throwable;

class NullErrorLogger implements ErrorLoggerInterface
{
    public function logError(ErrorCode $errorCode, string $message, array $context = []): void
    {
        // No logging - useful for testing or when logging is disabled
    }
    
    public function logException(Throwable $exception, array $context = []): void
    {
        // No logging
    }
    
    public function logValidationFailure(array $errors, array $context = []): void
    {
        // No logging
    }
    
    public function isEnabled(): bool
    {
        return false;
    }
}
