<?php

namespace App\Exceptions;

use App\Enums\ErrorCode;
use Throwable;

class BusinessLogicException extends BaseException
{
    public function __construct(
        ErrorCode $errorCode = ErrorCode::BUSINESS_RULE_VIOLATION,
        ?string $message = null,
        array $context = [],
        ?Throwable $previous = null
    ) {
        parent::__construct($errorCode, $message, $context, $previous);
    }
    
    /**
     * Create a business rule violation exception
     */
    public static function ruleViolation(string $rule, array $context = []): self
    {
        return new self(
            ErrorCode::BUSINESS_RULE_VIOLATION,
            "Business rule violation: {$rule}",
            array_merge(['rule' => $rule], $context)
        );
    }
    
    /**
     * Create an invalid operation exception
     */
    public static function invalidOperation(string $operation, array $context = []): self
    {
        return new self(
            ErrorCode::INVALID_OPERATION,
            "Invalid operation: {$operation}",
            array_merge(['operation' => $operation], $context)
        );
    }
    
    /**
     * Create an operation not allowed exception
     */
    public static function operationNotAllowed(string $operation, string $reason = '', array $context = []): self
    {
        $message = "Operation not allowed: {$operation}";
        if ($reason) {
            $message .= " - {$reason}";
        }
        
        return new self(
            ErrorCode::OPERATION_NOT_ALLOWED,
            $message,
            array_merge(['operation' => $operation, 'reason' => $reason], $context)
        );
    }
    
    /**
     * Create a quota exceeded exception
     */
    public static function quotaExceeded(string $resource, int $limit, int $current = null, array $context = []): self
    {
        $message = "Quota exceeded for {$resource}. Limit: {$limit}";
        if ($current !== null) {
            $message .= ", Current: {$current}";
        }
        
        return new self(
            ErrorCode::QUOTA_EXCEEDED,
            $message,
            array_merge([
                'resource' => $resource,
                'limit' => $limit,
                'current' => $current
            ], $context)
        );
    }
    
    /**
     * Create a duplicate entry exception
     */
    public static function duplicateEntry(string $field, mixed $value, array $context = []): self
    {
        return new self(
            ErrorCode::DUPLICATE_ENTRY,
            "Duplicate entry for {$field}: {$value}",
            array_merge(['field' => $field, 'value' => $value], $context)
        );
    }
}
