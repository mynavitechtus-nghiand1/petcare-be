<?php

namespace App\Services\Logging;

use App\Enums\ErrorCode;
use Throwable;

interface ErrorLoggerInterface
{
    public function logError(ErrorCode $errorCode, string $message, array $context = []): void;
    public function logException(Throwable $exception, array $context = []): void;
    public function logValidationFailure(array $errors, array $context = []): void;
    public function isEnabled(): bool;
}
