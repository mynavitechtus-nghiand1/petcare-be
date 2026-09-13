<?php

namespace App\Exceptions;

use Exception;
use App\Enums\ErrorCode;
use Throwable;

class BaseException extends Exception
{
    public function __construct(
        protected readonly ErrorCode $errorCode,
        ?string $message = null,
        protected array $context = [],
        ?Throwable $previous = null
    ) {
        $finalMessage = $message ?? $errorCode->getMessage();
        $httpStatusCode = $errorCode->getHttpStatus();
        
        parent::__construct($finalMessage, $httpStatusCode, $previous);
    }
    
    /**
     * Get the error code
     */
    public function getErrorCode(): ErrorCode
    {
        return $this->errorCode;
    }
    
    /**
     * Get the HTTP status code
     */
    public function getHttpStatus(): int
    {
        return $this->errorCode->getHttpStatus();
    }
    
    /**
     * Get error context
     */
    public function getContext(): array
    {
        return $this->context;
    }
    
    /**
     * Get error severity
     */
    public function getSeverity(): string
    {
        return $this->errorCode->getSeverity();
    }
    
    /**
     * Add context to the exception
     */
    public function withContext(array $context): self
    {
        $this->context = array_merge($this->context, $context);
        return $this;
    }
    
    /**
     * Convert exception to array for API response
     */
    public function toArray(): array
    {
        return [
            'error_code' => $this->errorCode->name,
            'message' => $this->getMessage(),
            'context' => $this->context,
            'timestamp' => now()->toISOString(),
        ];
    }
    
    /**
     * Convert exception to string for logging
     */
    public function __toString(): string
    {
        return sprintf(
            '[%s] %s (Code: %s, Severity: %s) in %s:%d',
            class_basename($this),
            $this->getMessage(),
            $this->errorCode->value,
            $this->getSeverity(),
            $this->getFile(),
            $this->getLine()
        );
    }
}
