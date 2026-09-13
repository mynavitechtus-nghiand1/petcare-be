<?php

namespace App\Exceptions;

use App\Enums\ErrorCode;
use Throwable;

class ResourceException extends BaseException
{
    public function __construct(
        ErrorCode $errorCode = ErrorCode::RESOURCE_NOT_FOUND,
        ?string $message = null,
        array $context = [],
        ?Throwable $previous = null
    ) {
        parent::__construct($errorCode, $message, $context, $previous);
    }
    
    /**
     * Create a resource not found exception
     */
    public static function notFound(string $resource, mixed $identifier = null, array $context = []): self
    {
        $message = "Resource not found: {$resource}";
        if ($identifier !== null) {
            $message .= " (ID: {$identifier})";
        }
        
        return new self(
            ErrorCode::RESOURCE_NOT_FOUND,
            $message,
            array_merge(['resource' => $resource, 'identifier' => $identifier], $context)
        );
    }
    
    /**
     * Create a resource already exists exception
     */
    public static function alreadyExists(string $resource, mixed $identifier = null, array $context = []): self
    {
        $message = "Resource already exists: {$resource}";
        if ($identifier !== null) {
            $message .= " (ID: {$identifier})";
        }
        
        return new self(
            ErrorCode::RESOURCE_ALREADY_EXISTS,
            $message,
            array_merge(['resource' => $resource, 'identifier' => $identifier], $context)
        );
    }
    
    /**
     * Create a resource conflict exception
     */
    public static function conflict(string $resource, string $reason = '', array $context = []): self
    {
        $message = "Resource conflict: {$resource}";
        if ($reason) {
            $message .= " - {$reason}";
        }
        
        return new self(
            ErrorCode::RESOURCE_CONFLICT,
            $message,
            array_merge(['resource' => $resource, 'reason' => $reason], $context)
        );
    }
    
    /**
     * Create a resource deleted exception
     */
    public static function deleted(string $resource, mixed $identifier = null, array $context = []): self
    {
        $message = "Resource has been deleted: {$resource}";
        if ($identifier !== null) {
            $message .= " (ID: {$identifier})";
        }
        
        return new self(
            ErrorCode::RESOURCE_DELETED,
            $message,
            array_merge(['resource' => $resource, 'identifier' => $identifier], $context)
        );
    }
    
    /**
     * Create a resource locked exception
     */
    public static function locked(string $resource, mixed $identifier = null, string $reason = '', array $context = []): self
    {
        $message = "Resource is locked: {$resource}";
        if ($identifier !== null) {
            $message .= " (ID: {$identifier})";
        }
        if ($reason) {
            $message .= " - {$reason}";
        }
        
        return new self(
            ErrorCode::RESOURCE_LOCKED,
            $message,
            array_merge([
                'resource' => $resource,
                'identifier' => $identifier,
                'reason' => $reason
            ], $context)
        );
    }
}
